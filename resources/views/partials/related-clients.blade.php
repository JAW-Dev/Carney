@if ($related_posts)
  <section class="wp-block-carney-section">
    <div class="wp-block-carney-related-clients pt-lg">
      <div class="container">
        <h2 class="dash mb-sm"><small>Related</small></h2>
      </div>
      <div class="wp-block-carney-related-clients__wrap pb-md" data-swipe-item=".wp-block-carney-related-clients__list li">
        <div class="container">
          <ul class="wp-block-carney-related-clients__list">
            <?php global $post; ?>
            @foreach ($related_posts as $post) @php(setup_postdata($post))
              <li>
                <a href="{{ get_the_permalink() }}" class="btn btn-block btn-primary btn--gradient btn--mega btn--mega--with-image">
                  <div class="btn__text-wrap">
                    <small>{{ get_the_title() }}</small>
                    <span class="btn__text">Discover →</span>
                  </div>
                  <div class="btn__image-wrap">
                    {!! get_the_post_thumbnail(null, 'medium_large') !!}
                  </div>
                </a>
              </li>
            @endforeach
            @php(wp_reset_postdata())
            @if ($related_posts_more_link)
              <li class="wp-block-carney-posts__more">
                <a href="{{ $related_posts_more_link['url'] }}" class="btn btn-primary btn-xl btn--gradient">
                  {{ $related_posts_more_link['title'] }}
                </a>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  </section>
@endif
