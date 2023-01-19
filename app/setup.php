<?php

namespace App;

use Roots\Sage\Container;
use Roots\Sage\Assets\JsonManifest;
use Roots\Sage\Template\Blade;
use Roots\Sage\Template\BladeProvider;
use \DrewM\MailChimp\MailChimp;

/**
 * Theme assets
 */
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('sage/main.css', asset_path('styles/main.css'), false, null);

    if (is_page('missfits')) :
        wp_enqueue_style('missfits-fonts', 'https://use.typekit.net/tgn8lnf.css', false, null);
    endif;

    if (is_page('carnage-referral-legacy')) :
        wp_enqueue_script('sage/carnage-referral.js', asset_path('scripts/carnage-referral.js'), null, null, true);
    else :
        wp_enqueue_script('sage/main.js', asset_path('scripts/main.js'), ['jquery'], null, true);
    endif;
}, 100);

/**
 * Admin assets
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('sage/admin.css', asset_path('styles/admin.css'), false, null);
    wp_enqueue_script('sage/admin.js', asset_path('scripts/admin.js'), ['jquery'], null, true);
}, 100);

/**
 * Theme setup
 */
add_action('after_setup_theme', function () {
    /**
     * Enable features from Soil when plugin is activated
     * @link https://roots.io/plugins/soil/
     */
    add_theme_support('soil-clean-up');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-relative-urls');

    /**
     * Enable plugins to manage the document title
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Register navigation menus
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'primary_navigation' => __('Primary Navigation', 'sage')
    ]);

    /**
     * Enable post thumbnails
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable HTML5 markup support
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    /**
     * Enable selective refresh for widgets in customizer
     * @link https://developer.wordpress.org/themes/advanced-topics/customizer-api/#theme-support-in-sidebars
     */
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Enable optional Gutenberg features
     * @link https://wordpress.org/gutenberg/handbook/designers-developers/developers/themes/theme-support/
     */
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('dark-editor-style');

    /**
     * Use main stylesheet for visual editor
     * @see resources/assets/styles/layouts/_tinymce.scss
     */
    add_editor_style(asset_path('styles/main.css'));
    add_editor_style(preg_replace('/^.*\/carney-2018\//', '../', asset_path('styles/main.css')));
}, 20);

/**
 * Register sidebars
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ];
    register_sidebar([
        'name'          => __('Primary', 'sage'),
        'id'            => 'sidebar-primary'
    ] + $config);
    register_sidebar([
        'name'          => __('Footer', 'sage'),
        'id'            => 'sidebar-footer'
    ] + $config);
});

/**
 * Updates the `$post` variable on each iteration of the loop.
 * Note: updated value is only available for subsequently loaded views, such as partials
 */
add_action('the_post', function ($post) {
    sage('blade')->share('post', $post);
});

/**
 * Setup Sage options
 */
add_action('after_setup_theme', function () {
    /**
     * Add JsonManifest to Sage container
     */
    sage()->singleton('sage.assets', function () {
        return new JsonManifest(config('assets.manifest'), config('assets.uri'));
    });

    /**
     * Add Blade to Sage container
     */
    sage()->singleton('sage.blade', function (Container $app) {
        $cachePath = config('view.compiled');
        if (!file_exists($cachePath)) {
            wp_mkdir_p($cachePath);
        }
        (new BladeProvider($app))->register();
        return new Blade($app['view']);
    });

    /**
     * Create @asset() Blade directive
     */
    sage('blade')->compiler()->directive('asset', function ($asset) {
        return "<?= " . __NAMESPACE__ . "\\asset_path({$asset}); ?>";
    });
});

/**
 * Set path for Blade SVG for Sage
 */
add_filter('bladesvg_image_path', function () {
    return \BladeSvgSage\get_dist_path('images/svg');
});

/**
 * Modify query for carnage_issue and carnage_feature_cat archives
 */
add_filter('pre_get_posts', function ($query) {
    if ($query->is_main_query()) :
        if ($query->is_tax('carnage_feature_cat') || $query->is_post_type_archive('carnage_issue')) :
            $query->set('posts_per_page', 9);
        endif;
    endif;
});

