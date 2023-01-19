<?php

namespace App;

use Sober\Controller\Controller;

class ArchiveCareer extends Controller
{

    public function careers_form()
    {
        $careers_form_id = get_field('careers_form', 'options');
        if (!empty($careers_form_id)) :
            global $wp;
            $url = home_url($wp->request);
            $atts = "post_title='General' post_url='$url'";
            return do_shortcode("[contact-form-7 id='$careers_form_id' $atts]");
        endif;
        return '';
    }
}
