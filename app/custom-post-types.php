<?php

namespace App;

/**
 * Custom post types
 */

add_action('init', function() {
    // Carnage Features
    register_post_type( 'carnage_feature',
        array(
            'labels' => array(
                'name' => __('Carnage Features'),
                'singular_name' => __('Carnage Feature'),
                'add_new' => __('Add New' ),
                'add_new_item' => __('Add New Carnage Feature'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Carnage Feature'),
                'new_item' => __('New Carnage Feature'),
                'view' => __('View Carnage Feature'),
                'view_item' => __('View Carnage Feature'),
                'search_items' => __('Search Carnage Features'),
                'all_items' => __('All Carnage Features'),
                'not_found' => __('No Carnage Features Found.'),
                'not_found_in_trash' => __('No Carnage Features found in Trash')
            ),
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_feature',
            'graphql_plural_name' => 'carnage_features',
            'has_archive' => false,
            'public' => true,
            'supports' => array(
                'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author'
            ),
            'menu_icon' => 'dashicons-format-aside',
            'taxonomies'  => array('carnage_feature_cat', 'carnage_feature_tag'),
            'menu_position' => 4,
            'rewrite' => array('slug' => 'daily-carnage-feature')
        )
    );

    // Carnage Issues
    register_post_type( 'carnage_issue',
        array(
            'labels' => array(
                'name' => __('Carnage Issues'),
                'singular_name' => __('Carnage Issue'),
                'add_new' => __('Add New' ),
                'add_new_item' => __('Add New Carnage Issue'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Carnage Issue'),
                'new_item' => __('New Carnage Issue'),
                'view' => __('View Carnage Issue'),
                'view_item' => __('View Carnage Issue'),
                'search_items' => __('Search Carnage Issues'),
                'all_items' => __('All Carnage Issues'),
                'not_found' => __('No Carnage Issues Found.'),
                'not_found_in_trash' => __('No Carnage Issues found in Trash')
            ),
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_issue',
            'graphql_plural_name' => 'carnage_issues',
            'has_archive' => 'daily-carnage',
            'public' => true,
            'supports' => array(
                'title', 'thumbnail', 'excerpt', 'revisions', 'author'
            ),
            'menu_icon' => 'dashicons-email',
            'taxonomies'  => array('carnage_issue_cat', 'carnage_sponsor'),
            'menu_position' => 4,
            'rewrite' => array('slug' => 'daily-carnage')
        )
    );

    // Clients
    register_post_type( 'client',
        array(
            'labels' => array(
                'name' => __('Clients'),
                'singular_name' => __('Client'),
                'add_new' => __('Add New' ),
                'add_new_item' => __('Add New Client'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Client'),
                'new_item' => __('New Client'),
                'view' => __('View Client'),
                'view_item' => __('View'),
                'search_items' => __('Search Clients'),
                'all_items' => __('All Clients'),
                'not_found' => __('No clients found.'),
                'not_found_in_trash' => __('No clients found in Trash')
            ),
            'has_archive' => 'work',
            'public' => true,
            'supports' => array(
                'title', 'editor', 'thumbnail', 'page-attributes', 'excerpt'
            ),
            'menu_icon' => 'dashicons-awards',
            'menu_position' => 4,
            'taxonomies'  => array('capabilities'),
            'rewrite' => array('slug' => 'work')
        )
    );

    // Polls
    register_post_type( 'poll',
        array(
            'labels' => array(
                'name' => __('Polls'),
                'singular_name' => __('Poll'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New Poll'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Poll'),
                'view' => __('View'),
                'view_item' => __('View Poll'),
                'search_items' => __('Search Polls'),
                'all_items' => __('All Polls'),
                'not_found' => __('No polls found.'),
                'not_found_in_trash' => __('No polls found in Trash.'),
            ),
            'supports' => array(
                'title', 'excerpt'
            ),
            'public' => true,
            'query_var' => false,
            'has_archive' => false,
            'menu_icon' => 'dashicons-chart-area'
        )
    );

    // Testimonials
    register_post_type( 'testimonial',
        array(
            'labels' => array(
                'name' => __('Testimonials'),
                'singular_name' => __('Testimonial'),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New Testimonial'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Testimonial'),
                'view' => __('View'),
                'view_item' => __('View Testimonial'),
                'search_items' => __('Search Testimonials'),
                'all_items' => __('All Testimonials'),
                'not_found' => __('No testimonials found.'),
                'not_found_in_trash' => __('No testimonials found in Trash.'),
            ),
            'supports' => array(
                'title', 'editor', 'excerpt', 'page-attributes', 'thumbnail'
            ),
            'rewrite' => array(
                'with_front' => 'false', 'slug' => 'testimonials'
            ),
            'public' => true,
            'menu_icon' => 'dashicons-heart'
        )
    );

    // Job openings
    register_post_type( 'job_opening',
        array(
            'labels' => array(
                'name' => __('Job Openings'),
                'singular_name' => __('Job Opening'),
                'add_new' => __('Add New' ),
                'add_new_item' => __('Add New Job Opening'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Job Opening'),
                'new_item' => __('New Job Opening'),
                'view' => __('View Job Opening'),
                'view_item' => __('View'),
                'search_items' => __('Search Job Openings'),
                'all_items' => __('All Job Openings'),
                'not_found' => __('No job openings found.'),
                'not_found_in_trash' => __('No job openings found in Trash')
            ),
            'has_archive' => false,
            'public' => true,
            'exclude_from_search' => true,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'supports' => array(
                'title', 'editor', 'excerpt'
            ),
            'menu_icon' => 'dashicons-id-alt',
            'menu_position' => 4,
            'taxonomies'  => array('capabilities', 'job_type', 'job_experience', 'company'),
        )
    );

    // Careers
    register_post_type( 'career',
        array(
            'labels' => array(
                'name' => __('Careers'),
                'singular_name' => __('Career'),
                'add_new' => __('Add New' ),
                'add_new_item' => __('Add New Career'),
                'edit' => __('Edit'),
                'edit_item' => __('Edit Career'),
                'new_item' => __('New Career'),
                'view' => __('View Career'),
                'view_item' => __('View'),
                'search_items' => __('Search Careers'),
                'all_items' => __('All Careers'),
                'not_found' => __('No careers found.'),
                'not_found_in_trash' => __('No careers found in Trash')
            ),
            'has_archive' => 'careers',
            'public' => true,
            'exclude_from_search' => true,
            'show_in_rest' => true,
            'show_in_graphql' => true,
            'rewrite' => array(
                'slug' => 'careers',
                'with_front' => false
            ),
            'supports' => array(
                'title', 'editor', 'excerpt'
            ),
            'menu_icon' => 'dashicons-businessman',
            'taxonomies'  => array('department'),
        )
    );
});
