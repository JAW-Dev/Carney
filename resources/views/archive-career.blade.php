@extends('layouts.app')

@section('content')
  @include('partials.header-careers', ['have_posts' => have_posts()])

  <section class="wp-block-carney-section bg-white text-dark">
    <div class="wp-block-carney-posts pt-5 pb-sm">
      <div class="container">
        @if (!have_posts())
          @if ($careers_form)
            <div class="row" id="apply">
              <div class="col-md-10">
                <h2 class="sr-only">Apply Now</h2>
                {!! $careers_form !!}
              </div>
            </div>
          @endif
        @else
          <ul class="wp-block-carney-posts__list">
            @while (have_posts()) @php(the_post())
              <li>
                @include('partials.content-'.get_post_type())
              </li>
            @endwhile
          </ul>
        @endif
      </div>
    </div>
  </section>
@endsection
