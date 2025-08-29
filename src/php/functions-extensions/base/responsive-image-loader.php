<?php

function get_responsive_image($image_id, $loading = 'lazy', $class_names = '') {
	if (!$image_id || !is_numeric($image_id)) return '';

	$srcset = wp_get_attachment_image_srcset($image_id, 'full');
	$alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);

	// Full size details for width/height attributes (preserve layout for IO on mobile)
	$full_src = wp_get_attachment_image_src($image_id, 'full');
	$full_url = $full_src ? $full_src[0] : '';
	$width = $full_src ? intval($full_src[1]) : 0;
	$height = $full_src ? intval($full_src[2]) : 0;

	$base_classes = is_string($class_names) ? trim($class_names) : '';
	$classes = ($loading === 'eager') ? $base_classes : trim('blur-load ' . $base_classes);

	if ($loading === 'eager') {
		return sprintf(
			'<img src="%s" srcset="%s" alt="%s" width="%d" height="%d" decoding="async" class="%s">',
			esc_url($full_url),
			esc_attr($srcset),
			esc_attr($alt),
			$width,
			$height,
			esc_attr($classes)
		);
	}

	// Low quality placeholder for blur-up effect
	$low_quality_url = wp_get_attachment_image_url($image_id, 'medium');

	// Lazy loading: provide placeholder src and data attributes for loader
	return sprintf(
		'<img src="%s"
		     data-srcset="%s"
		     data-src="%s"
		     alt="%s"
		     loading="lazy"
		     width="%d" height="%d" decoding="async"
		     class="%s">',
		esc_url($low_quality_url),
		esc_attr($srcset),
		esc_url($full_url),
		esc_attr($alt),
		$width,
		$height,
		esc_attr($classes)
	);
}

function responsive_image($image_id, $loading = 'lazy', $class_names = '') {
	echo get_responsive_image($image_id, $loading, $class_names);
}
