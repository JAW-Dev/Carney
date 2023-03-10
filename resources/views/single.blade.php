@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.content-single-'.get_post_type())
    @include('partials.cta-carnage')
    @include('partials.related-posts')
  @endwhile
@endsection
