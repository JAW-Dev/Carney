<h1 class="sr-only">Duolingo</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm wp-block-carney-hero--align-bottom">
      <div class="container d-flex flex-column align-items-start position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <img src="@asset('images/logo-duolingo-white.png')" alt="Logo" class="order-first mb-4" width="186" height="47">
            <p class="client-lead">
              Campaign creation and ad management for the number one free language learning app
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-duolingo-grayscale.jpg')" alt="">
      </div>
    </div>

    <div class="wp-block-carney-client-meta bg-dark">
      <div class="container indent-md">
        <div class="row">
          <div class="col-md-3 wp-block-carney-client-meta__industry-col">
            <h3 class="text-green-light-alt">Industry</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Education</li>
            </ul>
          </div>
          <div class="col-md-9 wp-block-carney-client-meta__capabilities-col">
            <h3 class="text-green-light-alt">Capabilities</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Strategy</li><!--
            --><li class="list-inline-item">Digital Advertising</li><!--
            --><li class="list-inline-item">Content Creation</li><!--
            --><li class="list-inline-item">Social Media</li><!--
            --><li class="list-inline-item">Analytics</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="wp-block-carney-section">
    <div class="wp-block-carney-indent py-sm">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="row row--lg-pad">
              <div class="col-12">
                <p class="lead lead--larger mb-5">People are scrolling. People are streaming. How do you get them to stop, install, and start learning? Duolingo was looking for a strategic partner to help drive installs by motivated learners who were likely to become daily users.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="wp-block-carney-section duo-phone pb-sm">
    <div class="wp-block-carney-split">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col">
            <h2 class="h3 no-indent">Campaign Strategy and Creative</h2>
            <p class="lead">From capturing the attention of language-learners during the New Years’ resolution season, to conveying the appeal of Duolingo’s bite-sized lessons, we helped Duolingo plan and execute seasonal and evergreen campaigns.</p>
            <p class="lead">Animations captured attention and showcased the app as well as Duolingo’s much-loved, slightly pushy mascot, Duo.</p>
          </div>
          <div class="col-lg-6 py-3 pt-md-0">
            <div class="phone">
              <div class="phone__video">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=" data-src="@asset('images/image-duo-jump.gif')" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if($testimonials && $testimonials->have_posts())
      <div class="my-md">
        @include('partials.content-testimonials')
      </div>
    @endif

    <div class="wp-block-carney-split pb-md">
      <div class="wp-block-carney-split wp-block-carney-split--image-left">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 indent-md wp-block-carney-split__text-col">
              <h2 class="h3 no-indent">Parlez-vous paid acquisit&shy;ion?</h2>
              <p class="lead">As a language learning company with global reach, localized translations of ads are a must. We provided support for advertising in multiple languages and character sets to help drive installs worldwide.</p>
            </div>

            <div class="col-lg-6 wp-block-carney-split__image-col">
              <div class="wp-block-carney-split__image-wrap">
                <div class="wp-block-carney-image-comparison d-flex flex-column align-items-center align-items-lg-end">
                  <div class="wp-block-carney-image-comparison__inner wp-block-carney-image-comparison__inner--full-height">
                    <img src="@asset('images/image-duoburger-ko.jpg')" alt="">
                    <img src="@asset('images/image-duoburger-pt.jpg')" alt="">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="wp-block-carney-section py-sm">
    <div class="wp-block-carney-split">
      <div class="container">
        <div class="row">
          <div class="col-lg-7 indent-md wp-block-carney-split__text-col">
            <h2 class="h3 no-indent">Engaging Social Media Content</h2>
            <p class="lead">To support Duolingo’s goal of producing engaging social content we conceptualized and executed an animated Instagram story featuring Duo.</p>
            <p class="lead">The story quickly prompted over 500 people to click through and install the app.</p>
          </div>
          <div class="col-lg-5 text-center">
            <video id="duo-story" muted style="max-width: 100%; max-height: 80vh">
              <source src="@asset('videos/duo-insta-story.mp4')" type="video/mp4">
            </video>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="wp-block-carney-section py-sm">
    <div class="wp-block-carney-indent pt-sm pb-1">
      <div class="container indent-md">
        <h2 class="h3 no-indent">
          <small class="text-green-light-alt">The Takeaway</small>
          Create for people, not algorithms.
        </h2>

        <p class="lead lead--larger mt-5 mb-5">Consistently cooking up content that is relevant, trackable, and on brand is a tall order for many companies. Although clicks and views fill reports, they don't necessarily bring you closer to your goals. The struggle is real! Carney can help.</p>

        <p><a href="/hire-us/" class="btn btn-primary btn--gradient">Get in Touch</a></p>
      </div>
    </div>

    {{-- @php(the_content()) --}}
  </section>

  <footer class="wp-block-carney-section">
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
</article>

<script src="https://polyfill.io/v3/polyfill.min.js?features=IntersectionObserver"></script>

@include('partials.related-clients')
