<?php
/**
 * Custom RSS Template for the Daily Carnage MailChimp campaign
 */

namespace App;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

$args = array(
  'post_type' => 'carnage_issue',
  'posts_per_page' => 1,
);

if (!empty($id = $_GET['id'] ?: null)):
  $args['post__in'] = [$id];
endif;

$posts = query_posts($args);

function resize_carnage_images($content) {
    if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
        return $content;
    }

    $selected_images = $attachment_ids = array();

    foreach( $matches[0] as $image ) {
        if (preg_match( '/wp-image-([0-9]+)/i', $image, $class_id) && ($attachment_id = absint($class_id[1]))) {
            /*
             * If exactly the same image tag is used more than once, overwrite it.
             * All identical tags will be replaced later with 'str_replace()'.
             */
            $selected_images[ $image ] = $attachment_id;
            // Overwrite the ID when the same image is included more than once.
            $attachment_ids[ $attachment_id ] = true;
        }
    }

    if (count( $attachment_ids ) > 1) {
        /*
         * Warm the object cache with post and meta information for all found
         * images to avoid making individual database calls.
         */
        _prime_post_caches(array_keys($attachment_ids), false, true);
    }

    foreach ($selected_images as $image => $attachment_id) {
        preg_match('/data-size="([a-zA-Z0-9-_]+)"/', $image, $image_size_match);
        $image_size = !empty($image_size_match) ? $image_size_match[1] : 'carnage';
        $image_src = wp_get_attachment_image_src($attachment_id, $image_size);
        if (!empty($image_src)) {
            if (!empty($image_src[1]) && $image_src[1] > 550):
                if (!empty($image_src[2])):
                    $image_src[2] = (550 / $image_src[1]) * $image_src[2];
                endif;

                $image_src[1] = 550;
            endif;

            $new_image = preg_replace([
                '/src="[^"]*"/i',
                '/width="[^"]*"/i',
                '/height="[^"]*"/i'
            ], [
                "src=\"{$image_src[0]}\"",
                "width=\"{$image_src[1]}\"",
                "height=\"{$image_src[2]}\""
            ], $image);

            $content = str_replace($image, $new_image, $content);
        }
    }

    return $content;
}

function sanitize_content($content = '')  {
  // Ensure there's a space before all newlines in the content
  $content = trim(preg_replace('/\s*(<br[^>]*?>)/', ' $1', $content));

  // Add a classname to the final <ul> and append an invisible div
  // https://www.mattdailey.net/blog/how-to-fix-extra-list-bullet-in-outlook-2013/
  $doc = new \DOMDocument(null, 'utf-8');
  if (!empty(trim($content)) && $doc->loadHTML('<?xml encoding="utf-8" ?>' . $content)):
    $body = $doc->getElementsByTagName('body')[0];
    if (!empty($body) && !empty($last_child = $body->lastChild) && !empty($type = $last_child->nodeName) && in_array($type, ['ul', 'ol'])):
      $class = $last_child->getAttribute('class') ?: '';
      $class = trim($class .= ' last-child');
      $last_child->setAttribute('class', $class);
      $div = $doc->createElement('div', '&nbsp;');
      $div->setAttribute('style', 'display:none;');
      $body->appendChild($div);
    endif;
    $content = preg_replace('/<body>\s*(.*?)\s*<\/body>/is', '$1', $doc->saveHTML($body));
  endif;

  return $content;
}

