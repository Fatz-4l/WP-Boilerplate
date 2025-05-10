<?php

// Bold Shortcode
function bold_shortcode($atts, $content = null) {
    return '<span class="font-bold">' . $content . '</span>';
}

add_shortcode('bold', 'bold_shortcode');
