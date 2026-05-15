<?php
/**
 * Header del tema CQB Factory.
 */

if (!defined('ABSPATH')) {
    exit;
}

$home_url = esc_url(home_url('/'));
$can_moderate = function_exists('cqb_factory_user_can_moderate') && cqb_factory_user_can_moderate();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="cqb-factory">
    <header class="topbar">
        <div class="wrap nav">
            <a href="<?php echo esc_url($home_url . '#inicio'); ?>" class="brand" aria-label="Ir al inicio de CQB Factory">
                <span class="brand-mark" aria-hidden="true"></span>
                CQB FACTORY
            </a>

            <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu">MENU</button>

            <nav class="menu" id="mainMenu" aria-label="Menu principal">
                <a href="<?php echo esc_url($home_url . '#inicio'); ?>">Inicio</a>
                <a href="<?php echo esc_url($home_url . '#partidas'); ?>">Partidas</a>
                <a href="<?php echo esc_url($home_url . '#cuenta'); ?>">Cuenta</a>
                <a href="<?php echo esc_url($home_url . '#contacto'); ?>">Contacto</a>
                <?php if ($can_moderate) : ?>
                    <a href="<?php echo esc_url($home_url . '#moderadores'); ?>">Moderadores</a>
                <?php endif; ?>
            </nav>

            <a href="<?php echo esc_url($home_url . '#cuenta'); ?>" class="btn btn-primary">Crear cuenta</a>
        </div>
    </header>
