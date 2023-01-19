<h1 class="sr-only">{!! App::title() !!}</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm">
      <div class="container d-flex flex-column align-items-stretch position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="order-first mb-4">
              <p class="small mb-2 text-uppercase">Prepared for:</p>
              @svg('logo-art-van-white', ['style' => 'max-width: 150px'])
            </div>
            <p class="client-lead">
              Blog strategy and rebrand<br class="d-none d-md-block"> for Art Van Furniture.
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-wolfs-grayscale.jpg')" alt="">
      </div>
    </div>
  </header>

  <section class="wp-block-carney-section">
    <nav class="wp-block-carney-section-nav">
      <ul class="nav">
        <li class="nav-item active"><a class="nav-link active" href="#philips">Philips</a></li>
        <li class="nav-item"><a class="nav-link" href="#wolfs">Wolf’s</a></li>
        <li class="nav-item"><a class="nav-link" href="#astrobotic">Astrobotic</a></li>
        <li class="nav-item"><a class="nav-link" href="#cmoa">CMOA</a></li>
      </ul>
    </nav>

    <div class="wp-block-carney-steps">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1 py-md">
            <h2 class="h4 mb-5">Building a content marketing strategy lorem ipsum dolor sit amet.</h2>
            <p class="lead">Integer in commodo ligula. Nam rhoncus ipsum tempus, porttitor risus ut, luctus purus.
            Proin orci risus, accumsan non semper quis, dictum sed libero.</p>
          </div>
          <ul class="wp-block-carney-steps__list col-md-10 offset-md-1 list-unstyled">
            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Objectives</small>
                  Why are we producing content?
                </h3>
                <p>Clearly define the objectives of creating and distributing content. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Audience</small>
                  Who are we talking to?
                </h3>
                <p>Resaerch into client personas, audience. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Style</small>
                  How are we going to say it?
                </h3>
                <p>Define a consistent voice and tone for copywriting and a complementary visual vocabulary for the look and feel of the blog. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Content</small>
                  What are we saying?
                </h3>
                <p>What types of content will resonate with our audience and contribute toward our objectives? Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Frequency</small>
                  When should we say it?
                </h3>
                <p>Establish a frequency for posting and create a shared content creation calendar for writing, proofreading, and scheduling. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Distribution</small>
                  Where should we say it?
                </h3>
                <p>Build a distribution strategy across multiple channels such as email and social media platforms. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend</p>
              </div>
            </li>

            <li class="wp-block-carney-steps__step row row--lg-pad py-md">
              <div class="wp-block-carney-steps__step-image-col col-3">
                <img src="@asset('images/svg/icon-step-image.svg')" alt="" class="wp-block-carney-steps__step-image">
              </div>
              <div class="wp-block-carney-steps__step-content col-9 indent-md">
                <h3 class="wp-block-carney-steps__step-title h5 mb-3 no-indent">
                  <small class="mb-2">Analytics</small>
                  What can we learn?
                </h3>
                <p>Analyze. Adjust. Repeat. Evaluate performance based on Google Analytics and social data. Adjust the content strategy based on the goals and measured results. Quisque blandit quis leo vel tincidunt. Fusce pulvinar leo ac ex sodales luctus. Fusce auctor quam id magna commodo, vitae facilisis augue eleifend.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
</article>