/**
 * Add Carnage RSS feed
 */
add_action('init', function () {
    add_feed('carnage', function () {
        get_template_part('views/partials/rss', 'carnage');
    });
});

add_filter('feed_content_type', function ($content_type, $type) {
    if ('carnage' === $type) {
        return feed_content_type('rss2');
    }
    return $content_type;
}, 10, 2);

/**
 * Reduce excerpt length
 */
add_filter('excerpt_length', function () {
    return 18;
}, 999);

/**
 * Remove link in excerpts
 */
add_filter('excerpt_more', function () {
    return ' …';
}, 999);

/**
 * Add CPT options page
 */
if (function_exists('acf_add_options_page')) :
    acf_add_options_page();
endif;

/**
 * Update referral info when a user is deleted
 */
add_action('delete_user', function ($user_id) {
    if (user_can($user_id, 'carnage_user')) :
        // Remove this user from referring user's referrals
        $user = get_user_by('ID', $user_id);
        $referral_id = get_field('referral_id', "user_$user_id");
        $referring_user = get_field('referred_by', "user_$user_id");
        if (!empty($referring_user)) :
            $referring_user_referral_id = get_field('referral_id', "user_{$referring_user->ID}", false) ?: '';
            $referring_user_referrals = get_field('referrals', "user_{$referring_user->ID}", false) ?: [];
            $referring_user_referrals = array_diff($referring_user_referrals, [$user_id]);
            update_field('referrals', $referring_user_referrals, "user_{$referring_user->ID}");
        endif;

        $list_id = MAILCHIMP_LIST_ID;

        // Make sure we're in production before changing anything in MC
        if (WP_ENV == 'production' && !empty($list_id)) :
            $MailChimp = new MailChimp(MAILCHIMP_API_KEY);

            // Update referring user's referral count in MailChimp
            $referring_hash = $MailChimp->subscriberHash($referring_user->user_email);
            $referring_user_referral_count = count($referring_user_referrals);
            $result = $MailChimp->patch("lists/$list_id/members/$referring_hash", [
                'merge_fields' => ['REF_COUNT' => $referring_user_referral_count]
            ]);

            // Add a note to referring user's MC record
            $result = $MailChimp->post("lists/$list_id/members/$referring_hash/notes", [
                'note' => "Automatically decremented referral count to $referring_user_referral_count when WordPress user with referral ID $referral_id was deleted."
            ]);

            // Unsubscribe this user from the Daily Carnage
            $user_hash = $MailChimp->subscriberHash($user->user_email);
            $result = $MailChimp->patch("lists/$list_id/members/$user_hash", [
                'merge_fields' => ['REF_BY' => ''],
                'status' => 'cleaned'
            ]);

            // Add a note to deleted user's MC record
            $result = $MailChimp->post("lists/$list_id/members/$user_hash/notes", [
                'note' => "Automatically cleaned when WordPress user was deleted. Originally referred by ID $referring_user_referral_id."
            ]);
        endif;
    endif;
});

/**
 * Add attributes to Contact Form 7 shortcode
 */
add_filter('shortcode_atts_wpcf7', function ($out, $pairs, $atts) {
    if (isset($atts['post_id'])) :
        $post_id = $atts['post_id'];
        $out['post_id'] = $post_id;
        $out['post_title'] = get_the_title($post_id);
        $out['post_url'] = get_permalink($post_id);
    endif;

    $attrs = ['source', 'response', 'post_title', 'post_url'];
    foreach ($attrs as $attr) :
        if (isset($atts[$attr])) :
            $out[$attr] = $atts[$attr];
        endif;
    endforeach;
    return $out;
}, 10, 3);

/**
 * Helper function to update subscriber tags in MailChimp
 */
function add_mc_subscriber_tag($list_id, $tag_id, $email)
{
    if (!empty($list_id) && !empty($tag_id) && !empty($email)) :
        $MailChimp = new MailChimp(MAILCHIMP_API_KEY);

        // Add tag
        return $MailChimp->post("lists/$list_id/segments/$tag_id/members", [
            'email_address' => $email
        ]);
    endif;
}

