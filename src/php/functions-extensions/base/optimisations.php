<?php
/**
 * WordPress Optimizations
 * This file contains optimizations to remove unnecessary WordPress junk
 */

// =============================================================================
// HEAD CLEANUP
// =============================================================================

function wp_boilerplate_clean_head() {
    // Remove version info
    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');

    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('template_redirect', 'rest_output_link_header', 11);

    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
}
add_action('init', 'wp_boilerplate_clean_head');

// =============================================================================
// STYLES & SCRIPTS CLEANUP
// =============================================================================

function wp_boilerplate_clean_assets() {
    // Remove emoji scripts and styles
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove classic theme styles
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');

    // Remove global styles (theme.json)
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');

    // Remove responsive image inline styles
    remove_action('wp_enqueue_scripts', 'wp_enqueue_responsive_images', 10);
}
add_action('init', 'wp_boilerplate_clean_assets');


function wp_boilerplate_dequeue_styles() {
    // Block library styles
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-blocks-style'); // WooCommerce blocks

    // Classic theme styles (fallback)
    wp_dequeue_style('classic-theme-styles');
    wp_deregister_style('classic-theme-styles');

    // Global styles
    wp_dequeue_style('global-styles');
}
add_action('wp_enqueue_scripts', 'wp_boilerplate_dequeue_styles', 100);

// =============================================================================
// COMMENTS SYSTEM REMOVAL
// =============================================================================


function wp_boilerplate_disable_comments() {
    // Remove support from all post types
    foreach (get_post_types() as $type) {
        if (post_type_supports($type, 'comments')) {
            remove_post_type_support($type, 'comments');
            remove_post_type_support($type, 'trackbacks');
        }
    }

    // Redirect comments page in admin
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'wp_boilerplate_disable_comments');


function wp_boilerplate_remove_comments_admin() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'wp_boilerplate_remove_comments_admin');

function wp_boilerplate_remove_comments_admin_bar() {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}
add_action('init', 'wp_boilerplate_remove_comments_admin_bar');

// Disable comments functionality
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);