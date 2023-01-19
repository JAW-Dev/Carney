<article @php(post_class('wp-block-carney-posts__post'))>
  @if (!in_array(get_post_type(), ['carnage_issue']) && has_post_thumbnail())
    <div class="wp-block-carney-posts__featured-image">
      <a href="{{ get_the_permalink() }}">
        {!! get_the_post_thumbnail(null, 'large_wide') !!}
      </a>
    </div>
  @endif
  <header>
    <div class="post-labels mb-3">
      @php(the_terms(null, 'department', '', '', ''))
    </div>
    <h2>
      <a href="{{ get_the_permalink() }}">
        {{ get_the_title() }}
      </a>
    </h2>
  </header>
  <div class="wp-block-carney-posts__excerpt">
    @php(the_excerpt())
  </div>
  <footer>
    <div class="post-labels">
      @php(the_terms(null, 'category', '', '', ''))
    </div>
    @if (!has_term( 'quote', 'carnage_feature_cat' ))
      <a href="{{ get_the_permalink() }}" class="btn btn--gradient {{ in_array(get_post_type(), ['carnage_issue', 'carnage_feature']) ? 'btn--gradient--pink' : 'btn-primary' }}">Learn More</a>
    @endif
  </footer>
</article>