/**
 * Add "Submitted Hire Us" tag in MailChimp
 */
add_action('wpcf7_submit', function ($form, $result) {
    $list_id = MAILCHIMP_LIST_ID;
    $tag_id = 18291; // "Submitted Hire Us" tag ID
    $email = !empty($_POST['mc_email']) ? $_POST['mc_email'] : false;

    if (!empty($email) && in_array($form->id(), [11403, 46125])) :
        if (function_exists('as_schedule_single_action')) :
            $params = ['list_id' => $list_id, 'tag_id' => $tag_id, 'email' => $email];
            as_schedule_single_action(time(), 'handle_tag_update_action', ['params' => $params], 'mc_tag_update');
        endif;
    endif;
}, 10, 2);

/**
 * Add "Clicked Hire Us" tag in MailChimp
 */
add_filter('do_shortcode_tag', function ($output, $tag, $attr) {
    if (preg_match('/^contact-form(-7)?$/', $tag)) :
        $list_id = MAILCHIMP_LIST_ID;
        $tag_id = 18287; // "Clicked Hire Us" tag ID
        $email = !empty($_GET['email']) ? $_GET['email'] : false;
        if (!empty($email) && !empty($attr['source']) && 'The Daily Carnage' === $attr['source']) :
            if (function_exists('as_schedule_single_action')) :
                $params = ['list_id' => $list_id, 'tag_id' => $tag_id, 'email' => $email];
                as_schedule_single_action(time(), 'handle_tag_update_action', ['params' => $params], 'mc_tag_update');
            endif;
        endif;
    endif;
    return $output;
}, 10, 3);

add_action('handle_tag_update_action', function ($params) {
    return call_user_func_array(__NAMESPACE__ . '\\add_mc_subscriber_tag', $params);
});

/**
 * Helper to tack on URL params for referral pages
 */
function append_referral_params($url)
{
    $r = !empty($_GET['r']) ? urlencode($_GET['r']) : false;
    $u = !empty($_GET['u']) ? urlencode($_GET['u']) : false;
    return add_query_arg([
        'r' => $r,
        'u' => $u
    ], $url);
}

/**
 * Include URL params in canonical URL for referral pages
 */
add_filter('wpseo_opengraph_url', __NAMESPACE__ . '\\referral_url_params');
add_filter('wpseo_canonical',  __NAMESPACE__ . '\\referral_url_params');
function referral_url_params($url)
{
    if (is_page('carnage-referral') && !empty($url)) :
        return append_referral_params($url);
    else :
        return $url;
    endif;
};

/**
 * Templates and Page IDs that should use Gutenberg
 * Modified from: https://www.billerickson.net/disabling-gutenberg-certain-templates/
 */
function enable_editor($id = false)
{
    $included_templates = array(
        'views/template-gutenberg.blade.php'
    );

    $included_ids = array();

    if (empty($id)) :
        return true;
    endif;

    $id = intval($id);
    $template = get_page_template_slug($id);

    return in_array($id, $included_ids) || in_array($template, $included_templates);
}

/**
 * Enable Gutenberg by template
 */
function enable_gutenberg($can_edit, $post_type)
{
    if (!(is_admin() && !empty($_GET['post']))) :
        return $can_edit;
    endif;

    if (enable_editor($_GET['post'])) :
        $can_edit = true;
    endif;

    return $can_edit;
}
add_filter('gutenberg_can_edit_post_type', __NAMESPACE__ . '\\enable_gutenberg', 9999, 2);
add_filter('use_block_editor_for_post_type', __NAMESPACE__ . '\\enable_gutenberg', 9999, 2);

/**
 * Enable Classic Editor by template
 */
function enable_classic_editor()
{
    $screen = get_current_screen();

    if ('page' !== $screen->id || !isset($_GET['post'])) :
        return;
    endif;

    if (!enable_editor($_GET['post'])) :
        remove_post_type_support('page', 'editor');
    endif;
}
add_action('admin_head', __NAMESPACE__ . '\\enable_classic_editor');

/**
 * Create/update Poll Choice terms when a Poll is saved
 */
