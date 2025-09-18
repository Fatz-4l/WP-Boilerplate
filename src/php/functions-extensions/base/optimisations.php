<?php
// Disable REST API
remove_action('wp_head', 'rest_output_link_wp_head', 10);

// Remove oEmbed discovery links
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);

// Remove REST API link from HTTP headers
remove_action('template_redirect', 'rest_output_link_header', 11);

// Disable XML-RPC and remove RSD link from header
function my_disable_xmlrpc_and_rsd() {
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');

    // Remove the RSD link from <head>
    remove_action('wp_head', 'rsd_link');
}
add_action('init', 'my_disable_xmlrpc_and_rsd');

function my_clean_wp_head() {
    // Disable emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove classic theme styles
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');

    // Remove global styles (theme.json)
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
}
add_action('init', 'my_clean_wp_head');

function my_clean_wp_head_assets() {
    // Emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Classic theme styles
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');

    // Global styles (theme.json / block editor)
    remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');

    // Responsive image inline style
    remove_action('wp_enqueue_scripts', 'wp_enqueue_responsive_images', 10);
}
add_action('init', 'my_clean_wp_head_assets');

// Remove classic theme styles from bing injected
add_action('init', function () {
    remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');
});

// Remove classic theme styles from bing injected
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('classic-theme-styles');
    wp_deregister_style('classic-theme-styles');
}, 100);


// Remove WP version from head and RSS
remove_action('wp_head', 'wp_generator');

// Remove WP version from RSS feeds
add_filter('the_generator', '__return_empty_string');


// Disable comments
add_action('admin_init', function () {
    // Remove support from all post types
    foreach (get_post_types() as $type) {
        if (post_type_supports($type, 'comments')) {
            remove_post_type_support($type, 'comments');
            remove_post_type_support($type, 'trackbacks');
        }
    }
    // Redirect comments page
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url()); exit;
    }
});

// Comments Removed Admin Bar
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
add_action('init', function () {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
});

// Disable Comments
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);