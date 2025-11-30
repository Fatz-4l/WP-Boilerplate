<?php
/**
 * SVG Support
 * This file adds SVG support to the WordPress media library
 */

function svg_mime_types($mimes) {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'svg_mime_types');