function custom_content_markup($count, $title = '', $label = '', $custom_content = '', $cta_links = [], $style) {
  $table_class = 'rss-box' . ('white' !== $style ? " rss-box--$style" : '');
  if ('white-callout' === $style):
    $table_class .= ' rss-box--callout';
  endif;
  $title = trim(preg_replace('/\s*(<br[^>]*?>)/', ' $1', $title));
  $custom_content = sanitize_content($custom_content);

  $cta_links = $cta_links && is_array($cta_links) ? array_filter($cta_links, function($cta_link) {
    return !empty($cta_link) && !empty($cta_link['link']) && !empty($cta_link['link']['url']);
  }) : [];

  if (empty($title) && empty($label) && empty($custom_content) && empty($cta_links)):
    return '';
  endif;

  ob_start();
  ?>
      <tr class="rss-box-row <?= 0 === $count ? 'rss-first-row' : ''; ?>">
          <td class="rss-box-cell <?= 0 === $count ? 'rss-first-cell' : ''; ?>">
              <table class="<?= $table_class ?>" width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                      <tr>
                          <td class="rss-box__inner">
                              <?php if (!empty($label)): ?>
                                  <p class="post__label-wrap">
                                      <span class="post__label"><?= $label ?></span>
                                  </p>
                              <?php endif; ?>

                              <?php if (!empty($title)): ?>
                                  <h2><?= (empty($label) ? '<br>' : '') . $title ?></h2>
                              <?php endif; ?>

                              <?php if (!empty(trim($custom_content))): ?>
                                <div class="rss-box__content-wrap">
                                  <?= $custom_content ?>
                                </div>
                              <?php endif; ?>

                              <?php if (!empty($cta_links)): ?>
                                  <p>
                                      <?php foreach ($cta_links as $item): $cta_link = $item['link']; ?>
                                          <?php
                                              $link_text = 'Learn More';
                                              if (!empty($cta_link['title'])):
                                                  $link_text = $cta_link['title'];
                                              endif;
                                              $cta_width = 0;
                                              if (!empty($cta_link) && !empty($cta_link['url'])):
                                                  // 10px per letter, plus 30px padding on left/right
                                                  $cta_width = strlen($link_text) * 10 + 60;
                                              endif;
                                          ?>
                                          <?php if (!empty($cta_link) && !empty($cta_link['url'])): ?>
                                              <?php $link_url = preg_replace('/carney.co\/(carnage-)?hire-us\/?$/', 'carney.co/carnage-hire-us/?email=*|EMAIL|*', $cta_link['url']); ?>
                                              <!--[if mso]>
                                                  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= $link_url ?>" style="height:44px;v-text-anchor:middle;width:<?= $cta_width ?>px;" arcsize="50%" strokecolor="#ff9966" fillcolor="#ff9966">
                                                      <v:fill color2="#ff6397" type="gradient" angle="90" />
                                                      <w:anchorlock/>
                                                      <center style="color:#ffffff;font-family:Helvetica, Arial,sans-serif;font-size:16px;text-transform:uppercase;font-weight:bold;"><?= $link_text ?></center>
                                                  </v:roundrect>
                                              <![endif]-->
                                              <!--[if !mso]><!---->
                                                  <a href="<?= $link_url ?>" target="_blank" class="button"><?= $link_text ?></a>
                                              <!-- <![endif]-->
                                          <?php endif; ?>
                                      <?php endforeach; ?>
                                  </p>
                              <?php endif; ?>
                          </td>
                      </tr>
                      <tr>
                        <td>
                          <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnDividerBlock" style="min-width:100%;">
                            <tbody class="mcnDividerBlockOuter">
                              <tr>
                                <td class="mcnDividerBlockInner" style="min-width:100%;padding:0;">
                                  <table class="mcnDividerContent" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width:100%;border-top:none;">
                                    <tbody>
                                      <tr>
                                        <td> <span></span>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </td>
                      </tr>
                  </tbody>
              </table>
          </td>
      </tr>
  <?php
  return ob_get_clean();
}

function add_utms($content) {
  return preg_replace_callback('/ href=([\'"])([^\'"]+)([\'"])/', function($matches) {
    $url = add_query_arg([
      'utm_source' => 'daily-carnage',
      'utm_medium' => 'email'
    ], $matches[2]);
    return ' href=' . $matches['1'] . $url . $matches[3];
  }, $content);
}

