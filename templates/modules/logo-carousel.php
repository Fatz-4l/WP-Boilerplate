<?php
/**
 * Logo Carousel Module
 */

$logos_data = get_option('logo_carousel_logos', []);
$logo_config = [];
$carousel_css = '';

foreach ($logos_data as $index => $logo) {
    if (!empty($logo['image'])) {
        $css_class = 'logo-carousel-img-' . $index;

        $logo_config[] = [
            'url' => $logo['image'],
            'alt' => 'Logo ' . ($index + 1),
            'css_class' => $css_class
        ];

        $carousel_css .= "
            .{$css_class} {
                height: {$logo['height_mobile']}rem;
                width: {$logo['width']};
            }
            @media (min-width: 1024px) {
                .{$css_class} {
                    height: {$logo['height_desktop']}rem;
                }
            }
        ";
    }
}

if (!empty($logo_config)): ?>

<style>
<?php echo $carousel_css;

?>.logo-carousel-container {
   overflow: hidden;
   white-space: nowrap;
}

.logo-carousel-track {
   display: inline-flex;
   animation: infiniteScroll 100s linear infinite;
   will-change: transform;
}

@keyframes infiniteScroll {
   from {
      transform: translateX(0);
   }

   to {
      transform: translateX(-50%);
   }
}

.logo-item {
   flex-shrink: 0;
   margin-right: 150px;
}
</style>

<div class="logo-carousel-container">
   <div class="logo-carousel-track flex items-center">
      <?php
        $logo_count = count($logo_config);
        $repeats = max(2, ceil(10 / $logo_count));

        for ($set = 0; $set < 2; $set++) {
            for ($repeat = 0; $repeat < $repeats; $repeat++) {
                foreach ($logo_config as $logo) {
                    ?>
      <div class="logo-item">
         <img src="<?= esc_url($logo['url']); ?>"
              alt="<?= esc_attr($logo['alt']); ?>"
              class="<?= esc_attr($logo['css_class']); ?>"
              loading="eager">
      </div>
      <?php
                }
            }
        }
        ?>
   </div>
</div>

<?php endif; ?>