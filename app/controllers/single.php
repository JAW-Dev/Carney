<?php

namespace App;

use Sober\Controller\Controller;

class Single extends Controller
{
    public function related_posts() {
        global $post;
        $post_type = get_post_type();
        $is_carnage = in_array($post_type, ['carnage_issue', 'carnage_feature']);
        $categories = $is_carnage ? [] : get_the_category();
        $related_posts = get_posts([
            'post_type' => $post_type === 'carnage_feature' ? 'carnage_issue' : $post_type,
            'posts_per_page' => $post_type === 'client' ? 4 : 5,
            'exclude' => $post->ID,
            'cat' => array_map(function($cat) { return $cat->term_id; }, $categories),
            'orderby' => 'rand'
        ]);
        return $related_posts;
    }

    public function related_posts_heading() {
        return 'Related Posts';
    }

    public function related_posts_section_heading() {
        return '';
    }

    public function related_posts_section_classes() {
        return 'scrollable';
    }

    public function related_posts_more_link() {
        $post_type = get_post_type();
        $url = get_post_type_archive_link($post_type === 'carnage_feature' ? 'carnage_issue' : $post_type);

        return [
            'url' => $url,
            'title' => 'See More →'
        ];
    }

    public function job_form()
    {
        $job_form_id = get_field('job_form', 'options');
        if (!empty($job_form_id)) :
            $post_id = is_singular() ? get_the_ID() : null;
            if ($post_id) :
                $atts = "post_id='$post_id'";
            endif;
            return do_shortcode("[contact-form-7 id='$job_form_id' $atts]");
        endif;
        return '';
    }
}
