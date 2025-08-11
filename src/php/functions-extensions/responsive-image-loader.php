<?php

function get_responsive_image($image_id, $loading = 'lazy') {
    if (!$image_id || !is_numeric($image_id)) return '';
    
    $srcset = wp_get_attachment_image_srcset($image_id, 'full');
    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    
    if ($loading === 'eager') {
        // Eager loading - output normal responsive image
        return sprintf(
            '<img src="%s" srcset="%s" alt="%s">',
            esc_url(wp_get_attachment_image_url($image_id, 'full')),
            esc_attr($srcset),
            esc_attr($alt)
        );
    }
    
    // Get low quality version for blur-up effect
    $low_quality_url = wp_get_attachment_image_url($image_id, 'thumbnail');
    
    // Lazy loading - use low quality image and data attributes
    return sprintf(
        '<img src="%s" 
             data-src="%s" 
             alt="%s" 
             loading="lazy"
             class="blur-load">',
        esc_url($low_quality_url),
        esc_attr($srcset),
        esc_attr($alt)
    );
}

function responsive_image($image_id, $loading = 'lazy') {
    echo get_responsive_image($image_id, $loading);
}