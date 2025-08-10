<?php
/**
 * Footer Navigation Template
 */
?>

<footer class="border-t border-gray-200">
    <div class="container">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-2">
            <img src="<?php echo get_template_directory_uri(); ?>/src/media/sample-logo.svg" alt="Logo" class="w-16 mb-6">
        </div>

        <!-- Footer Navigation -->
        <nav class="mb-8">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer_menu',
                'container'      => false,
                'menu_class'     => 'flex flex-wrap items-center justify-center gap-x-8 gap-y-4 text-black',
            ]);
            ?>
        </nav>

        <!-- Divider -->
        <div class="w-full h-px bg-gray-200 mb-8"></div>

        <!-- Bottom Section -->
        <div class="text-center flex flex-col md:flex-row items-center justify-center text-sm text-black">
            <div class="mb-4 md:mb-0">
                © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
            </div>
        </div>
    </div>
</footer>