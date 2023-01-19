<?php

namespace App;

function register_acf_block_types() {
    /**
     * Column Section
     */
    acf_register_block_type(array(
        'name'              => 'column-section',
        'title'             => __('Column Section'),
        'description'       => __('Two column section with child content and custom image.'),
        'render_template'   => 'views/blocks/column-section.php',
        'align'             => 'full',
        'supports'          => array('align' => ['full']),
        'category'          => 'layout',
        'icon'              => 'layout',
        'keywords'          => array('section', 'column', 'columns')
    ));

    /**
     * Preview Section
     */
    acf_register_block_type(array(
        'name'              => 'preview-section',
        'title'             => __('Preview Section'),
        'description'       => __('Two column section with child content and custom image.'),
        'render_template'   => 'views/blocks/preview-section.php',
        'align'             => 'full',
        'supports'          => array('align' => ['full']),
        'category'          => 'layout',
        'icon'              => 'layout',
        'keywords'          => array('section', 'preview', 'image')
    ));

    /**
     * Job Openings
     */
    acf_register_block_type(array(
        'name'              => 'job-openings',
        'title'             => __('Job Openings'),
        'description'       => __('Paginated table listing active Job Openings.'),
        'render_template'   => 'views/blocks/job-openings.php',
        'enqueue_script'    => asset_path('scripts/job-openings.js'),
        'supports'          => array('align' => false),
        'category'          => 'widgets',
        'icon'              => 'id-alt',
        'keywords'          => array('job', 'employment', 'connector')
    ));
}

if (function_exists('acf_register_block_type')) {
    add_action('acf/init', __NAMESPACE__ . '\\register_acf_block_types');
}