add_action('acf/save_post', function ($id) {
    $post_type = get_post_type($id);

    if ('poll' === $post_type) :
        // Get any poll_choice terms associated with this poll
        $terms = get_the_terms($id, 'poll_choice') ?: [];

        // Map poll_choice terms by ID
        $terms_by_id = [];
        foreach ($terms as $term) :
            $terms_by_id[$term->term_id] = $term;
        endforeach;

        // Get updated poll choices from ACF field data
        $term_ids = [];
        if (!empty($_POST['acf']['field_5e6b82a5e3cb0'])) :
            foreach ($_POST['acf']['field_5e6b82a5e3cb0'] as $row_key => $row) :
                $choice_key = 'field_5e6b82b3e3cb1';
                $term_key = 'field_5e6b8bc2a8c0d';

                // Get existing term or create a new one
                $term_id = $row[$term_key] ?: wp_insert_term("Poll $id - " . $row[$choice_key], 'poll_choice');
                if (is_array($term_id)) :
                    $term_id = $term_id['term_id'];
                endif;

                if (!empty($term_id) && !is_wp_error($term_id)) :
                    $term = !empty($terms_by_id) && !empty($terms_by_id[$term_id]) ?
                        $terms_by_id[$term_id] :
                        get_term($term_id, 'poll_choice');

                    if (!empty($term) && !is_wp_error($term)) :
                        $term_ids[] = $term->term_id;

                        // Update ACF term sub-field
                        $_POST['acf']['field_5e6b82a5e3cb0'][$row_key][$term_key] = $term->term_id;

                        // Update existing terms if necessary
                        if (!empty($terms_by_id[$term->term_id]) && $term->name !== ($name = "Poll $id - " . $row[$choice_key])) :
                            wp_update_term($term->term_id, 'poll_choice', [
                                'name' => $name,
                                'slug' => null
                            ]);
                        endif;
                    endif;
                endif;
            endforeach;
        endif;

        // Assign updated terms to this post
        if (!empty($term_ids)) :
            wp_set_post_terms($id, $term_ids, 'poll_choice');
        endif;
    endif;
}, 5);

/**
 * Allow poll URL params
 */
add_filter('query_vars', function ($vars) {
    $vars[] = 'poll';
    $vars[] = 'response';
    $vars[] = 'email';
    $vars[] = 'uid';
    return $vars;
});

/**
 * Cookie email and UID params if they are set
 */
add_action('init', __NAMESPACE__ . '\\cookie_email_uid', 10, 0);
function cookie_email_uid($email = null, $uid = null)
{
    if (!is_admin()) :
        $email = $email ?? $_REQUEST['email'] ?? null;
        $uid = $uid ?? $_REQUEST['uid'] ?? null;

        if (!empty($email) && !empty($uid)) :
            setcookie('email', $email, null, '/');
            setcookie('uid', $uid, null, '/');
        endif;
    endif;
}

/**
 * Display poll
 */
