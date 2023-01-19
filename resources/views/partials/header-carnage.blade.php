<header class="wp-block-carney-section">
  <div class="wp-block-carney-carnage-header pt-lg pb-sm pb-xl-md bg--gradient bg--gradient--pink">
    <div class="container">
      <div class="row">
        <div class="col-md-7 col-xl-8">
          <p class="h3">Be the <em>Sharpest Marketer</em> in the Room</p>
          <p class="lead mb-5 limit">The Daily Carnage is your handpicked list of the best marketing content delivered to your inbox each day.</p>
          <?php if (!empty($form_id = get_field('subscribe_form', 'options'))): ?>
            <?= do_shortcode("[contact-form-7 id='$form_id']") ?>
          <?php endif; ?>

          <p class="mt-sm text-uppercase"><strong>What People Are Saying:</strong></p>

          <ul class="wp-block-carney-fader">
            <li class="wp-block-carney-fader__item active">
              <blockquote class="pl-md-5 limit">
                <p class="mb-0"><em>"If you enjoy the Skimm as much as I do, and you love the world of marketing as much as I do, you should check out the newsletter The Daily Carnage."</em></p>
              </blockquote>
            </li>
            <li class="wp-block-carney-fader__item">
              <blockquote class="pl-md-5 limit">
                <p class="mb-0"><em>"I LOVE the Daily Carnage! I couldn't start my day without ya! THANK YOU!"</em></p>
              </blockquote>
            </li>
            <li class="wp-block-carney-fader__item">
              <blockquote class="pl-md-5 limit">
                <p class="mb-0"><em>"I definitely love the Daily Carnage. It's my daily dose of affirmations and kick in the butt – all at the same time."</em></p>
              </blockquote>
            </li>
            <li class="wp-block-carney-fader__item">
              <blockquote class="pl-md-5 limit">
                <p class="mb-0"><em>"There has been a gap to focus on online marketing in a fun, relevant way. I LOVE the Daily Carnage."</em></p>
              </blockquote>
            </li>
          </ul>

        </div>
        <div class="col-md-5 col-xl-4">
          <img src="@asset('images/carnage-phone.png')" width="453" height="901" class="wp-block-carney-carnage-header__phone-image">
        </div>
      </div>
    </div>
  </div>
</header>
