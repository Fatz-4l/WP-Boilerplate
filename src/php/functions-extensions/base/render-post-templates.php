<?php
/**
 * Post template handling functionality
 */

// Register available post templates
add_filter('theme_templates', function($post_templates, $wp_theme, $post, $post_type) {
    if ($post_type === 'post') {
        $templates_dir = get_template_directory() . '/templates/posts';
        
        if (is_dir($templates_dir)) {
            $custom_templates = glob($templates_dir . '/single-*.php');
            foreach ($custom_templates as $template) {
                $template_data = get_file_data($template, array('Template Name' => 'Template Name'));
                if (!empty($template_data['Template Name'])) {
                    $template_name = basename($template);
                    $post_templates[$template_name] = $template_data['Template Name'];
                }
            }
        }
    }
    return $post_templates;
}, 10, 4);

// Load the selected template
add_filter('single_template', function($template) {
    global $post;
    $selected = get_post_meta($post->ID, '_wp_page_template', true);
    
    if (!empty($selected) && $selected !== 'default') {
        $custom_template = get_template_directory() . '/templates/posts/' . basename($selected);
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    
    // Default template
    $default = get_template_directory() . '/templates/posts/single.php';
    return file_exists($default) ? $default : $template;
}); 