function inline_and_minify_markup($markup) {
  if (empty($markup)):
    return '';
  endif;

  $cssToInlineStyles = new CssToInlineStyles();
  $css = file_get_contents(__DIR__ . '/../../../dist/' . sage('assets')->get('static/daily-carnage.css'));

  $output = $cssToInlineStyles->convert(resize_carnage_images($markup), $css);
  $output = preg_replace('/^.*?<html><body[^>]*>(.*)<\/body><\/html>$/is', '$1', $output);
  $output = preg_replace('/%2A%7C([a-zA-Z:_]+)%7C%2A/', '*|$1|*', $output); // unencode MailChimp merge tags
  return trim(array_reduce(explode(PHP_EOL, $output), function($result, $current) {
      $result .= trim($current);
      return $result;
  }, ''));
}

header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
    <?php do_action('rss2_ns'); ?>>
<channel>
    <title><![CDATA[<?= strip_tags(convert_chars($posts[0]->post_excerpt ?: '')); ?>]]></title>
    <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
    <link><?php bloginfo_rss('url') ?></link>
    <description><?php
      if (!empty($posts)):
        $field_location = $posts[0];
        $include_footer_callout = get_field('include_footer_callout', $field_location) ?: get_field('include_footer_callout', $field_location = 'options');

        if ($include_footer_callout):
          $footer_title = trim(get_field('footer_title', $field_location));
          $footer_custom_content = trim(get_field('footer_custom_content', $field_location));
          $footer_cta_links = get_field('footer_cta_links', $field_location);
          $footer_spacer = '
            <tr>
              <td class="mcnDividerBlockInner" style="min-width:100%;padding:24px 0;">
                <table class="mcnDividerContent" style="min-width:100%;border-top:none;" width="100%" cellspacing="0" cellpadding="0" border="0">
                  <tbody>
                    <tr>
                      <td> <span></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          ';

          if (!empty($footer_title) || !empty($footer_custom_content) || !empty($footer_cta_links)):
            $footer_markup = inline_and_minify_markup(add_utms(custom_content_markup(1, $footer_title, '', $footer_custom_content, $footer_cta_links, 'callout')));
            echo !empty($footer_markup) ? '<![CDATA[' . $footer_markup . inline_and_minify_markup($footer_spacer) . ']]>' : '';
          endif;
        endif;
      endif;
    ?></description>
    <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
    <language><?php echo get_option('rss_language'); ?></language>
    <sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'hourly' ); ?></sy:updatePeriod>
    <sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '1' ); ?></sy:updateFrequency>
    <?php do_action('rss2_head'); ?>
    <?php while(have_posts()) : the_post(); ?>
        <item>
            <title><?php the_title_rss(); ?></title>
            <link><?php the_permalink_rss(); ?></link>
            <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
            <dc:creator><?php the_author(); ?></dc:creator>
            <guid isPermaLink="false"><?php the_guid(); ?></guid>
            <?php
              $sponsor = get_field('sponsor');
            ?>
            <?php ob_start(); ?>
              <tr>
                <td valign="top" id="templateCallout">
                  <table class="callout-box" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody class="mcnTextBlockOuter">
                      <tr>
                        <td class="mcnTextBlockInner callout-inner" valign="top">
                          <?php if (!empty($sponsor)): ?>
                            <?php
                              $sponsor_name = $sponsor->name;
                              $sponsor_logo = get_field('logo', "term_$sponsor->term_id");
                              $sponsor_url = get_field('sponsor_landing_page') ?: get_field('website', "term_$sponsor->term_id");
                            ?>
                            <p>brought to you by</p>
                            <?php if (!empty($sponsor_url)): ?>
                              <p class="sponsor-logo-wrap"><a href="<?= $sponsor_url ?>">
                            <?php else: ?>
                              <p class="sponsor-name-wrap"><strong>
                            <?php endif; ?>
                              <?php if (!empty($sponsor_logo)): ?>
                                <?php
                                  $area_ratio = $sponsor_logo['sizes']['small_logo-width'] * $sponsor_logo['sizes']['small_logo-height'] / 12000;
                                  $width = round($sponsor_logo['sizes']['small_logo-width'] / $area_ratio);
                                  $height = round($sponsor_logo['sizes']['small_logo-height'] / $area_ratio);
                                  $logo_url = $sponsor_logo['sizes']['small_wide'];
                                ?>
                                <!--[if mso]>
                                <img src="<?= $logo_url ?>" class="sponsor-logo wp-image-<?= $sponsor_logo['ID'] ?>" data-size="medium_logo" alt="<?= $sponsor_name ?>" width="<?= $width ?>" height="<?= $height ?>" style="width: <?= $width ?>px !important; height: <?= $height ?>px !important;">
                                <![endif]-->
                                <!--[if !mso]><!---->
                                  <img src="<?= $logo_url ?>" class="sponsor-logo wp-image-<?= $sponsor_logo['ID'] ?>" data-size="small_wide" alt="<?= $sponsor_name ?>" width="<?= $width ?>" height="<?= $height ?>" style="width: <?= $width ?>px !important; height: <?= $height ?>px !important;">
                                <!-- <![endif]-->
                              <?php else: ?>
                                <?= $sponsor_name ?>
                              <?php endif; ?>
                            <?php if (!empty($sponsor_url)): ?>
                              </a></p>
                            <?php else: ?>
                              </strong></p>
                            <?php endif;?>
                          <?php else: ?>
                            <p>created by <b><a href="https://carney.co">carney</a></b>, a dynamic digital agency</p>
                          <?php endif; ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            <?php $sponsor_markup = inline_and_minify_markup(add_utms(ob_get_clean())); ?>
            <description><![CDATA[<?= !empty($sponsor_markup) ? $sponsor_markup : ''; ?>]]></description>
            <content:encoded><![CDATA[<?php

                $output = '<table class="rss-content" cellspacing="0" cellpadding="0" border="0"><tbody>';

                $count = 0;
                if (have_rows('features')):
                    while (have_rows('features')): the_row();

                        switch (get_row_layout()):
                            case 'poll':
                                $title = trim(get_sub_field('title'));
                                $label = trim(get_sub_field('label'));
                                $poll = get_sub_field('poll');
                                $poll_title = get_field('poll_title', $poll);
                                $custom_content = trim(get_sub_field('custom_content')) ?: '';
                                $cta_links = get_sub_field('cta_links');
                                $style = 'white';

                                $poll_code = do_shortcode("[poll id=$poll view='email' email='*|EMAIL|*' uid='*|UNIQID|*']" . $custom_content . "[/poll]");

                                $output .= custom_content_markup(
                                  $count,
                                  $title,
                                  $label,
                                  $poll_code,
                                  $cta_links,
                                  $style
                                );
                                break;

                            case 'share_cta':
                                $title = trim(get_sub_field('title'));
                                $encoded_permalink = urlencode(get_permalink());
                                ob_start();
                                ?>
                                  <div style="text-align:center;font-size:0;">
                                    <!--[if mso]>
                                    <table role="presentation" width="100%">
                                    <tr>
                                    <td style="width:43%;padding:25px 25px 0 25px;" valign="middle">
                                    <![endif]-->
                                    <div style="width:100%;max-width:225px;display:inline-block;vertical-align:middle;">
                                      <div style="width: auto; max-width: 100%; padding:25px 25px 0 25px;">
                                        <h2 style="font-size: 20px; margin: 0; text-transform: none; text-align: center;">Share this issue</h2>
                                      </div>
                                    </div>
                                    <!--[if mso]>
                                    </td>
                                    <td style="width:57%;padding:25px 17px 0 17px;" valign="middle">
                                    <![endif]-->
                                    <div style="width:100%;max-width:300px;display:inline-block;vertical-align:middle;">
                                      <div style="width: 250px; max-width: 100%; padding:25px 17px 0 17px;font-size:14px;line-height:18px;text-align:left; margin: 0 auto;">
                                        <table class="social social--block" width="100%" cellspacing="0" cellpadding="0" border="0">
                                          <tbody>
                                            <tr>
                                              <td style="width: 40px; padding: 0 8px;"> <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encoded_permalink ?>">
                                                <!--[if mso]>
                                                <img alt="facebook" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/32fdab2f-d261-4599-817c-742d8aed4d92.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <![endif]-->
                                                <!--[if !mso]><!--><img alt="facebook" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/df9d32e2-0337-4efb-9963-70a94484e5b3.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <!--<![endif]--></a>
                                              </td>
                                              <td style="width: 40px; padding: 0 8px;"> <a href="https://twitter.com/intent/tweet/?via=carney_co&text=<?= urlencode(get_the_title() . ' – The Daily Carnage') ?>&url=<?= $encoded_permalink ?>">
                                                <!--[if mso]>
                                                <img alt="twitter" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/15491c34-de6a-4e38-92eb-e8fe10227400.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <![endif]-->
                                                <!--[if !mso]><!--><img alt="twitter" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/cfb66991-1d61-4f3d-b822-339fd81feeab.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <!--<![endif]--></a>
                                              </td>
                                              <td style="width: 40px; padding: 0 8px;"> <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $encoded_permalink ?>">
                                                <!--[if mso]>
                                                <img alt="linkedin" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/02d9385d-fc38-4ce2-8d91-d60d76663b19.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <![endif]-->
                                                <!--[if !mso]><!--><img alt="linkedin" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/397515df-8c93-4309-ab2c-03443a1ff705.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <!--<![endif]--></a>
                                              </td>
                                              <td style="width: 40px; padding: 0 8px;"> <a href="mailto:?subject=<?= urlencode(get_the_title() . ' – The Daily Carnage') ?>&body=<?= $encoded_permalink ?>">
                                                <!--[if mso]>
                                                <img alt="email" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/12df6510-3509-49ee-805d-4923ff6109d7.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <![endif]-->
                                                <!--[if !mso]><!--><img alt="email" src="https://gallery.mailchimp.com/3052de70878ed32dbf7046722/images/28d283be-fbe9-4909-8ec7-6ba0f4838638.png" width="40" height="40" style="width: 40px; max-width: none; margin-bottom: 0;">
                                                <!--<![endif]--></a>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <!--[if mso]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                  </div>
                                <?php
                                $custom_content = ob_get_clean();

                                $output .= custom_content_markup(
                                  $count,
                                  $title,
                                  null,
                                  $custom_content,
                                  $cta_links,
                                  'dark'
                                );
                                break;

                            case 'custom_content':
                                $title = trim(get_sub_field('title'));
                                $label = trim(get_sub_field('label'));
                                $custom_content = trim(get_sub_field('custom_content'));
                                $cta_links = get_sub_field('cta_links');
                                $style = get_sub_field('style');

                                $output .= custom_content_markup(
                                  $count,
                                  $title,
                                  $label,
                                  $custom_content,
                                  $cta_links,
                                  $style
                                );
                                break;

                            case 'feature_post':
                                $post = get_sub_field('feature_post');
                                setup_postdata($post);

                                // get feature category
                                $categories = get_the_terms($post, 'carnage_feature_cat');
                                $category_name = '';
                                $category_slug = '';
                                $category_link = '';
                                if (!empty($categories)):
                                    $category_name = $categories[0]->name;
                                    $category_slug = $categories[0]->slug;
                                    $category_link = '
                                        <p class="post__label-wrap">
                                            <a href="' . get_term_link($categories[0], 'carnage_feature_cat') . '" class="post__label">Category: ' . $categories[0]->name . '</a>
                                        </p>
                                    ';
                                endif;


                                // determine CSS class for this section
                                $class = 'rss-box';
                                $extra_class = '';
                                if (!empty($categories)):
                                    if (preg_match('/^(ad|quote)$/i', $categories[0]->slug, $cat_matches)):
                                        $class = "rss-{$cat_matches[1]}";
                                    endif;

                                    if (preg_match('/^(reading|question)$/i', $categories[0]->slug, $cat_matches)):
                                        $extra_class = ' rss-box--with-icon';
                                    endif;
                                endif;

                                // get CTA link and link text
                                $cta_links = get_field('cta_links');
                                ob_start();
                                ?>

                                    <tr class="<?= $class ?>-row <?= 0 === $count ? 'rss-first-row' : '' ?>">
                                        <td class="<?= $class ?>-cell <?= 0 === $count ? 'rss-first-cell' : '' ?>">
                                            <table class="<?= $class . $extra_class ?>" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td class="<?= $class ?>__inner">
                                                            <?php if ('ad' === $category_slug): ?>
                                                                <?= $category_link ?>
                                                            <?php endif; ?>

                                                            <?php if (in_array($category_slug, ['reading', 'question'])): ?>
                                                                <?php
                                                                    $icons = [
                                                                        'reading' => ['lg_src' => asset_path('images/icon-reading@3x.png'), 'sm_src' => asset_path('images/icon-reading.png'), 'width' => 49, 'height' => 39],
                                                                        'question' => ['lg_src' => asset_path('images/icon-question@3x.png'), 'sm_src' => asset_path('images/icon-question.png'), 'width' => 50, 'height' => 50]
                                                                    ];
                                                                ?>
                                                                <p class="carnage-icon-wrap">
                                                                  <!--[if mso]>
                                                                      <img src="<?= $icons[$category_slug]['sm_src'] ?>" class="carnage-icon" alt="<?= $category_slug ?>" width="<?= $icons[$category_slug]['width'] ?>" height="<?= $icons[$category_slug]['height'] ?>" style="width: <?= $icons[$category_slug]['width'] ?>px !important; height: <?= $icons[$category_slug]['height'] ?>px !important;">
                                                                  <![endif]-->
                                                                  <!--[if !mso]><!---->
                                                                      <img src="<?= $icons[$category_slug]['lg_src'] ?>" class="carnage-icon" alt="<?= $category_slug ?>" width="<?= $icons[$category_slug]['width'] ?>" height="<?= $icons[$category_slug]['height'] ?>" style="width: <?= $icons[$category_slug]['width'] ?>px !important; height: <?= $icons[$category_slug]['height'] ?>px !important;">
                                                                  <!-- <![endif]-->
                                                                </p>
                                                            <?php endif; ?>

                                                            <?php if (has_post_thumbnail()): ?>
                                                                <?php if (!empty($cta_links) && !empty($cta_links[0]) && !empty($cta_links[0]['link']) && !empty($cta_links[0]['link']['url'])): ?>
                                                                    <?php $link_url = preg_replace('/carney.co\/(carnage-)?hire-us\/?$/', 'carney.co/carnage-hire-us/?email=*|EMAIL|*', $cta_links[0]['link']['url']); ?>
                                                                    <a href="<?= $link_url ?>" rel="noopener" target="_blank">
                                                                <?php endif; ?>

                                                                <?php
                                                                    $image_id = get_post_thumbnail_id();
                                                                    $image_src = get_the_post_thumbnail_url(null, 'carnage');
                                                                    $image_src_large = get_the_post_thumbnail_url(null, 'large');
                                                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                                                    $image_meta = wp_get_attachment_metadata($image_id);
                                                                    $image_width = !empty($image_meta['sizes']['carnage']['width']) ? $image_meta['sizes']['carnage']['width'] : "";
                                                                    $image_height = !empty($image_meta['sizes']['carnage']['height']) ? $image_meta['sizes']['carnage']['height'] : "";
                                                                ?>
                                                                <figure>
                                                                    <!--[if mso]>
                                                                        <img src="<?= $image_src ?>" class="wp-image-<?= $image_id ?>" alt="<?= $image_alt ?>" width="<?= $image_width ?>" height="<?= $image_height ?>">
                                                                    <![endif]-->
                                                                    <!--[if !mso]><!---->
                                                                        <img src="<?= $image_src_large ?>" class="wp-image-<?= $image_id ?>" data-size="large" alt="<?= $image_alt ?>" width="<?= $image_width ?>" height="<?= $image_height ?>">
                                                                    <!-- <![endif]-->
                                                                </figure>

                                                                <?php if (!empty($cta_links) && !empty($cta_links[0]) && !empty($cta_links[0]['link']) && !empty($cta_links[0]['link']['url'])): ?>
                                                                    </a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                            <?php if (!in_array($category_slug, ['ad', 'reading', 'question'])): ?>
                                                                <?= $category_link ?>
                                                            <?php endif; ?>

                                                            <?php if (!in_array($category_slug, ['ad', 'reading', 'question', 'quote'])): ?>
                                                                <h2><?= empty($categories) ? '<br>' : ''; ?><?php the_title(); ?></h2>
                                                            <?php elseif (in_array($category_slug, ['reading', 'question'])): ?>
                                                                <h2><?= $category_name; ?></h2>
                                                            <?php endif; ?>

                                                            <?php if (!empty(trim($post->post_content))): ?>
                                                              <div class="rss-box__content-wrap">
                                                                <?= sanitize_content(apply_filters('the_content', trim($post->post_content))); ?>
                                                              </div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($cta_links)): ?>
                                                                <p>
                                                                    <?php foreach ($cta_links as $item): $cta_link = $item['link']; ?>
                                                                        <?php if (!empty($cta_link) && !empty($cta_link['url'])): ?>
                                                                            <?php
                                                                                $cta_width = 0; // for VML buttons
                                                                                $link_text = 'Learn More';
                                                                                if (!empty($cta_link) && !empty($cta_link['url'])):
                                                                                    $link_url = preg_replace('/carney.co\/(carnage-)?hire-us\/?$/', 'carney.co/carnage-hire-us/?email=*|EMAIL|*', $cta_link['url']);
                                                                                    if (!empty($cta_link['title'])):
                                                                                        $link_text = $cta_link['title'];
                                                                                    elseif (!empty($categories)):
                                                                                        if (preg_match('/(read|watch|listen)/i', $categories[0]->name, $cat_matches)):
                                                                                            $link_text = "{$cat_matches[1]} Now";
                                                                                        endif;
                                                                                    endif;

                                                                                    // 10px per letter, plus 30px padding on left/right
                                                                                    $cta_width = strlen($link_text) * 10 + 60;
                                                                                endif;
                                                                            ?>
                                                                            <!--[if mso]>
                                                                                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="<?= $link_url ?>" style="height:44px;v-text-anchor:middle;width:<?= $cta_width ?>px;" arcsize="50%" strokecolor="#ff9966" fillcolor="#ff9966">
                                                                                    <v:fill color2="#ff6397" type="gradient" angle="90" />
                                                                                    <w:anchorlock/>
                                                                                    <center style="color:#ffffff;font-family:Helvetica, Arial,sans-serif;font-size:16px;text-transform:uppercase;font-weight:bold;"><?= $link_text ?></center>
                                                                                </v:roundrect>
                                                                            <![endif]-->
                                                                            <!--[if !mso]><!---->
                                                                                <a href="<?= $link_url ?>" target="_blank" class="button"><?= $link_text ?></a>
                                                                            <!-- <![endif]-->
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </p>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                <?php
                                $output .= ob_get_clean();
                                wp_reset_postdata();
                                break;

                            default:
                        endswitch;

                        $count++;
                    endwhile;
                endif;

                $output .= '</tbody></table>';

                echo inline_and_minify_markup(add_utms($output));
            ?>]]></content:encoded>
            <?php rss_enclosure(); ?>
            <?php do_action('rss2_item'); ?>
        </item>
    <?php endwhile; ?>
</channel>
</rss>
