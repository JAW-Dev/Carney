<?php

namespace App;

/**
 * Custom taxonomies
 *
 * @package carney
 */

add_action('init', function() {
    // Capabilities
    register_taxonomy( "capability", array( "client" ),
        array(
            "label" => __( "Capabilities", "carney" ),
            "labels" => array(
                "name" => __( "Capabilities", "carney" ),
                "singular_name" => __( "Capability", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Capabilities",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'capabilities', 'with_front' => false, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'capability',
            'graphql_plural_name' => 'capabilities',
            "rest_base" => "",
            "show_in_quick_edit" => true,
        )
    );

    // Daily Carnage taxonomies

    // Issue Category
    register_taxonomy( "carnage_issue_cat", array( "carnage_issue" ),
        array(
            "label" => __( "Categories", "carney" ),
            "labels" => array(
                "name" => __( "Categories", "carney" ),
                "singular_name" => __( "Category", "carney" ),
            ),
            "public" => true,
            "hierarchical" => true,
            "label" => "Categories",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'carnage-issue-category', 'with_front' => false, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_issue_cat',
            'graphql_plural_name' => 'carnage_issue_cats',
            "rest_base" => "",
            "show_in_quick_edit" => true,
        )
    );

    // Carnage Sponsor
    register_taxonomy( "carnage_sponsor", array( "carnage_issue" ),
        array(
            "label" => __( "Sponsors", "carney" ),
            "labels" => array(
                "name" => __( "Sponsors", "carney" ),
                "singular_name" => __( "Sponsor", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Sponsors",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => false,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'carnage-sponsor', 'with_front' => false, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_sponsor',
            'graphql_plural_name' => 'carnage_sponsors',
            "rest_base" => "",
            "show_in_quick_edit" => true,
        )
    );

    // Feature Category
    register_taxonomy( "carnage_feature_cat", array( "carnage_feature" ),
        array(
            "label" => __( "Categories", "carney" ),
            "labels" => array(
                "name" => __( "Categories", "carney" ),
                "singular_name" => __( "Category", "carney" ),
            ),
            "public" => true,
            "hierarchical" => true,
            "label" => "Categories",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'carnage-category', 'with_front' => false, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_feature_cat',
            'graphql_plural_name' => 'carnage_feature_cats',
            "rest_base" => "",
            "show_in_quick_edit" => true,
        )
    );

    // Feature Tag
    register_taxonomy( "carnage_feature_tag", array( "carnage_feature" ),
        array(
            "label" => __( "Tags", "carney" ),
            "labels" => array(
                "name" => __( "Tags", "carney" ),
                "singular_name" => __( "Tag", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Tags",
            "show_ui" => true,
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "query_var" => true,
            "rewrite" => array( 'slug' => 'carnage-tag', 'with_front' => false, ),
            "show_admin_column" => true,
            "show_in_rest" => true,
            'show_in_graphql' => true,
            'graphql_single_name' => 'carnage_feature_tag',
            'graphql_plural_name' => 'carnage_feature_tags',
            "rest_base" => "",
            "show_in_quick_edit" => true,
        )
    );

    // Poll Choice
    register_taxonomy( "poll_choice", array("poll"),
        array(
            "label" => __("Poll Choices", "carney"),
            "labels" => array(
                "name" => __( "Poll Choices", "carney" ),
                "singular_name" => __( "Poll Choice", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Poll Choices",
            "show_ui" => true,
            "query_var" => true
        )
    );

    // Job Type
    register_taxonomy( "job_type", array("job_opening", "career"),
        array(
            "label" => __("Job Types", "carney"),
            "labels" => array(
                "name" => __( "Job Types", "carney" ),
                "singular_name" => __( "Job Type", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Job Types",
            "show_ui" => true,
            "query_var" => true,
            "show_in_rest" => true
        )
    );

    // Job Experience Level
    register_taxonomy( "job_experience", array("job_opening", "career"),
        array(
            "label" => __("Experience Levels", "carney"),
            "labels" => array(
                "name" => __( "Experience Levels", "carney" ),
                "singular_name" => __( "Experience Level", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Experience Levels",
            "show_ui" => true,
            "query_var" => true,
            "show_in_rest" => true
        )
    );

    // Company
    register_taxonomy( "company", array("job_opening"),
        array(
            "label" => __("Companies", "carney"),
            "labels" => array(
                "name" => __( "Companies", "carney" ),
                "singular_name" => __( "Company", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Companies",
            "show_ui" => true,
            "query_var" => true,
            "show_in_rest" => true
        )
    );

    // Career department
    register_taxonomy( "department", array("career"),
        array(
            "label" => __("Departments", "carney"),
            "labels" => array(
                "name" => __( "Departments", "carney" ),
                "singular_name" => __( "Department", "carney" ),
            ),
            "public" => true,
            "hierarchical" => false,
            "label" => "Departments",
            "show_ui" => true,
            "query_var" => true,
            "show_in_rest" => true
        )
    );
});
