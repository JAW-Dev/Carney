<header class="site-header headroom--unpinned">
  <div class="container nav-container">
    <a class="logo-link" href="{{ home_url('/') }}">@svg('logo-carney')</a>
    <button href="#" class="site-nav-toggle">
      <div class="site-nav-toggle__top"></div>
      <div class="site-nav-toggle__bottom"></div>
    </button>
    <nav class="nav-primary">
      @if (has_nav_menu('primary_navigation'))
        {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav list-inline']) !!}
      @endif
    </nav>
  </div>
</header>
<div class="nav-overlay"></div>
