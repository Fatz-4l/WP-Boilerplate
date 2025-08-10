<?php

//Entry point for the theme
function load_scripts() {
    $entry_js  = get_stylesheet_directory_uri() . '/dist/app.js';
    
    // create version codes.
    $entry_js_ver  = '007-' . filemtime(__DIR__ . '/dist/app.js');
    
    wp_enqueue_script('customtheme-scripts', $entry_js, null, $entry_js_ver, false);
}
add_action('wp_enqueue_scripts', 'load_scripts');


// Load all files in a directory
function php_require_all_files_in_directory($dir) {
    $files = glob($dir);
    if ($files === false) {
        return;
    }
    
    foreach ($files as $file) {
        if (is_file($file)) {
            require_once $file;
        }
    }
}



// Load all files from a directory
php_require_all_files_in_directory(__DIR__ . '/src/php/shortcodes/*.php');

//Load page templates
require_once get_template_directory() . '/src/php/render-page-templates.php';

//Load post templates
require_once get_template_directory() . '/src/php/render-post-templates.php';

// Load functions extensions
php_require_all_files_in_directory(__DIR__ . '/src/php/functions-extensions/*.php');

// Register Navigation Menus
register_nav_menus(array(
    'header_menu' => esc_html__('Header Menu', 'customtheme'),
    'footer_menu'  => esc_html__('Footer Menu', 'customtheme'),
));

// Add theme support for title tag
add_theme_support('title-tag');

// Shortcodes work in ACF fields
add_filter('acf/format_value/type=textarea', 'do_shortcode');
add_filter('acf/format_value/type=text', 'do_shortcode');
add_filter('acf/format_value/type=wysiwyg', 'do_shortcode');