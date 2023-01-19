@extends('layouts.carnage-referral')

@section('content')
  @while(have_posts()) @php(the_post())
    <main class="main">
      <div id="app-content">
        <div class="app-content-inner">
          <header class="wp-block-carney-section bg--gradient bg--gradient--pink" key="header">
            <div class="container py-sm text-center">
              <img src="<?= App\asset_path('images/svg/logo-carnage.svg') ?>" alt="Carnage" width="600" height="74" style="max-width: 80%;" />
            </div>
          </header>

          <?php
            $r = !empty($_GET['r']) && '*|REF_ID|*' !== $_GET['r'] ? $_GET['r'] : null;
            $u = !empty($_GET['u']) && '*|REF_ID|*' !== $_GET['u'] ? $_GET['u'] : null;
            $type = !empty($u) ? 'user' : 'refer';
          ?>

          <?php if (!empty(SCALEMAIL_HOST)): ?>
            <section class="wp-block-carney-section">
              <?php if ('refer' === $type): ?>
                <div class="container py-md text-center">
                  <h1 class="h4 mb-4">Great news!</h1>
                  <p class="lead">You've been invited to subscribe to the Daily Carnage, the best dang marketing newsletter on the planet.</p>
                  <p>The Daily Carnage is your handpicked list of the best marketing content delivered to your inbox each day.</p>
                </div>
              <?php endif; ?>
              <script src="//<?= SCALEMAIL_HOST ?>/scripts/<?= $type ?>-iframe.js" id="scalemail-<?= $type ?>" type="text/javascript"></script>
            </section>
          <?php endif; ?>

        </div>
      </div>


      <?php if (!empty($r)): ?>
        <?php $issues = get_posts(['post_type' => 'carnage_issue', 'posts_per_page' => 3]); ?>
        <?php if (!empty($issues)): global $post; ?>
          <section class="wp-block-carney-section bg-gray text-dark py-md">
            <div class="wp-block-carney-posts pb-5">
              <div class="container">
                <h2 class="h6 mb-4 text-center">What can you expect?</h2>
                <p class="lead text-center">Check out some recent issues. Go ahead, we'll wait.</p>
                <ul class="wp-block-carney-posts__list mt-3">
                  <?php foreach ($issues as $post): setup_postdata($post); ?>
                    <li class="my-sm">
                      @include('partials.content-'.get_post_type())
                    </li>
                  <?php endforeach; wp_reset_postdata(); ?>
                </ul>
              </div>
            </div>
          </section>
        <?php endif; ?>
      <?php endif; ?>
    </main>

  @endwhile
@endsection
