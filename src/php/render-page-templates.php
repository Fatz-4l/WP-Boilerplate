<?php
/**
 * Page template handling functionality
 */

// Handle Page Templates
add_filter('template_include', function($template) {
    if (is_page()) {
        $custom_template = get_page_template_slug();
        
        if ($custom_template) {
            $template_path = get_template_directory() . '/templates/pages/' . basename($custom_template);
            if (file_exists($template_path)) {
                return $template_path;
            }
        }
        
        $default_page = get_template_directory() . '/templates/pages/page.php';
        return file_exists($default_page) ? $default_page : $template;
    }
    return $template;
});

// Register page templates
add_filter('theme_page_templates', function($page_templates) {
    $templates_dir = get_template_directory() . '/templates/pages';
    
    if (is_dir($templates_dir)) {
        $custom_templates = glob($templates_dir . '/*.php');
        foreach ($custom_templates as $template) {
            if (basename($template) === 'page.php') continue;
            
            $template_data = get_file_data($template, array('Template Name' => 'Template Name'));
            if (!empty($template_data['Template Name'])) {
                $template_path = 'templates/pages/' . basename($template);
                $page_templates[$template_path] = $template_data['Template Name'];
            }
        }
    }
    return $page_templates;
}); 