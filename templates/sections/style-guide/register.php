<?php

/**
 * Simply registers the block.
 * Loaded via functions.php.
 */

add_action('init', function () {
    register_block_type(__DIR__ . '/block.json');
});
