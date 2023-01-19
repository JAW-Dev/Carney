<?php
/**
 * Template Name: Gutenberg
 */
?>

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article @php(post_class())>
      @php(the_content())
    </article>
  @endwhile
@endsection
