<?php

function load_scripts() {
    $entry_js  = get_stylesheet_directory_uri() . '/dist/app.js';
    
    // create version codes.
    $entry_js_ver  = '007-' . filemtime(__DIR__ . '/dist/app.js');
    
    wp_enqueue_script('customtheme-scripts', $entry_js, null, $entry_js_ver, false);
}

add_action('wp_enqueue_scripts', 'load_scripts');

// Load template handling functionality
require_once get_template_directory() . '/src/php/render-page-templates.php';

// Add this line after the render-templates.php require
require_once get_template_directory() . '/src/php/render-post-templates.php';