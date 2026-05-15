<?php
/**
 * Funciones del tema hijo CQB Factory.
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'cqb-google-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Manrope:wght@400;500;600;700;800&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'parent-style',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme(get_template())->get('Version')
    );

    wp_enqueue_style(
        'cqb-factory-child-style',
        get_stylesheet_uri(),
        ['parent-style', 'cqb-google-fonts'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'cqb-factory-child-script',
        get_stylesheet_directory_uri() . '/assets/js/theme.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );
});

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
});

/**
 * Determina si el usuario actual tiene acceso al panel de moderacion.
 */
function cqb_factory_user_can_moderate()
{
    if (!is_user_logged_in()) {
        return false;
    }

    if (current_user_can('cqb_moderation_access')) {
        return true;
    }

    $user = wp_get_current_user();
    $roles = is_array($user->roles) ? $user->roles : [];
    $role_match = in_array('moderator', $roles, true) || in_array('administrator', $roles, true);

    if ($role_match) {
        return true;
    }

    return current_user_can('moderate_comments');
}
