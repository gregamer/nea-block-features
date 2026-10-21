<?php // Silence is golden
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}

add_filter('big_image_size_threshold', function() {
    return 6000; // Set the desired maximum width in pixels
});