<article @php(post_class('wp-block-carney-section bg-white text-dark'))>
  <header class="bg-white">
    @if (has_post_thumbnail())
      <section class="wp-block-carney-hero wp-block-carney-hero--xs text-white">
        @if (get_post_type() === 'carnage_feature')
          <div class="container">
            <div class="h1 text-center text-shadow">The Daily Carnage</div>
          </div>
        @endif
        <div class="wp-block-carney-hero__media-wrap">
          {{ the_post_thumbnail(null, 'large_wide') }}
        </div>
      </section>
    @endif

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

          <div class="post-labels mt-4 mb-sm pt-4">
            @php(the_terms(null, (get_post_type() === 'post' ? 'category' : 'carnage_feature_cat'), '', '', ''))
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="entry-content">
    <div class="container pb-sm">
      <div class="row">
        <div class="col-md-10 offset-md-1 col-xl-8 offset-xl-2">
          @php(the_content())
        </div>
      </div>
    </div>
  </section>

  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>

  <a data-sumome-share-id="1164aca1-edaa-468e-ade3-ccbdbf4b20de"></a>
</article>
