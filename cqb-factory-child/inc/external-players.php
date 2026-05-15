<?php
/**
 * Integracion de jugadores CQB Factory con una base MySQL externa.
 */

if (!defined('ABSPATH')) {
    exit;
}

function cqb_factory_external_db_config_value($constant_name, $env_name, $default = null)
{
    if (defined($constant_name) && constant($constant_name) !== '') {
        return constant($constant_name);
    }

    $env_value = getenv($env_name);

    if ($env_value !== false && $env_value !== '') {
        return $env_value;
    }

    return $default;
}

function cqb_factory_external_db_is_configured()
{
    return (bool) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_HOST', 'DB_HOST')
        && (bool) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_NAME', 'DB_NAME')
        && (bool) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_USER', 'DB_USER')
        && (null !== cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_PASSWORD', 'DB_PASSWORD'));
}

function cqb_factory_external_players_table_name()
{
    $table_name = cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_TABLE', 'DB_TABLE');

    if ($table_name) {
        return $table_name;
    }

    return 'cqb_factory_players';
}

function cqb_factory_external_db_connect()
{
    static $connection = null;
    static $error = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    if ($error instanceof WP_Error) {
        return $error;
    }

    if (!cqb_factory_external_db_is_configured()) {
        $error = new WP_Error('cqb_ext_db_not_configured', 'Faltan credenciales de la base MySQL externa.');
        return $error;
    }

    if (!class_exists('mysqli')) {
        $error = new WP_Error('cqb_ext_db_missing_extension', 'La extension mysqli no esta disponible en el servidor.');
        return $error;
    }

    $host = (string) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_HOST', 'DB_HOST');
    $database = (string) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_NAME', 'DB_NAME');
    $username = (string) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_USER', 'DB_USER');
    $password = (string) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_PASSWORD', 'DB_PASSWORD', '');
    $port = (int) cqb_factory_external_db_config_value('CQB_FACTORY_EXT_DB_PORT', 'DB_PORT', 3306);

    $connection = @new mysqli($host, $username, $password, $database, $port);

    if ($connection->connect_errno) {
        $error = new WP_Error('cqb_ext_db_connection_failed', $connection->connect_error);
        $connection = null;
        return $error;
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}

function cqb_factory_external_players_schema_version()
{
    return '1.1.0';
}

function cqb_factory_external_players_install_table()
{
    if (!cqb_factory_external_db_is_configured()) {
        return new WP_Error('cqb_ext_db_not_configured', 'La base externa aun no esta configurada.');
    }

    $installed_version = get_option('cqb_factory_external_players_schema_version');

    if ($installed_version === cqb_factory_external_players_schema_version()) {
        return true;
    }

    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return $db;
    }

    $table = cqb_factory_external_players_table_name();
    $table_sql = sprintf(
        'CREATE TABLE IF NOT EXISTS `%s` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `full_name` VARCHAR(120) NOT NULL,
            `email` VARCHAR(190) NOT NULL,
            `password_hash` VARCHAR(255) NOT NULL,
            `is_moderator` TINYINT(1) NOT NULL DEFAULT 0,
            `victories` INT UNSIGNED NOT NULL DEFAULT 0,
            `defeats` INT UNSIGNED NOT NULL DEFAULT 0,
            `matches_played` INT UNSIGNED NOT NULL DEFAULT 0,
            `active_reservations` INT UNSIGNED NOT NULL DEFAULT 0,
            `ratio` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email_unique` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        $db->real_escape_string($table)
    );

    if (!$db->query($table_sql)) {
        return new WP_Error('cqb_ext_db_schema_failed', $db->error);
    }

    $column_exists = $db->query("SHOW COLUMNS FROM `{$table}` LIKE 'is_moderator'");

    if ($column_exists && $column_exists->num_rows === 0) {
        if (!$db->query("ALTER TABLE `{$table}` ADD COLUMN `is_moderator` TINYINT(1) NOT NULL DEFAULT 0 AFTER `password_hash`")) {
            return new WP_Error('cqb_ext_db_alter_failed', $db->error);
        }
    }

    update_option('cqb_factory_external_players_schema_version', cqb_factory_external_players_schema_version());

    return true;
}

add_action('init', 'cqb_factory_external_players_install_table', 5);

function cqb_factory_player_cookie_name()
{
    return 'cqb_factory_player';
}

function cqb_factory_player_cookie_expiration()
{
    return time() + WEEK_IN_SECONDS;
}

function cqb_factory_player_cookie_signature($player_id, $expiration, $email, $password_hash)
{
    $payload = implode('|', [(string) $player_id, (string) $expiration, strtolower($email), $password_hash]);

    return hash_hmac('sha256', $payload, wp_salt('auth'));
}

function cqb_factory_set_current_player_cookie(array $player)
{
    $expiration = cqb_factory_player_cookie_expiration();
    $signature = cqb_factory_player_cookie_signature($player['id'], $expiration, $player['email'], $player['password_hash']);
    $value = implode('|', [$player['id'], $expiration, $signature]);
    $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';

    $paths = array_unique(array_filter([
        defined('COOKIEPATH') ? COOKIEPATH : '/',
        defined('SITECOOKIEPATH') ? SITECOOKIEPATH : '/',
    ]));

    foreach ($paths as $path) {
        setcookie(cqb_factory_player_cookie_name(), $value, $expiration, $path, $cookie_domain, is_ssl(), true);
    }

    $_COOKIE[cqb_factory_player_cookie_name()] = $value;
}

function cqb_factory_clear_current_player_cookie()
{
    $cookie_domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
    $paths = array_unique(array_filter([
        defined('COOKIEPATH') ? COOKIEPATH : '/',
        defined('SITECOOKIEPATH') ? SITECOOKIEPATH : '/',
    ]));

    foreach ($paths as $path) {
        setcookie(cqb_factory_player_cookie_name(), '', time() - HOUR_IN_SECONDS, $path, $cookie_domain, is_ssl(), true);
    }

    unset($_COOKIE[cqb_factory_player_cookie_name()]);
}

function cqb_factory_map_player_row(array $row)
{
    $row['id'] = (int) $row['id'];
    $row['is_moderator'] = !empty($row['is_moderator']);
    $row['victories'] = (int) $row['victories'];
    $row['defeats'] = (int) $row['defeats'];
    $row['matches_played'] = (int) $row['matches_played'];
    $row['active_reservations'] = (int) $row['active_reservations'];
    $row['ratio'] = number_format((float) $row['ratio'], 2, '.', '');

    return $row;
}

function cqb_factory_get_player_by_email($email)
{
    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return $db;
    }

    $table = cqb_factory_external_players_table_name();
    $statement = $db->prepare("SELECT * FROM `{$table}` WHERE email = ? LIMIT 1");

    if (!$statement) {
        return new WP_Error('cqb_ext_db_prepare_failed', $db->error);
    }

    $normalized_email = sanitize_email($email);
    $statement->bind_param('s', $normalized_email);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $statement->close();

    if (!$row) {
        return null;
    }

    return cqb_factory_map_player_row($row);
}

function cqb_factory_get_player_by_id($player_id)
{
    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return $db;
    }

    $table = cqb_factory_external_players_table_name();
    $statement = $db->prepare("SELECT * FROM `{$table}` WHERE id = ? LIMIT 1");

    if (!$statement) {
        return new WP_Error('cqb_ext_db_prepare_failed', $db->error);
    }

    $player_id = (int) $player_id;
    $statement->bind_param('i', $player_id);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result ? $result->fetch_assoc() : null;
    $statement->close();

    if (!$row) {
        return null;
    }

    return cqb_factory_map_player_row($row);
}

function cqb_factory_calculate_player_ratio($victories, $defeats)
{
    $victories = max(0, (int) $victories);
    $defeats = max(0, (int) $defeats);

    if ($victories === 0) {
        return 0;
    }

    return round($victories / max($defeats, 1), 2);
}

function cqb_factory_create_player($full_name, $email, $password, $is_moderator = false)
{
    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return $db;
    }

    $table = cqb_factory_external_players_table_name();
    $statement = $db->prepare(
        "INSERT INTO `{$table}` (full_name, email, password_hash, is_moderator, victories, defeats, matches_played, active_reservations, ratio) VALUES (?, ?, ?, ?, 0, 0, 0, 0, 0.00)"
    );

    if (!$statement) {
        return new WP_Error('cqb_ext_db_prepare_failed', $db->error);
    }

    $normalized_name = sanitize_text_field($full_name);
    $normalized_email = sanitize_email($email);
    $password_hash = wp_hash_password($password);
    $moderator_flag = $is_moderator ? 1 : 0;

    $statement->bind_param('sssi', $normalized_name, $normalized_email, $password_hash, $moderator_flag);
    $inserted = $statement->execute();
    $insert_id = $db->insert_id;
    $error = $statement->error;
    $statement->close();

    if (!$inserted) {
        return new WP_Error('cqb_ext_db_insert_failed', $error ?: 'No fue posible crear el jugador.');
    }

    return cqb_factory_get_player_by_id($insert_id);
}

function cqb_factory_update_player_moderator_flag($player_id, $is_moderator)
{
    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return $db;
    }

    $table = cqb_factory_external_players_table_name();
    $statement = $db->prepare("UPDATE `{$table}` SET is_moderator = ? WHERE id = ?");

    if (!$statement) {
        return new WP_Error('cqb_ext_db_prepare_failed', $db->error);
    }

    $moderator_flag = $is_moderator ? 1 : 0;
    $player_id = (int) $player_id;
    $statement->bind_param('ii', $moderator_flag, $player_id);
    $updated = $statement->execute();
    $error = $statement->error;
    $statement->close();

    if (!$updated) {
        return new WP_Error('cqb_ext_db_update_failed', $error ?: 'No fue posible actualizar el rol moderador.');
    }

    return true;
}

function cqb_factory_external_moderator_emails()
{
    $emails = [];
    $configured = cqb_factory_external_db_config_value('CQB_FACTORY_MODERATOR_EMAILS', 'CQB_FACTORY_MODERATOR_EMAILS', '');

    if ($configured) {
        $emails = array_merge($emails, explode(',', (string) $configured));
    }

    $bootstrap_email = cqb_factory_external_db_config_value('CQB_FACTORY_BOOTSTRAP_MOD_EMAIL', 'CQB_FACTORY_BOOTSTRAP_MOD_EMAIL', '');

    if ($bootstrap_email) {
        $emails[] = $bootstrap_email;
    }

    $emails = array_map('sanitize_email', $emails);
    $emails = array_filter($emails, 'is_email');

    return array_values(array_unique(array_map('strtolower', $emails)));
}

function cqb_factory_player_can_moderate($player)
{
    if (!$player || !is_array($player)) {
        return false;
    }

    if (!empty($player['is_moderator'])) {
        return true;
    }

    $email = isset($player['email']) ? strtolower(sanitize_email($player['email'])) : '';

    return $email && in_array($email, cqb_factory_external_moderator_emails(), true);
}

function cqb_factory_bootstrap_external_moderator_account()
{
    static $bootstrapped = false;

    if ($bootstrapped || !cqb_factory_external_db_is_configured()) {
        return;
    }

    $bootstrapped = true;
    $result = cqb_factory_external_players_install_table();

    if (is_wp_error($result)) {
        return;
    }

    $full_name = cqb_factory_external_db_config_value('CQB_FACTORY_BOOTSTRAP_MOD_NAME', 'CQB_FACTORY_BOOTSTRAP_MOD_NAME', '');
    $email = cqb_factory_external_db_config_value('CQB_FACTORY_BOOTSTRAP_MOD_EMAIL', 'CQB_FACTORY_BOOTSTRAP_MOD_EMAIL', '');
    $password = cqb_factory_external_db_config_value('CQB_FACTORY_BOOTSTRAP_MOD_PASSWORD', 'CQB_FACTORY_BOOTSTRAP_MOD_PASSWORD', '');

    if (!$full_name || !is_email($email) || strlen((string) $password) < 8) {
        return;
    }

    $player = cqb_factory_get_player_by_email($email);

    if (is_wp_error($player)) {
        return;
    }

    if (!$player) {
        cqb_factory_create_player($full_name, $email, $password, true);
        return;
    }

    if (empty($player['is_moderator'])) {
        cqb_factory_update_player_moderator_flag($player['id'], true);
    }
}

add_action('init', 'cqb_factory_bootstrap_external_moderator_account', 8);

function cqb_factory_get_current_player()
{
    static $player = false;

    if ($player !== false) {
        return $player;
    }

    $cookie = isset($_COOKIE[cqb_factory_player_cookie_name()]) ? sanitize_text_field(wp_unslash($_COOKIE[cqb_factory_player_cookie_name()])) : '';

    if (!$cookie) {
        $player = null;
        return $player;
    }

    $parts = explode('|', $cookie);

    if (count($parts) !== 3) {
        cqb_factory_clear_current_player_cookie();
        $player = null;
        return $player;
    }

    [$player_id, $expiration, $signature] = $parts;
    $player_id = (int) $player_id;
    $expiration = (int) $expiration;

    if ($player_id <= 0 || $expiration < time()) {
        cqb_factory_clear_current_player_cookie();
        $player = null;
        return $player;
    }

    $player_data = cqb_factory_get_player_by_id($player_id);

    if (!$player_data || is_wp_error($player_data)) {
        cqb_factory_clear_current_player_cookie();
        $player = null;
        return $player;
    }

    $expected_signature = cqb_factory_player_cookie_signature($player_data['id'], $expiration, $player_data['email'], $player_data['password_hash']);

    if (!hash_equals($expected_signature, $signature)) {
        cqb_factory_clear_current_player_cookie();
        $player = null;
        return $player;
    }

    $player = $player_data;

    return $player;
}

function cqb_factory_player_count()
{
    $db = cqb_factory_external_db_connect();

    if (is_wp_error($db)) {
        return 0;
    }

    $table = cqb_factory_external_players_table_name();
    $result = $db->query("SELECT COUNT(*) AS total FROM `{$table}`");

    if (!$result) {
        return 0;
    }

    $row = $result->fetch_assoc();

    return isset($row['total']) ? (int) $row['total'] : 0;
}

function cqb_factory_player_notice_catalog()
{
    return [
        'registered' => ['type' => 'success', 'message' => 'Cuenta creada correctamente. Ya puedes reservar y revisar tu progreso.'],
        'logged-in' => ['type' => 'success', 'message' => 'Sesion iniciada. Tu panel ya esta sincronizado con tu cuenta.'],
        'logged-out' => ['type' => 'success', 'message' => 'Sesion cerrada correctamente.'],
        'missing-config' => ['type' => 'error', 'message' => 'Falta configurar la conexion MySQL externa en wp-config.php.'],
        'invalid-registration' => ['type' => 'error', 'message' => 'Completa nombre, correo valido y una contrasena de minimo 8 caracteres.'],
        'email-exists' => ['type' => 'error', 'message' => 'Ese correo ya esta registrado. Inicia sesion con esa cuenta.'],
        'registration-failed' => ['type' => 'error', 'message' => 'No fue posible crear la cuenta en la base externa.'],
        'invalid-login' => ['type' => 'error', 'message' => 'Correo o contrasena invalidos.'],
    ];
}

function cqb_factory_get_player_notice()
{
    $notice_key = isset($_GET['cqb_notice']) ? sanitize_key(wp_unslash($_GET['cqb_notice'])) : '';
    $catalog = cqb_factory_player_notice_catalog();

    return $notice_key && isset($catalog[$notice_key]) ? $catalog[$notice_key] : null;
}

function cqb_factory_redirect_with_notice($notice_key)
{
    $target = add_query_arg('cqb_notice', $notice_key, home_url('/'));
    $target .= '#cuenta';

    wp_safe_redirect($target);
    exit;
}

function cqb_factory_handle_player_auth_requests()
{
    if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['cqb_player_action'])) {
        return;
    }

    $action = sanitize_key(wp_unslash($_POST['cqb_player_action']));
    $nonce = isset($_POST['cqb_player_nonce']) ? sanitize_text_field(wp_unslash($_POST['cqb_player_nonce'])) : '';

    if (!wp_verify_nonce($nonce, 'cqb_player_action')) {
        cqb_factory_redirect_with_notice('registration-failed');
    }

    if (!cqb_factory_external_db_is_configured()) {
        cqb_factory_redirect_with_notice('missing-config');
    }

    cqb_factory_external_players_install_table();

    if ($action === 'register') {
        $full_name = isset($_POST['nombre']) ? sanitize_text_field(wp_unslash($_POST['nombre'])) : '';
        $email = isset($_POST['correo']) ? sanitize_email(wp_unslash($_POST['correo'])) : '';
        $password = isset($_POST['contrasena']) ? (string) wp_unslash($_POST['contrasena']) : '';

        if (!$full_name || !is_email($email) || strlen($password) < 8) {
            cqb_factory_redirect_with_notice('invalid-registration');
        }

        $existing_player = cqb_factory_get_player_by_email($email);

        if ($existing_player instanceof WP_Error) {
            cqb_factory_redirect_with_notice('registration-failed');
        }

        if ($existing_player) {
            cqb_factory_redirect_with_notice('email-exists');
        }

        $player = cqb_factory_create_player($full_name, $email, $password);

        if (!$player || is_wp_error($player)) {
            cqb_factory_redirect_with_notice('registration-failed');
        }

        cqb_factory_set_current_player_cookie($player);
        cqb_factory_redirect_with_notice('registered');
    }

    if ($action === 'login') {
        $email = isset($_POST['correo_login']) ? sanitize_email(wp_unslash($_POST['correo_login'])) : '';
        $password = isset($_POST['pass_login']) ? (string) wp_unslash($_POST['pass_login']) : '';

        if (!is_email($email) || $password === '') {
            cqb_factory_redirect_with_notice('invalid-login');
        }

        $player = cqb_factory_get_player_by_email($email);

        if (!$player || is_wp_error($player) || !wp_check_password($password, $player['password_hash'])) {
            cqb_factory_redirect_with_notice('invalid-login');
        }

        cqb_factory_set_current_player_cookie($player);
        cqb_factory_redirect_with_notice('logged-in');
    }

    if ($action === 'logout') {
        cqb_factory_clear_current_player_cookie();
        cqb_factory_redirect_with_notice('logged-out');
    }
}

add_action('init', 'cqb_factory_handle_player_auth_requests', 20);