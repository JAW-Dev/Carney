<article @php(post_class('wp-block-carney-section bg-white text-dark'))>
  <header class="bg-white">
    <section class="wp-block-carney-hero wp-block-carney-hero--xs text-white">
      <div class="container">
        <div class="h1 text-center text-shadow">The Daily Carnage</div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        @if (has_post_thumbnail())
          {{ the_post_thumbnail(null, 'large_wide') }}
        @else
          <img src="@asset('images/svg/bg-carnage-gradient.svg')" width="800" height="400" aria-hidden="true" style="-o-object-position: 50% 50%; object-position: 50% 50%;">
        @endif
      </div>
    </section>

    <div class="container pt-sm">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xl-8 offset-xl-2">
          <h1 class="entry-title h3">{{ get_the_title() }}</h1>
          @include('partials/entry-meta')

          @if ($post->post_excerpt)
            <div class="post-excerpt lead lead--larger">
              @php(the_excerpt())
            </div>
          @endif

          <?php
            $sponsor = get_field('sponsor');
            $now = time();
            $post_date = strtotime($post->post_date);
          ?>
          <?php if (!empty($sponsor) && ($now - $post_date < strtotime('1 week', 0))): ?>
            <?php
              $sponsor_name = $sponsor->name;
              $sponsor_logo = get_field('logo', "term_$sponsor->term_id");
              $sponsor_url = get_field('sponsor_landing_page') ?: get_field('website', "term_$sponsor->term_id");
            ?>
            <div class="post-sponsor text-center mt-4 mb-5">
              <p class="small">
                brought to you by<br>

                <span class="mt-1">
                  <?php if (!empty($sponsor_url)): ?>
                    <a href="<?= add_query_arg([
                      'utm_source' => 'daily-carnage',
                      'utm_medium' => 'web'
                    ], $sponsor_url); ?>">
                  <?php endif; ?>

                  <?php if (!empty($sponsor_logo)): ?>
                    <?= wp_get_attachment_image($sponsor_logo['ID'], 'medium') ?>
                  <?php else: ?>
                    <?= $sponsor_name ?>
                  <?php endif; ?>

                  <?php if (!empty($sponsor_url)): ?>
                    </a>
                  <?php endif; ?>
                </span>
              </p>

            </div>
          <?php endif; ?>

          <?php
            ob_start();
            the_terms(null, 'carnage_issue_cat', '', '', '');
            $terms = ob_get_clean();
          ?>
          <div class="post-labels mt-4 pt-4 <?= !empty($terms) ? 'mb-sm' : '' ?>">
            <?= $terms ?>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="entry-content">
    <div class="container pb-sm">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xl-8 offset-xl-2">
          <?php
            global $post;

            if (have_rows('features')):
              $feature_count = 0;
              while (have_rows('features')): the_row();

                switch (get_row_layout()):
                  case 'custom_content':
                    $title = get_sub_field('title');
                    $label = get_sub_field('label');

                    $cta_links = get_sub_field('cta_links');

                    $style = get_sub_field('style');
                    $class = 'rss-box' . ('gray' === $style ? ' rss-box--gray' : '');
                    ?>
                      <div class="<?= $class; ?>">
                        <?php if (!empty($label)): ?>
                          <span class="post-label"><?= $label ?></span>
                        <?php endif; ?>

                        <?php if (!empty($title)): ?>
                          <h2><?= $title ?></h2>
                        <?php endif; ?>

                        <?= wp_filter_content_tags(get_sub_field('custom_content')) ?>

                        <?php if (!empty($cta_links)): ?>
                          <p class="cta-links">
                            <?php foreach ($cta_links as $item): $cta_link = $item['link']; ?>
                              <?php if (!empty($cta_link) && !empty($cta_link['url'])): ?>
                                <?php
                                  $link_text = 'Learn More';
                                  if (!empty($cta_link['title'])):
                                    $link_text = $cta_link['title'];
                                  endif;
                                ?>
                                <a href="<?= $cta_link['url'] ?>" class="btn btn-primary btn--gradient" target="_blank"><?= $link_text ?></a>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </p>
                        <?php endif; ?>
                      </div>
                    <?php
                    break;

                  case 'feature_post':
                    $feature_count++;

                    $post = get_sub_field('feature_post');
                    if (!empty($post)):
                      setup_postdata($post);

                      // get feature category
                      $categories = get_the_terms($post, 'carnage_feature_cat');

                      // determine CSS class for this section
                      $class = 'rss-box';
                      if (!empty($categories)):
                        if (preg_match('/^(ad|quote)$/i', $categories[0]->slug, $cat_matches)):
                          $class = "rss-{$cat_matches[1]}";
                        endif;
                      endif;

                      // get CTA links
                      $cta_links = get_field('cta_links');
                      ?>
                        <div class="<?= $class; ?>">
                          <?php if (has_term('ad', 'carnage_feature_cat')): ?>
                            <h2><?= $categories[0]->name ?></h2>
                          <?php endif; ?>

                          <?php the_post_thumbnail('original'); ?>

                          <?php if (!empty($categories) && !has_term(array('reading', 'question'), 'carnage_feature_cat') && 'unknown' !== $categories[0]->slug): ?>
                            <a href="<?= get_term_link($categories[0], 'carnage_feature_cat') ?>" class="post-label"><?= $categories[0]->name ?></a>
                          <?php endif; ?>

                          <?php if (!has_term(array('ad', 'quote'), 'carnage_feature_cat')): ?>
                            <?php if (has_term(array('reading', 'question'), 'carnage_feature_cat')): ?>
                              <h2><?= $categories[0]->name; ?></h2>
                            <?php else: ?>
                              <h2><?php the_title(); ?></h2>
                            <?php endif; ?>
                          <?php endif; ?>

                          <?php the_content(); ?>

                          <?php if (!empty($cta_links)): ?>
                            <p class="cta-links">
                              <?php foreach ($cta_links as $item): $cta_link = $item['link']; ?>
                                <?php if (!empty($cta_link) && !empty($cta_link['url'])): ?>
                                  <?php
                                    $link_text = 'Learn More';
                                    if (!empty($cta_link['title'])):
                                      $link_text = $cta_link['title'];
                                    elseif (!empty($categories)):
                                      if (preg_match('/(read|watch|listen)/i', $categories[0]->name, $cat_matches)):
                                        $link_text = "{$cat_matches[1]} Now";
                                      endif;
                                    endif;
                                  ?>
                                  <a href="<?= $cta_link['url'] ?>" class="btn btn-primary btn--gradient" target="_blank"><?= $link_text ?></a>
                                <?php endif; ?>
                              <?php endforeach; ?>
                            </p>
                          <?php endif; ?>

                          <?php if (1 === $feature_count): ?>
                            <?php the_field('carnage_cta', 'options'); ?>
                          <?php endif; ?>
                        </div>
                      <?php
                      wp_reset_postdata();
                    endif;
                    break;

                  case 'poll':
                    $title = get_sub_field('title');
                    $label = get_sub_field('label');
                    $poll = get_sub_field('poll');
                    $custom_content = wp_filter_content_tags(trim(get_sub_field('custom_content') ?: ''));
                    $class = 'rss-box rss-poll';

                    if (!empty($poll)):?>
                      <?php if (!empty($poll_code = do_shortcode("[poll id=$poll] $custom_content [/poll]"))): ?>
                        <div class="<?= $class; ?>">
                          <?php if (!empty($label)): ?>
                            <span class="post-label"><?= $label ?></span>
                          <?php endif; ?>

                          <?php if (!empty($title)): ?>
                            <h2><?= $title ?></h2>
                          <?php endif; ?>

                          <?= $poll_code ?>
                        </div>
                      <?php endif; ?>
                    <?php endif;
                    break;

                  default:
                endswitch;

              endwhile;
            endif;
          ?>
        </div>
      </div>
    </div>
  </section>

  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>

  <a data-sumome-share-id="1164aca1-edaa-468e-ade3-ccbdbf4b20de"></a>
</article>