add_shortcode('poll', __NAMESPACE__ . '\\display_poll');
function display_poll($atts, $content)
{
    extract(shortcode_atts([
        'id' => null,
        'view' => 'web',
        'url' => get_permalink(),
        'clear' => !empty($_REQUEST['clear']) ? sanitize_text_field($_REQUEST['clear']) : false,
        'uid' => (!empty($_REQUEST['uid']) ? sanitize_text_field($_REQUEST['uid']) : null) ?? $_COOKIE['uid'] ?? null,
        'email' => (!empty($_REQUEST['email']) ? sanitize_text_field($_REQUEST['email']) : null) ?? $_COOKIE['email'] ?? null,
        'response' => (!empty($_REQUEST['response']) ? array_map('sanitize_text_field', $_REQUEST['response']) : null) ?? [],
        'username' => (!empty($_REQUEST['username']) ? sanitize_text_field($_REQUEST['username']) : null) ?? null /* honeypot */
    ], $atts));

    if (!$id) :
        return '';
    endif;

    $poll = get_post($id);
    if (empty($poll) || is_wp_error($poll)) :
        return '';
    endif;

    $poll_title = get_field('poll_title', $poll->ID) ?: '';
    $poll_choices = get_field('poll_choices', $poll->ID) ?: [];

    $poll_response = false;
    if (!empty($response) || !empty($clear)) :
        // If response included, submit poll response
        $poll_response = poll_response([
            'poll_id' => $id,
            'response' => $response,
            'email' => $email,
            'uid' => $uid,
            'clear' => $clear,
            'username' => $username /* honeypot */
        ]);
        $uid = $poll_response['data']['uid'] ?? $uid ?? null;
    elseif ($uid && !empty($existing_response = get_post_meta($poll->ID, "response_$uid", true))) :
        // Otherwise check if an existing response was recorded
        $response = json_decode($existing_response, true)['response'];
        $poll_response = $response;
    endif;

    $has_poll_choices = !empty($poll_choices);
    $has_valid_response = !empty($poll_response) && empty($poll_response['error']);

    ob_start();

    if ($view === 'email') :
?>
        <div class="poll">
            <?php if (!empty(trim($content))) : ?>
                <?= $content ?>
            <?php else : ?>
                <h3><?= $poll_title ?></h3>
            <?php endif; ?>
            <?php if ($has_poll_choices) : ?>
                <?php foreach ($poll_choices as $index => $poll_choice) : ?>
                    <?php
                    $term = $poll_choice['term'] ? get_term($poll_choice['term']) : null;
                    $is_last = $index === count($poll_choices) - 1;
                    ?>
                    <?php if (!empty($term) && !is_wp_error($term)) : ?>
                        <p class="poll__choice <?= $is_last ? 'poll__choice--last' : ''; ?>">
                            <a href="<?= add_query_arg([
                                            'poll' => $poll->ID,
                                            'response[]' => $term->term_id,
                                            'uid' => $uid,
                                            'email' => $email
                                        ], $url) ?>#poll-<?= $poll->ID; ?>" mc:disable-tracking>
                                <?= $poll_choice['choice'] ?>
                            </a>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
                <p class="poll__choice poll__choice--hidden">
                    <a href="<?= add_query_arg([
                                    'poll' => $poll->ID,
                                    'clear' => 1,
                                    'uid' => $uid,
                                    'email' => $email
                                ], $url) ?>" mc:disable-tracking>
                        Clear response
                    </a>
                </p>
            <?php endif; ?>
        </div>
    <?php
    else :
    ?>
        <form class="poll" action="<?= $url ?>" method="post" id="poll-<?= $poll->ID; ?>">
            <div class="poll__body">
                <?php if (!empty(trim($content))) : ?>
                    <?= $content ?>
                <?php else : ?>
                    <h3><?= $poll_title ?></h3>
                <?php endif; ?>
            </div>
            <?php if ($has_poll_choices) : ?>
                <?php if (!empty($poll_response)) : ?>
                    <!-- Error/success message -->
                    <?php if (!$has_valid_response) : ?>
                        <div class="alert alert-warning text-center">
                            <strong>Error:</strong> <?= $poll_response['error'] ?>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-success text-center">
                            <strong>Thanks!</strong> Your response has been recorded.
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (!$has_valid_response) : ?>
                    <!-- Choices -->
                    <ul class="poll__choices list-unstyled">
                        <?php $checked = false; ?>
                        <?php foreach ($poll_choices as $poll_choice) : ?>
                            <?php $term = $poll_choice['term'] ? get_term($poll_choice['term']) : null; ?>
                            <?php if (!empty($term) && !is_wp_error($term)) : ?>
                                <?php
                                $checked = !$checked && in_array($term->term_id, $response);
                                $disabled = !empty($poll_response) && empty($poll_response['error']);
                                ?>
                                <li class="poll__choice form-check">
                                    <input type="radio" name="response[]" id="choice-<?= $term->term_id ?>" class="form-check-input" value="<?= $term->term_id ?>" <?= $checked ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?> required>
                                    <label for="choice-<?= $term->term_id ?>" class="form-check-label ml-1"><?= $poll_choice['choice'] ?></label>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <?= display_poll_results(['id' => $id, 'response' => $response]); ?>
                <?php endif; ?>

                <?php if (empty($uid) || empty($email)) : ?>
                    <!-- Email input and "Subscribe + Vote" button -->
                    <div class="poll__subscribe text-center p-4 border">
                        <p><strong>Psst!</strong> You need to be a Daily Carnage subscriber to participate in this poll.</p>
                        <p class="small">Enter your email below to subscribe or verify your existing subscription.</p>

                        <label for="email-<?= $id ?>" class="sr-only">Email</label>
                        <input class="form-control form-control-lg" type="email" name="email" id="email-<?= $id ?>" placeholder="you@example.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn--gradient mt-4">Subscribe + Vote</button>

                <?php elseif (!$has_valid_response) : ?>
                    <!-- Hidden inputs and "Vote" button -->
                    <input type="hidden" name="uid" value="<?= $uid ?>">
                    <input type="hidden" name="email" value="<?= $email ?>">

                    <button type="submit" class="btn btn-primary btn--gradient mt-1">Vote</button>
                <?php endif; ?>

                <!-- Poll ID -->
                <input type="hidden" name="poll_id" value="<?= $poll->ID ?>">

                <!-- Honeypot -->
                <input type="text" class="sr-only" name="username" aria-label="Leave this field blank.">
            <?php endif; ?>
        </form>
        <script>
            // Remove query string from URL on page load
            var pollHash = document.location.hash || '';
            var pollUrl = document.location.href.replace(/\?.*$/, '') + pollHash;
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', pollUrl);
            }
        </script>
        <?php
    endif;

    return ob_get_clean();
}

