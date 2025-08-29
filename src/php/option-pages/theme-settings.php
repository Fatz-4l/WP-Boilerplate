<?php

/**
 * Theme Settings Options Page
 * Main options page for global theme settings
 */

// Add admin menu for theme settings
add_action('admin_menu', 'bwt_add_theme_settings_menu');

function bwt_add_theme_settings_menu() {
    // Main Theme Settings page
    add_menu_page(
        'Theme Settings',           // Page title
        'Theme Settings',           // Menu title
        'manage_options',           // Capability
        'theme-settings',           // Menu slug
        'bwt_theme_settings_page',  // Callback function
        'dashicons-admin-appearance', // Icon
        30                          // Position
    );

    // Import and register modular sub-pages
    bwt_load_option_subpages();
}

// Load all modular option sub-pages
function bwt_load_option_subpages() {
    // Include logo carousel settings
    require_once get_template_directory() . '/src/php/option-pages/logo-carousel-settings.php';
    bwt_add_logo_carousel_submenu();

    // Future sub-pages can be added here
    // require_once get_template_directory() . '/src/php/option-pages/contact-settings.php';
    // bwt_add_contact_submenu();
}

// Main theme settings page callback
function bwt_theme_settings_page() {
    ?>
<div class="wrap">
    <h1>Theme Settings</h1>
    <p>Manage global theme settings from the sub-pages in the menu.</p>
    <div class="card">
        <h2>Available Settings</h2>
        <ul>
            <li><a href="<?php echo admin_url('admin.php?page=logo-carousel-settings'); ?>">Logo Carousel Settings</a> - Configure logo carousel images and sizing</li>
        </ul>
    </div>
</div>
<?php
}

?>