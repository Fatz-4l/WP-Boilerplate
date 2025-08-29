<?php
/**
 * Plugin Name: Enable Secure SVG Uploads
 * Description: Admin-only SVG uploads with sanitization, better Media Library previews, and normalized attachment data.
 * Version: 1.0.0
 */

if ( ! defined('ABSPATH') ) { exit; }

/**
 * Admin-only SVG MIME.
 */
add_filter('upload_mimes', function ($mimes) {
    if ( current_user_can('manage_options') ) {
        $mimes['svg'] = 'image/svg+xml';
    } else {
        unset($mimes['svg']);
    }
    return $mimes;
});

/**
 * Trust filetype/ext for SVG.
 */
add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
    $filetype = wp_check_filetype($filename, $mimes);
    if ( isset($filetype['ext']) && $filetype['ext'] === 'svg' ) {
        $data['ext']  = 'svg';
        $data['type'] = 'image/svg+xml';
        $data['proper_filename'] = $data['proper_filename'] ?? $filename;
    }
    return $data;
}, 10, 4);

/**
 * Sanitize SVGs (balanced): keep <style> and style= but scrub dangerous content.
 */
add_filter('wp_handle_upload_prefilter', function ($file) {
    $name = isset($file['name']) ? strtolower($file['name']) : '';
    $tmp  = isset($file['tmp_name']) ? $file['tmp_name'] : '';

    if ( $tmp && $name && substr($name, -4) === '.svg' ) {

        if ( ! current_user_can('manage_options') ) {
            $file['error'] = __('For security, only admins may upload SVG files.', 'secure-svgs');
            return $file;
        }

        // Optional size cap
        $max_bytes = 2 * 1024 * 1024; // 2MB
        if ( @filesize($tmp) > $max_bytes ) {
            $file['error'] = sprintf(__('SVG exceeds %d MB limit.', 'secure-svgs'), $max_bytes / 1024 / 1024);
            return $file;
        }

        $svg = @file_get_contents($tmp);
        if ($svg === false || trim($svg) === '') {
            $file['error'] = __('Could not read uploaded SVG.', 'secure-svgs');
            return $file;
        }

        $sanitized = ssvg_sanitize($svg);
        if ($sanitized === null) {
            $file['error'] = __('Invalid or unsafe SVG (failed sanitization).', 'secure-svgs');
            return $file;
        }
        if ( @file_put_contents($tmp, $sanitized) === false ) {
            $file['error'] = __('Failed to write sanitized SVG.', 'secure-svgs');
            return $file;
        }
    }
    return $file;
});

/**
 * ==== Sanitizer (Balanced) ====
 * - Removes script/foreignObject/iframe/object/embed/canvas/audio/video, etc.
 * - Keeps gradients/defs/masks/clipPath, etc.
 * - Keeps <image> ONLY if its href is fragment (#id) or a safe data:image/*
 * - Keeps inline style and <style>, but scrubs dangerous constructs.
 */
function ssvg_sanitize(string $svg): ?string {
    // Quick reject for obvious HTML or script containers
    if ( preg_match('/<(?:html|script|iframe|object|embed)\b/i', $svg) ) {
        return null;
    }
    if ( stripos($svg, '<svg') === false ) { return null; }

    $prev = libxml_use_internal_errors(true);
    $dom  = new DOMDocument();
    // Load XML (no network). We include NOENT/DTD* but will reject any DTD immediately after.
    $loaded = $dom->loadXML($svg, LIBXML_NONET | LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR);
    if ( ! $loaded ) {
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        return null;
    }
    // Reject if DTD/ENTITY is present (avoid XXE)
    if ( $dom->doctype ) {
        libxml_use_internal_errors($prev);
        return null;
    }

    $xp = new DOMXPath($dom);

    // Remove dangerous elements
    $kill = [
        'script','foreignObject','iframe','embed','object','audio','video','canvas',
        'set','animate','animateTransform','animateMotion','mpath','feImage'
    ];
    foreach ($kill as $tag) {
        foreach ($xp->query('//' . $tag) as $n) {
            $n->parentNode->removeChild($n);
        }
    }

    // Keep <image>, but we’ll validate its href later. (Do NOT remove.)

    // Strip event handler attributes and javascript/data URLs
    $all = $dom->getElementsByTagName('*');
    foreach ($all as $el) {
        if (!($el instanceof DOMElement)) { continue; }

        // Gather attribute names (we’ll edit while iterating)
        $atts = [];
        foreach ($el->attributes ?? [] as $a) {
            $atts[] = $a->nodeName;
        }

        foreach ($atts as $name) {
            $lname = strtolower($name);

            // Remove on* event handlers
            if (strpos($lname, 'on') === 0) {
                $el->removeAttribute($name);
                continue;
            }

            // href/xlink:href: allow only fragments or safe data:image/*
            if ($lname === 'href' || $lname === 'xlink:href') {
                $v = $el->getAttribute($name);
                if (!ssvg_href_is_allowed($v)) {
                    $el->removeAttribute($name);
                }
                continue;
            }

            // style attribute: scrub content, but keep if it becomes safe (possibly ends empty)
            if ($lname === 'style') {
                $val = $el->getAttribute($name);
                $clean = ssvg_scrub_css($val);
                if ($clean === '') {
                    // keep empty style off for tidiness
                    $el->removeAttribute($name);
                } else {
                    $el->setAttribute($name, $clean);
                }
                continue;
            }
        }
    }

    // <style> blocks: scrub CSS
    foreach ($xp->query('//style') as $styleNode) {
        $css = $styleNode->textContent ?? '';
        $clean = ssvg_scrub_css($css);
        // Replace content safely
        while ($styleNode->firstChild) { $styleNode->removeChild($styleNode->firstChild); }
        $styleNode->appendChild($dom->createTextNode($clean));
    }

    // Ensure root svg exists and namespace is correct
    $svgs = $dom->getElementsByTagName('svg');
    if ($svgs->length === 0) { libxml_use_internal_errors($prev); return null; }
    $root = $svgs->item(0);
    $root->setAttribute('xmlns', 'http://www.w3.org/2000/svg');

    // Sanitize suspicious width/height values (only numbers/percent)
    foreach (['width','height'] as $dim) {
        $v = $root->getAttribute($dim);
        if ($v && preg_match('/[^\d.\s%]/', $v)) {
            $root->removeAttribute($dim);
        }
    }

    $dom->formatOutput = false;
    $out = $dom->saveXML();
    libxml_use_internal_errors($prev);

    // Final guard
    if ($out === false || stripos($out, '<script') !== false) { return null; }

    return $out;
}

