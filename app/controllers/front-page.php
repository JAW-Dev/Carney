<?php

namespace App;

use Sober\Controller\Controller;

class FrontPage extends Controller
{
    public function related_posts() {
        return get_posts([
            'post_type' => 'post',
            'posts_per_page' => 5
        ]);
    }

    public function related_posts_heading() {
        return '';
    }

    public function related_posts_section_heading() {
        return 'The Blog';
    }

    public function related_posts_section_classes() {
        return 'wp-block-carney-section--horizontal-header pt-md-sm scrollable';
    }

    public function related_posts_more_link() {
        return [
            'url' => get_permalink(get_option('page_for_posts')),
            'title' => 'See More →'
        ];
    }
}
