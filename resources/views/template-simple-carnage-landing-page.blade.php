<?php
/**
 * Template Name: Simple Carnage Landing Page
 */

  add_action('wp_head', function() {
    ?>
      <style>
        span.logo-link {
          max-width: 18rem;
        }

        .site-header::before {
          display: none !important;
        }

        .page-footer__social-col {
          display: none;
        }

        @media (min-width: 768px) {
          .wp-block-carney-carnage-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            min-height: calc(100vh - 239px);
          }
        }
      </style>
    <?php
  }, -10);
?>

@extends('layouts.carnage-simple')

@section('content')
  @include('partials.header-carnage')
@endsection
