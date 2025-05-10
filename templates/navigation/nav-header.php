<?php
/**
 * Header Navigation Template
 * 
 */
?>

<header class="border-b border-gray-200">
    <div class="container py-4">
        <div class="flex items-center justify-between py-5 max-w-full">

            <!-- Logo -->
            <a href="<?php echo home_url('/'); ?>" class="pointer-events-auto">
                <img src="<?php echo get_template_directory_uri();?>/src/media/sample-logo.svg" alt="Logo" class="w-20">
            </a>

            <!-- Mobile Burger Button -->
            <button id="mobile-menu-button" 
                    class="flex flex-col items-center justify-center lg:hidden w-8 h-8 gap-1.5 z-50" >
                <span class="block w-6 h-0.5 bg-gray-800 transform origin-center transition-all duration-300 ease-in-out"></span>
                <span class="block w-6 h-0.5 bg-gray-800 transform origin-center transition-all duration-300 ease-in-out"></span>
                <span class="block w-6 h-0.5 bg-gray-800 transform origin-center transition-all duration-300 ease-in-out"></span>
            </button>

            <!-- Menu -->
            <nav id="menu" 
                 class="flex flex-col lg:flex-row 
                        items-center justify-center lg:justify-end
                        w-full h-screen lg:w-auto lg:h-auto
                        fixed lg:relative 
                        inset-0 lg:inset-auto 
                        -translate-x-full lg:translate-x-0
                        z-40
                        bg-white lg:bg-transparent">
                <?php 
                wp_nav_menu([
                    'theme_location' => 'header_menu',
                    'menu_class'     => 'flex flex-col lg:flex-row
                                         items-center justify-center lg:justify-start
                                         gap-8
                                         text-xl lg:text-base'
                ]); 
                ?>
            </nav>
            
        </div>
    </div>
</header>