/**
 * Get poll results
 */
function get_poll_results($id)
{
    $results = [];

    if ('poll' === get_post_type($id)) :
        if (have_rows('poll_choices', $id)) :
            // get poll choices
            while (have_rows('poll_choices', $id)) : the_row();
                $term_id = get_sub_field('term');
                $choice = get_sub_field('choice');
                if (!empty($term_id)) :
                    $results[(string) $term_id] = array(
                        'choice' => $choice,
                        'term_id' => $term_id,
                        'responses' => array()
                    );
                endif;
            endwhile;

            // get all responses and organize them by choice term ID
            $meta = get_post_meta($id);
            $responses = [];
            if (!empty($meta)) :
                foreach ($meta as $key => $raw_value) :
                    if (0 === strpos($key, 'response_') && !empty($raw_value)) :
                        try {
                            $value = json_decode($raw_value[0], true);
                            if (!empty($value['response'])) :
                                $response_arr = is_array($value['response']) ? $value['response'] : [$value['response']];
                                foreach ($response_arr as $term_id) :
                                    $str_id = (string) $term_id;
                                    if (array_key_exists($str_id, $results)) :
                                        $results[$str_id]['responses'][] = $value;
                                    endif;
                                endforeach;
                            endif;
                        } catch (Exception $e) {
                            // Ignore if invalid JSON
                        }
                    endif;
                endforeach;
            endif;

            // Sort results by # of responses descending
            usort($results, function ($a, $b) {
                $a_count = count($a['responses']);
                $b_count = count($b['responses']);

                if ($a_count === $b_count) :
                    return 0;
                else :
                    return $a_count > $b_count ? -1 : 1;
                endif;
            });
        endif;
    endif;

    return $results;
}

/**
 * Output poll results
 */
