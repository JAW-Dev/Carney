@extends('layouts.app')

@section('content')
  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif

  @php($index = 0)
  @while (have_posts())
    @php(the_post())
    @if (0 === $index)
      <article class="wp-block-carney-section">
        <div class="wp-block-carney-post-preview py-lg-lg">
          <div class="container order-last order-lg-first">
            <div class="row">
              <div class="col-lg-7 wp-block-carney-post-preview__content-col indent indent--xs">
                <header>
                  <div class="h6 dash no-indent mb-5"><small>The Newest</small></div>
                  <div class="post-labels mb-4">
                    @php(the_terms(null, 'category', '', '', ''))
                  </div>
                  <h2 class="h4 entry-title"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h2>
                  @include('partials/entry-meta')
                </header>
                <div class="entry-summary lead mb-sm">
                  @php(the_excerpt())
                </div>
                <a class="btn btn-primary btn--gradient" href="{{ get_permalink() }}">Read More</a>
              </div>
            </div>
          </div>
          <div class="container-fluid wp-block-carney-post-preview__image-container">
            <div class="row">
              <div class="col-lg-5 offset-lg-7 wp-block-carney-post-preview__image-col">
                {!! get_the_post_thumbnail(null, 'large_wide') !!}
              </div>
            </div>
          </div>
        </div>
      </article>
    @endif
    @php($index++)
  @endwhile
  @php(rewind_posts())

  <nav class="wp-block-carney-section">
    <div class="wp-block-carney-category-nav py-sm pt-md-lg" id="categories">
      <div class="container">
        <div class="select-nav-wrap d-md-none">
          <select class="select-nav">
            <option value="{{ get_permalink(get_option('page_for_posts')) }}#categories" {{ is_home() ? 'selected' : ''}}>All Posts</option>
            <option value="{{ get_term_link('strategy', 'category') }}#categories" {{ is_category('strategy') ? 'selected' : '' }}>Strategy</option>
            <option value="{{ get_term_link('content', 'category') }}#categories" {{ is_category('content') ? 'selected' : '' }}>Content</option>
            <option value="{{ get_term_link('design', 'category') }}#categories" {{ is_category('design') ? 'selected' : '' }}>Design</option>
            <option value="{{ get_term_link('development', 'category') }}#categories" {{ is_category('development') ? 'selected' : '' }}>Development</option>
            <option value="{{ get_term_link('marketing', 'category') }}#categories" {{ is_category('marketing') ? 'selected' : '' }}>Marketing</option>
            <option value="{{ get_term_link('analytics', 'category') }}#categories" {{ is_category('analytics') ? 'selected' : '' }}>Analytics</option>
          </select>
        </div>
        <ul class="nav d-none d-md-flex">
          <li class="nav-item nav-item--all {{ is_home() ? 'active' : ''}}"><a href="{{ get_permalink(get_option('page_for_posts')) }}" class="nav-link dash">All Posts</a></li>
          <li class="nav-item nav-item--strategy {{ is_category('strategy') ? 'active' : '' }}"><a href="{{ get_term_link('strategy', 'category') }}" class="nav-link dash">Strategy @svg('icon-strategy')</a></li>
          <li class="nav-item nav-item--content {{ is_category('content') ? 'active' : '' }}"><a href="{{ get_term_link('content', 'category') }}" class="nav-link dash">Content @svg('icon-design')</a></li>
          <li class="nav-item nav-item--design {{ is_category('design') ? 'active' : '' }}"><a href="{{ get_term_link('design', 'category') }}" class="nav-link dash">Design @svg('icon-content')</a></li>
          <li class="nav-item nav-item--development {{ is_category('development') ? 'active' : '' }}"><a href="{{ get_term_link('development', 'category') }}" class="nav-link dash">Development @svg('icon-development')</a></li>
          <li class="nav-item nav-item--marketing {{ is_category('marketing') ? 'active' : '' }}"><a href="{{ get_term_link('marketing', 'category') }}" class="nav-link dash">Marketing @svg('icon-marketing')</a></li>
          <li class="nav-item nav-item--analytics {{ is_category('analytics') ? 'active' : '' }}"><a href="{{ get_term_link('analytics', 'category') }}" class="nav-link dash">Analytics @svg('icon-analytics')</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="wp-block-carney-section">
    <div class="wp-block-carney-posts pb-5">
      <div class="container">
        <ul class="wp-block-carney-posts__list">
          @php($index = 0)
          @while (have_posts()) @php(the_post())
            @if (0 < $index)
              <li>
                @include('partials.content-'.get_post_type())
              </li>
            @endif
            @php($index++)
          @endwhile
        </ul>
      </div>
    </div>
  </section>

  <nav class="wp-block-carney-section">
    <div class="container pt-sm pb-md">
      {!! get_the_posts_navigation() !!}
    </div>
  </nav>

  @include('partials.cta-carnage')
@endsection
