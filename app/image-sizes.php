<?php
/**
 * Custom image sizes
 */

namespace App;

/**
 * Set max srcset image width to 3000px
 */
function remove_max_srcset_image_width($max_width) {
    return 3000;
}
add_filter('max_srcset_image_width', __NAMESPACE__ . '\\remove_max_srcset_image_width');

/**
 * Adds extra image sizes for responsive images
 */
function image_size_chooser($sizes) {
    $add_sizes = [
        'small_wide' => __('Medium Wide'),
        'large_wide' => __('Large Wide'),
        'large_square' => __('Large Square'),
        'small_square' => __('Small Square')
    ];
    $new_sizes = array_merge($sizes, $add_sizes);
    return $new_sizes;
}
add_filter('image_size_names_choose', __NAMESPACE__ . '\\image_size_chooser');
add_image_size('small_logo', 160, 120);
add_image_size('medium_logo', 240, 180);
add_image_size('large_wide', 1600, 800, true);
add_image_size('small_wide', 800, 400);
add_image_size('large_square', 600, 600, true);
add_image_size('small_square', 300, 300, true);
add_image_size('carnage', 600);
