<?php
/**
 * Template Name: No Title
 */
?>

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class())>
      <section class="wp-block-carney-section pt-lg pb-sm">
        <div class="container">
          @php(the_content())
        </div>
      </section>
    </article>
  @endwhile
@endsection