add_shortcode('poll_results', __NAMESPACE__ . '\\display_poll_results');
function display_poll_results($atts)
{
    extract(shortcode_atts([
        'id' => null,
        'response' => []
    ], $atts));

    $output = '<div class="poll__no-results">No results available yet!</div>';

    if ($id && 'poll' === get_post_type($id)) :
        if (have_rows('poll_choices', $id)) :
            // Get results
            $results = get_poll_results($id);

            // Get total number of responses
            $total_response_count = array_reduce($results, function ($prev, $choice) {
                return $prev + count($choice['responses']);
            }, 0);

            // Output results
            ob_start();
        ?>
            <!--
                -->
            <table class="table table-striped table-small text-dark border widefat fixed poll-results">
                <thead>
                    <tr>
                        <th class="poll-results__choice">Choice</th>
                        <th class="poll-results__result">Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result) : ?>
                        <?php $is_selected = in_array($result['term_id'], $response); ?>
                        <tr>
                            <td class="small <?= $is_selected ? 'font-weight-bold' : ''; ?>">
                                <span class="mr-2"><?= $result['choice'] ?></span>
                                <?php if ($is_selected) : ?>
                                    <span class="badge badge-dark text-white badge-pill align-text-bottom">Your Choice</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <?php
                                $response_count = count($result['responses']);
                                $ratio = 0 === $total_response_count ? 0 : $response_count / $total_response_count;
                                $percentage = round($ratio * 100, 1);
                                $text = str_replace('.0', '', $percentage) . "%";
                                ?>
                                <div class="progress" style="height: 1.5rem;">
                                    <div class="progress-bar progress-bar-striped <?= $ratio < 0.2 ? 'progress-bar--short' : '' ?>" role="progressbar" style="width: <?= "$percentage%" ?>;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                        <?= $text ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<?php
            $output = ob_get_clean();
        endif;
    endif;

    return $output;
}

/**
 * Display poll results on Poll edit screen
 */
add_filter('acf/load_field/key=field_5e712dab15d85', function ($field) {
    global $post;
    $field['message'] = display_poll_results(['id' => $post->ID]);
    return $field;
});

/**
 * Display responses
 */
add_filter('acf/load_field/key=field_5e712dda15d86', function ($field) {
    $field['message'] = 'TODO: Browse and search responses by email address.';
    return $field;
});

/**
 * Create a draft Job Opening post when form is submitted
 */
add_action('wpcf7_submit', function ($form, $result) {
    if (!empty($result['status']) && 'mail_sent' === $result['status']) :
        $is_job_form = $form->is_true('is_job_form');

        if ($is_job_form) :
            $submission = \WPCF7_Submission::get_instance();
            $data = $submission->get_posted_data();

            extract(shortcode_atts([
                'title' => '',
                'experience' => [],
                'type' => [],
                'location' => '',
                'remote' => [],
                'description' => '',
                'link' => '',
                'company' => '',
                'industry' => '',
                'contact_name' => $data['contact-name'] ?? '',
                'contact_email' => $data['contact-email'] ?? '',
            ], $data));

            // Create post
            $id = wp_insert_post([
                'post_type' => 'job_opening',
                'post_title' => "$company - $title",
                'post_excerpt' => $description,
                'post_content' => '',
                'meta_input' => [
                    '_title' => 'field_5e99bfc10143f',
                    'job_title' => $title,
                    '_locations' => 'field_5e98d6ed6aa67',
                    'locations' => implode("\n", preg_split('/(\s*[\/|]\s*)/', $location)),
                    '_remote_friendly' => 'field_5e98d940c18b1',
                    'remote_friendly' => !empty($remote),
                    '_link' => "field_5e98d87d6aa6c",
                    'link' => $link,
                    '_industries' => "field_5e98d7e66aa6b",
                    'industries' => implode("\n", preg_split('/(\s*[,\/|]\s*)/', $industry)),
                    '_contact_name' => "field_5e98d7936aa68",
                    'contact_name' => $contact_name,
                    '_contact_email' => "field_5e98d79a6aa69",
                    'contact_email' => $contact_email,
                ]
            ]);

            // Assign terms
            if (!empty($id) && !is_wp_error($id)) :
                wp_set_object_terms($id, $experience, 'job_experience');
                wp_set_object_terms($id, $type, 'job_type');
                wp_set_object_terms($id, [$company], 'company');
            endif;
        endif;
    endif;
}, 10, 2);

/**
 * Set Job Opening expires date
 */
add_filter('acf/update_value/name=expires', function ($value, $post_id, $field) {
    if (empty($value) && 'job_opening' === get_post_type($post_id) && 'publish' === get_post_status($post_id)) :
        $date = new \DateTime('+2 month');
        $value = $date->format('Ymd');
    endif;

    return $value;
}, 10, 3);
