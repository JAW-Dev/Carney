<article @php(post_class('wp-block-carney-section bg-white text-dark'))>
  <header class="bg-white">
    <section class="wp-block-carney-hero wp-block-carney-hero--xs text-white">
      <div class="container">
        <div class="h1 text-center text-shadow">The Daily Carnage</div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <div class="wp-block-carney-hero__media-wrap">
          @if (has_post_thumbnail())
            {{ the_post_thumbnail(null, 'large_wide') }}
          @else
            <img src="@asset('images/svg/bg-carnage-gradient.svg')" width="800" height="400" aria-hidden="true" style="-o-object-position: 50% 50%; object-position: 50% 50%;">
          @endif
        </div>
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

          <div class="post-labels mt-4 mb-sm pt-4">@php(the_terms(null, 'carnage_feature_cat', '', '', ''))</div>
        </div>
      </div>
    </div>
  </header>

  <section class="entry-content">
    <div class="container pb-sm">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xl-8 offset-xl-2">
          <?php the_content(); ?>
          <?php $cta_links = get_field('cta_links'); ?>
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
        </div>
      </div>
    </div>
  </section>

  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>

  <a data-sumome-share-id="1164aca1-edaa-468e-ade3-ccbdbf4b20de"></a>
</article>
