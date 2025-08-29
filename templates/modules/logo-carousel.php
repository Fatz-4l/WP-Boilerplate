<?php

// Logo carousel configuration from WordPress options
$logo_config = [];
$carousel_css = '';

// Get logos from new dynamic structure first, fallback to old structure
$logos_data = get_option('logo_carousel_logos', []);

if (!empty($logos_data)) {
    // Use new dynamic structure
    foreach ($logos_data as $index => $logo) {
        if (!empty($logo['image'])) {
            $logo_config[] = [
                'url' => $logo['image'],
                'alt' => 'Logo ' . ($index + 1),
                'height_mobile' => $logo['height_mobile'] . 'rem',
                'height_desktop' => $logo['height_desktop'] . 'rem',
                'width' => $logo['width'],
                'css_class' => 'logo-carousel-img-' . $index
            ];

            // Generate CSS for responsive heights
            $carousel_css .= "
                .logo-carousel-img-{$index} {
                    height: {$logo['height_mobile']}rem;
                    width: {$logo['width']};
                }
                @media (min-width: 1024px) {
                    .logo-carousel-img-{$index} {
                        height: {$logo['height_desktop']}rem;
                    }
                }
            ";
        }
    }
} else {
    // Fallback to old hardcoded structure for backward compatibility
    for ($i = 1; $i <= 5; $i++) {
        $image_url = get_option("logo_carousel_image_{$i}");
        $height_mobile = get_option("logo_carousel_height_mobile_{$i}", 1.5);
        $height_desktop = get_option("logo_carousel_height_desktop_{$i}", 2);
        $width = get_option("logo_carousel_width_{$i}", 'auto');

        if (!empty($image_url)) {
            $logo_config[] = [
                'url' => $image_url,
                'alt' => 'Logo ' . $i,
                'height_mobile' => $height_mobile . 'rem',
                'height_desktop' => $height_desktop . 'rem',
                'width' => $width,
                'css_class' => 'logo-carousel-img-' . $i
            ];

            // Generate CSS for responsive heights
            $carousel_css .= "
                .logo-carousel-img-{$i} {
                    height: {$height_mobile}rem;
                    width: {$width};
                }
                @media (min-width: 1024px) {
                    .logo-carousel-img-{$i} {
                        height: {$height_desktop}rem;
                    }
                }
            ";
        }
    }
}

?>

<?php if (!empty($logo_config)): ?>
<style>
<?=$carousel_css;
?>

/* Logo Carousel - Minimal CSS */
.logo-carousel-track {
    animation: infiniteScroll 100s linear infinite;
    will-change: transform;
}

@keyframes infiniteScroll {
    to {
        transform: translateX(-50%);
    }
}
</style>

<div class="logo-carousel-track opacity-50 flex items-center overflow-hidden">
    <?php for ($set = 0; $set < 2; $set++): // Two sets for seamless loop ?>
    <?php for ($i = 0; $i < 14; $i++): ?>
    <?php
        $logo = $logo_config[$i % count($logo_config)];
        $image_url = $logo['url'];
        $alt_text = $logo['alt'];
        $css_class = $logo['css_class'];
        ?>
    <div class="flex-shrink-0 mr-[150px] lg:mr-[150px] md:mr-[100px] sm:mr-[100px]">
        <img src="<?= esc_url($image_url); ?>"
             alt="<?= esc_attr($alt_text); ?>"
             class="<?= $css_class; ?>"
             loading="eager">
    </div>
    <?php endfor; ?>
    <?php endfor; ?>
</div>
<?php endif; ?>