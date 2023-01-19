<article @php(post_class('wp-block-carney-section bg-white text-dark'))>
  <header class="wp-block-carney-section bg-dark text-light">
    <div class="wp-block-carney-carnage-header pt-lg pb-sm">
      <div class="container">
        <div class="row">
          <div class="col-md-10">
            <div class="post-labels mb-3 border-0">
              <?php
                ob_start();
                the_terms(null, 'department', '<span class="post-label">', '</span><span class="post-label">', '</span>');
                $terms = ob_get_clean();
                echo strip_tags($terms, '<span>');
              ?>
            </div>
            <h1 class="entry-title h3">{{ get_the_title() }}</h1>
            <div class="mb-3">
              <time class="updated" datetime="{{ get_post_time('c', true) }}">Posted: {{ get_the_date() }}</time>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="entry-content pt-sm">
    <div class="container pb-3">
      <div class="row">
        <div class="col-md-10">
          @if ($post->post_excerpt)
            <div class="excerpt lead lead--larger mt-0 mb-4">
              @php(the_excerpt())
            </div>
          @endif
          @php(the_content())
        </div>
      </div>
    </div>
  </section>

  @if ($job_form)
    <section class="apply-form" id="apply">
      <div class="container pb-sm">
        <div class="row">
          <div class="col-md-10">
            <h2 class="h4 hr">Apply Now</h2>
            {!! $job_form !!}
          </div>
        </div>
      </div>
    </section>
  @endif

  <footer>
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>

  <a data-sumome-share-id="1164aca1-edaa-468e-ade3-ccbdbf4b20de"></a>
</article>
