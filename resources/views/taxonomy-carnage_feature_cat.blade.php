@extends('layouts.app')

@section('content')
  @if (!have_posts())
    <div class="alert alert-warning">
      {{ __('Sorry, no results were found.', 'sage') }}
    </div>
    {!! get_search_form(false) !!}
  @endif

  @include('partials.header-carnage')
  @include('partials.nav-carnage-categories')

  <section class="wp-block-carney-section bg-white text-dark">
    <div class="wp-block-carney-posts">
      <div class="container">
        <ul class="wp-block-carney-posts__list">
          @while (have_posts()) @php(the_post())
            <li>
              @include('partials.content-'.get_post_type())
            </li>
          @endwhile
        </ul>
      </div>
    </div>
  </section>

  <nav class="wp-block-carney-section bg-white text-dark">
    <div class="container py-md">
      {!! get_the_posts_navigation() !!}
    </div>
  </nav>
@endsection
