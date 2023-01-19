<div class="entry-meta">
  <time class="updated" datetime="{{ get_post_time('c', true) }}">{{ get_the_date() }}</time>
  @if (!in_array(get_post_type(), ['carnage_issue', 'carnage_feature']))
    <p class="byline author vcard">
      {{ __('By', 'sage') }} <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" rel="author" class="fn">
        {{ get_the_author() }}
      </a>
    </p>
  @endif
</div>
