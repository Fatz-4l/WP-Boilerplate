<?php
/**
 * Header Navigation Template
 *
 */
?>

<header class="border-b border-gray-200 relative">
   <div class="container py-6">
      <div class="flex items-center justify-between max-w-full">

         <!-- Logo -->
         <a href="<?php echo home_url('/'); ?>" class="pointer-events-auto">
            <img src="<?php echo get_template_directory_uri();?>/src/media/sample-logo.svg" alt="Logo" class="w-20">
         </a>

         <!-- Desktop Menu -->
         <nav id="desktop-menu" class="hidden md:flex font-normal text-lg text-black">
            <?php
                wp_nav_menu([
                    'theme_location' => 'header_menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-row gap-6',
                    'walker'         => new Desktop_Nav_Walker(),
                ]);
                ?>
         </nav>

         <!-- Mobile Burger Button -->
         <button id="mobile-menu-button" class="md:hidden flex flex-col items-center justify-center w-8 h-8 gap-1.5 z-50">
            <div class="block w-6 h-0.5 bg-black transition-all duration-300"></div>
            <div class="block w-6 h-0.5 bg-black transition-all duration-300"></div>
         </button>

         <!-- Mobile Menu -->
         <nav id="mobile-menu" class="md:hidden bg-white text-lg text-black shadow-md
                        absolute top-full left-0 right-0  z-40
                        transition-all duration-300
                        overflow-y-auto overscroll-contain">
            <div class="container pt-0 pb-4">
               <?php
                    wp_nav_menu([
                        'theme_location' => 'header_menu',
                        'container'      => false,
                        'menu_class'     => 'flex flex-col ',
                        'walker'         => new Mobile_Nav_Walker(),
                    ]);
                ?>
            </div>
         </nav>

         <div class="text-black hidden md:block ">Login</div>
      </div>
   </div>
</header>