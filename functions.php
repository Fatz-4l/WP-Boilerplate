<?php

//Entry point for the theme
function load_build_files() {
    $entry_js  = get_stylesheet_directory_uri() . '/dist/app.js';
    $entry_css = get_stylesheet_directory_uri() . '/dist/app.css';

    //Versioned Build Files
    $entry_js_ver  = '007-' . filemtime(__DIR__ . '/dist/app.js');
    $entry_css_ver = '007-' . filemtime(__DIR__ . '/dist/app.css');

    // CSS Build File
    wp_enqueue_style('wp-boilerplate-styles', $entry_css, [], $entry_css_ver);

    // JS Build File
    wp_enqueue_script('wp-boilerplate-scripts', $entry_js, [], $entry_js_ver);
}
add_action('wp_enqueue_scripts', 'load_build_files');

// Custom Image Sizes
add_image_size('xs', 300, 300, false);
add_image_size('sm', 600, 600, false);
add_image_size('md', 900, 900, false);
add_image_size('lg', 1200, 1200, false);
add_image_size('xl', 1600, 1600, false);
add_image_size('xxl', 2000, 2000, false);



// Load All PHP Files
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

// Load All PHP Files in src/php directory
php_require_all_files_in_directory(__DIR__ . '/src/php/*/*.php');
php_require_all_files_in_directory(__DIR__ . '/src/php/*/*/*.php');
php_require_all_files_in_directory(__DIR__ . '/src/php/*/*/*/*.php');

// Register Navigation Menus
register_nav_menus(array(
    'header_menu' => esc_html__('Header Menu', 'wp-boilerplate'),
    'footer_menu'  => esc_html__('Footer Menu', 'wp-boilerplate'),
));


// Shortcodes for ACF fields
add_filter('acf/format_value/type=textarea', 'do_shortcode');
add_filter('acf/format_value/type=text', 'do_shortcode');
add_filter('acf/format_value/type=wysiwyg', 'do_shortcode');