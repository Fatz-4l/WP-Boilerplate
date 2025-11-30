<?php
/**
 * Jetpack CDN URL Builder
 * This function builds a Jetpack CDN URL for a remote image with WebP conversion
 */

function jp_cdn_url($url, $w, $q = 82, $format = '') {
$parts = parse_url($url);
if (empty($parts['host'])) return $url;

// Build Host Path
$host_path = ltrim(($parts['host'] ?? '') . ($parts['path'] ?? ''), '/');
if (!empty($parts['query'])) $host_path .= '?' . $parts['query'];

// Build Format Parameter
$format_param = $format ? "&format={$format}" : '';
return "https://i0.wp.com/{$host_path}?w={$w}&quality={$q}&ssl=1{$format_param}";
}

// Build srcset for images
function jp_srcset($url, $widths = [480, 768, 1024, 1366, 1600, 1920], $q = 82, $format = '') {
$items = [];
foreach ($widths as $w) {
$items[] = jp_cdn_url($url, $w, $q, $format) . " {$w}w";
}
return implode(', ', $items);
}