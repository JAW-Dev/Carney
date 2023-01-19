<nav class="wp-block-carney-section bg-white text-dark">
  <div class="wp-block-carney-category-nav py-sm pt-md-lg" id="categories">
    <div class="container">
      <div class="select-nav-wrap d-md-none">
        <select class="select-nav">
          <option value="{{ get_post_type_archive_link('carnage_issue') }}#categories" {{ is_post_type_archive('carnage_issue') ? 'selected' : ''}}>All Posts</option>
          <option value="{{ get_term_link('listen', 'carnage_feature_cat') }}#categories" {{ is_tax('carnage_feature_cat', 'listen') ? 'selected' : '' }}>Listen</option>
          <option value="{{ get_term_link('read', 'carnage_feature_cat') }}#categories" {{ is_tax('carnage_feature_cat', 'read') ? 'selected' : '' }}>Read</option>
          <option value="{{ get_term_link('watch', 'carnage_feature_cat') }}#categories" {{ is_tax('carnage_feature_cat', 'watch') ? 'selected' : '' }}>Watch</option>
          <option value="{{ get_term_link('quote', 'carnage_feature_cat') }}#categories" {{ is_tax('carnage_feature_cat', 'quote') ? 'selected' : '' }}>Quote of the Day</option>
          <option value="{{ get_term_link('ad', 'carnage_feature_cat') }}#categories" {{ is_tax('carnage_feature_cat', 'ad') ? 'selected' : '' }}>Ads from the Past</option>
        </select>
      </div>
      <ul class="nav d-none d-md-flex">
        <li class="nav-item nav-item--all {{ is_post_type_archive('carnage_issue') ? 'active' : ''}}"><a href="{{ get_post_type_archive_link('carnage_issue') }}" class="nav-link dash">All Posts</a></li>
        <li class="nav-item nav-item--listen {{ is_tax('carnage_feature_cat', 'listen') ? 'active' : '' }}"><a href="{{ get_term_link('listen', 'carnage_feature_cat') }}" class="nav-link dash">Listen</a></li>
        <li class="nav-item nav-item--read {{ is_tax('carnage_feature_cat', 'read') ? 'active' : '' }}"><a href="{{ get_term_link('read', 'carnage_feature_cat') }}" class="nav-link dash">Read</a></li>
        <li class="nav-item nav-item--watch {{ is_tax('carnage_feature_cat', 'watch') ? 'active' : '' }}"><a href="{{ get_term_link('watch', 'carnage_feature_cat') }}" class="nav-link dash">Watch</a></li>
        <li class="nav-item nav-item--quote {{ is_tax('carnage_feature_cat', 'quote') ? 'active' : '' }}"><a href="{{ get_term_link('quote', 'carnage_feature_cat') }}" class="nav-link dash">Quote of the Day</a></li>
        <li class="nav-item nav-item--ad {{ is_tax('carnage_feature_cat', 'ad') ? 'active' : '' }}"><a href="{{ get_term_link('ad', 'carnage_feature_cat') }}" class="nav-link dash">Ads from the Past</a></li>
      </ul>
    </div>
  </div>
</nav>
