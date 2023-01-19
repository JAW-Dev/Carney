@if ($related_posts)
  <section class="wp-block-carney-section {{ $related_posts_section_classes }}">
    @if ($related_posts_section_heading)
      <h2><span>{{ $related_posts_section_heading }}<span></h2>
    @endif
    <div class="wp-block-carney-posts wp-block-carney-posts--swipe pt-lg">
      <div class="container">
        @if ($related_posts_heading)
          <h2 class="dash mb-sm"><small>{{ $related_posts_heading }}</small></h2>
        @endif
      </div>
      <div class="wp-block-carney-posts__wrap pb-md" data-swipe-item=".wp-block-carney-posts__list li">
        <div class="container">
          <ul class="wp-block-carney-posts__list">
            <?php global $post; ?>
            @foreach ($related_posts as $post) @php(setup_postdata($post))
              <li>
                @include('partials.content-'.get_post_type())
              </li>
            @endforeach
            @php(wp_reset_postdata())
            @if ($related_posts_more_link)
              <li class="wp-block-carney-posts__more">
                <a href="{{ $related_posts_more_link['url'] }}" class="btn {{ in_array(get_post_type(), ['carnage_issue', 'carnage_feature']) ? 'btn--gradient--pink' : 'btn-primary' }} btn--gradient btn-xl">
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
