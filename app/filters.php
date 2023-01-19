<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter('body_class', function (array $classes) {
    /** Add page slug if it doesn't exist */
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    /** Add class if sidebar is active */
    if (display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }

    /** Clean up class names for custom templates */
    $classes = array_map(function ($class) {
        return preg_replace(['/-blade(-php)?$/', '/^page-template-views/'], '', $class);
    }, $classes);

    return array_filter($classes);
});

/**
 * Add "… Continued" to the excerpt
 */
add_filter('excerpt_more', function () {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'sage') . '</a>';
});

/**
 * Template Hierarchy should search for .blade.php files
 */
collect([
    'index', '404', 'archive', 'author', 'category', 'tag', 'taxonomy', 'date', 'home',
    'frontpage', 'page', 'paged', 'search', 'single', 'singular', 'attachment'
])->map(function ($type) {
    add_filter("{$type}_template_hierarchy", __NAMESPACE__.'\\filter_templates');
});

/**
 * Render page using Blade
 */
add_filter('template_include', function ($template) {
    $data = collect(get_body_class())->reduce(function ($data, $class) use ($template) {
        return apply_filters("sage/template/{$class}/data", $data, $template);
    }, []);
    if ($template) {
        echo template($template, $data);
        return get_stylesheet_directory().'/index.php';
    }
    return $template;
}, PHP_INT_MAX);

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter('comments_template', function ($comments_template) {
    $comments_template = str_replace(
        [get_stylesheet_directory(), get_template_directory()],
        '',
        $comments_template
    );
    return template_path(locate_template(["views/{$comments_template}", $comments_template]) ?: $comments_template);
});

/**
 * Customize Yoast SEO Open Graph meta values for Custom Post Types
 */
add_filter('wpseo_opengraph_title', __NAMESPACE__ . '\\wpseso_cpt_title');
add_filter('wpseo_twitter_title', __NAMESPACE__ . '\\wpseso_cpt_title');
function wpseso_cpt_title($title) {
    if (is_post_type_archive()) {
        $seo_title = get_field(get_post_type() . '_seo_title', 'options');
        if (!empty ($seo_title)) {
            $title = $seo_title;
        }
    }
    return $title;
}

add_filter('wpseo_opengraph_desc', __NAMESPACE__ . '\\wpseo_cpt_description');
add_filter('wpseo_twitter_description', __NAMESPACE__ . '\\wpseo_cpt_description');
function wpseo_cpt_description($desc) {
    if (is_post_type_archive()) {
        $seo_desc = get_field(get_post_type() . '_seo_description', 'options');
        if (!empty ($seo_desc)) {
            $desc = $seo_desc;
        }
    }
    return $desc;
}

add_filter('wpseo_opengraph_image', __NAMESPACE__ . '\\wpseo_cpt_image');
add_filter('wpseo_twitter_image', __NAMESPACE__ . '\\wpseo_cpt_image');
function wpseo_cpt_image($image) {
    $wpseo_social = get_option('wpseo_social');

    if (is_post_type_archive()) {
        $post_type = get_post_type();
        $seo_image = get_field($post_type . '_seo_image', 'options');
        if (!empty($seo_image)) {
            $image = $seo_image['sizes']['large'];
        } else {
            $most_recent_post = get_posts(array(
                'post_type' => $post_type,
                'posts_per_page' => 2
            ));
            $featured_image = get_the_post_thumbnail_url($most_recent_post[0]->ID, 'large');
            if (empty($featured_image)) {
                $featured_image = get_the_post_thumbnail_url($most_recent_post[1]->ID, 'large');
            }
            if (!empty($featured_image)) {
                $image = $featured_image;
            }
        }
    }

    return $image;
}

// Add custom subject attribute for CF7 shortcode
add_filter('shortcode_atts_wpcf7', function ($out, $pairs, $atts) {
    $att = 'subject';
    if (isset($atts[$att])) {
        $out[$att] = $atts[$att];
    }
    return $out;
}, 10, 3);