/**
 * Allow fragments (#id) or data:image/(png|jpeg|jpg|gif|webp|svg+xml).
 * Block everything else (http/https/javascript/file/blob/etc).
 */
function ssvg_href_is_allowed(string $href): bool {
    $u = trim($href);
    if ($u === '' || $u[0] === '#') { return true; }
    $lower = strtolower($u);
    if (strpos($lower, 'data:image/') === 0) {
        // whitelist common image mediatypes
        return (bool) preg_match('#^data:image/(?:png|jpe?g|gif|webp|svg\+xml);#i', $lower);
    }
    // block any protocol
    if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $u)) { return false; }
    // allow relative (no protocol, not starting with //)
    if (strpos($u, '//') === 0) { return false; }
    return true;
}

/**
 * Scrub CSS: remove url(javascript:...), @import, expression(), -moz-binding, behavior, and protocols.
 * Keep normal fill/stroke/etc.
 */
function ssvg_scrub_css(string $css): string {
    // normalize whitespace
    $c = preg_replace('/\s+/', ' ', $css ?? '');
    if ($c === null) { $c = ''; }

    // remove @import rules entirely
    $c = preg_replace('/@import[^;]*;?/i', '', $c);

    // remove url(javascript:...), url(data:application…), etc.
    $c = preg_replace('/url\s*\(\s*[\'"]?\s*javascript:[^)]+?\)/i', '', $c);
    $c = preg_replace('/url\s*\(\s*[\'"]?\s*data:(?!image\/)[^)]+?\)/i', '', $c);

    // remove expression(), behavior, -moz-binding
    $c = preg_replace('/expression\s*\(/i', '', $c);
    $c = preg_replace('/behavior\s*:/i', '', $c);
    $c = preg_replace('/-moz-binding\s*:/i', '', $c);

    // strip protocols in property values (http:, https:, file:, blob:, vbscript:)
    $c = preg_replace('/(?:^|[\s:(])(?:https?|file|blob|vbscript):/i', '$1', $c);

    // trim leftover semicolons/spaces
    $c = trim(preg_replace('/\s*;\s*;+/',';', $c) ?? '');

    return $c;
}

/**
 * Admin CSS: make SVG thumbnails behave in grid/list/modal.
 */
add_action('admin_head', function () {
    echo '<style>
        /* Media grid & list */
        .attachment .thumbnail img[src$=".svg"],
        .media-icon img[src$=".svg"],
        .attachment-preview .thumbnail img[src$=".svg"] {
            width: 100% !important;
            height: auto !important;
            display: block;
        }
        .attachment-preview .thumbnail {
            background: #fff;
        }
        /* Modal details pane */
        .media-modal .attachment-details .thumbnail img[src$=".svg"] {
            max-width: 100% !important;
            height: auto !important;
            display: block;
        }
    </style>';
});

/**
 * Provide sensible width/height for SVGs in the UI from the file's viewBox.
 * Helps prevent tiny/odd previews.
 */
add_filter('wp_prepare_attachment_for_js', function ($response, $attachment, $meta) {
    if ( isset($response['mime']) && $response['mime'] === 'image/svg+xml' ) {
        $response['sizes'] = [];
        // Try to infer dimensions from the file (viewBox)
        $path = get_attached_file($attachment->ID);
        [$w, $h] = ssvg_infer_dimensions_from_svg($path);
        if ($w && $h) {
            $response['width']  = $w;
            $response['height'] = $h;
        } else {
            // Reasonable fallback keeps UI stable
            $response['width']  = $response['width']  ?? 512;
            $response['height'] = $response['height'] ?? 512;
        }
        $response['icon'] = $response['url'];
    }
    return $response;
}, 10, 3);

function ssvg_infer_dimensions_from_svg(?string $path): array {
    if ( ! $path || ! file_exists($path) ) { return [null, null]; }
    $xml = @file_get_contents($path);
    if ($xml === false) { return [null, null]; }

    // First try explicit width/height attributes (numbers or numbers+unit)
    if (preg_match('/<svg[^>]*\bwidth=["\']?([\d.]+)(?:[a-z%]*)["\'][^>]*\bheight=["\']?([\d.]+)(?:[a-z%]*)["\']/i', $xml, $m)) {
        return [ (int)round($m[1]), (int)round($m[2]) ];
    }
    // Fallback to viewBox="minx miny w h"
    if (preg_match('/viewBox=["\']?\s*[-\d.]+\s+[-\d.]+\s+([\d.]+)\s+([\d.]+)\s*["\']/i', $xml, $m)) {
        $w = max(1, (int)round($m[1]));
        $h = max(1, (int)round($m[2]));
        return [$w, $h];
    }
    return [null, null];
}