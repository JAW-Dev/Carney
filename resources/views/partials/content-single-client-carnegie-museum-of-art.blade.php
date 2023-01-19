<h1 class="sr-only">Carnegie Museum of Art</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm wp-block-carney-hero--align-bottom">
      <div class="container d-flex flex-column align-items-start position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <img src="@asset('images/logo-cmoa-white.png')" alt="Logo" class="order-first mb-4" width="105" height="105">
            <p class="client-lead">
              Complete redesign for modern art museum website and micro-sites.
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-cmoa-grayscale.jpg')" alt="">
      </div>
    </div>

    <div class="wp-block-carney-client-meta bg-dark">
      <div class="container indent-md">
        <div class="row">
          <div class="col-md-3 wp-block-carney-client-meta__industry-col">
            <h3 class="text-red-light">Industry</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Arts &amp; Culture</li>
            </ul>
          </div>
          <div class="col-md-9 wp-block-carney-client-meta__capabilities-col">
            <h3 class="text-red-light">Capabilities</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Strategy</li><!--
              --><li class="list-inline-item">Design</li><!--
              --><li class="list-inline-item">Content</li><!--
              --><li class="list-inline-item">Development</li>
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
                <p class="lead lead--larger mb-5">Every organization should be concerned about making its online content accessible. We helped the Carnegie Museum of Art update their website to a truly accessible, mobile-first experience.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="wp-block-carney-section">
    <div class="wp-block-carney-split wp-block-carney-split--image-right py-sm">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col">
            <h2 class="h3 no-indent">Lightning Fast Collections Search</h2>
            <p class="lead">CMOA’s collection contains more than <strong>88,500 works of art</strong>, from architecture to weaponry.</p>
            <p class="lead">To even start an update to the online search tool meant writing custom code to interpret legacy data and create a new database.</p>
            <p class="lead">That became the foundation for an ultra-fast and flexible search engine that serves up results in <strong>100 milliseconds or less.</strong></p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col pt-3 pt-md-0">
            <div class="wp-block-carney-split__image-wrap">
              <img src="@asset('images/image-client-cmoa-2.jpg')" alt="">
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

    <div class="wp-block-carney-split wp-block-carney-split--image-left py-sm">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col">
            <h2 class="h3 no-indent">Accessibility First</h2>
            <p class="lead">CMOA has a strong commitment to making its brick-and-mortar facilities accessible to visitors with auditory, speech, cognitive, and neurological disabilities.</p>
            <p class="lead">That same commitment needed to apply to the website, not as an afterthought, but as a guiding principle. Accessibility standards informed everything, from information architecture to color selection to coding.</p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col pt-3 pt-md-0">
            <div class="wp-block-carney-split__image-wrap">
              <img src="@asset('images/photo-client-cmoa-3.jpg')" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-split wp-block-carney-split--image-right py-sm">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col">
            <h2 class="h3 no-indent">Color Selection</h2>
            <p class="lead">When picking accent colors to complement the established CMOA black, white, and red, we turned to their collection for inspiration. Van Gogh’s “Wheat Fields after the Rain” provided a starting point. To meet accessibility standards, we measured the contrast of black and white text against these accent colors and refined accordingly.</p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col pt-3 pt-md-0">
            <div class="wp-block-carney-split__image-wrap">
              <div class="wp-block-carney-split__image-caption wp-block-carney-split__image-caption--cmoa-colors">
                <div class="cmoa-colors">
                  <div style="width: 5rem; height: 5rem; background-color: #bf0d3e; padding: 0.5rem;" class="cmoa-colors__color mr-lg-sm"><strong>CMOA Color</strong></div>
                  <div style="width: 5rem; height: 5rem; background-color: #e4bb6b; padding: 0.5rem;" class="cmoa-colors__color ml-lg-3"><strong>Accent Colors</strong></div>
                  <div style="width: 5rem; height: 5rem; background-color: #48536d; padding: 0.5rem;" class="cmoa-colors__color"></div>
                  <div style="width: 5rem; height: 5rem; background-color: #8ecbc3; padding: 0.5rem;" class="cmoa-colors__color"></div>
                  <div style="width: 5rem; height: 5rem; background-color: #555555; padding: 0.5rem;" class="cmoa-colors__color"></div>
                </div>
              </div>
              <img src="@asset('images/image-client-cmoa-1.jpg')" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-indent pt-sm pb-1">
      <div class="container indent-md">
        <h2 class="h3 no-indent">
          <small class="text-red-light">The Takeaway</small>
          Arts, culture, and education. At your fingertips.
        </h2>

        <p class="lead lead--larger mt-5 mb-5">
          Supporting programs for an audience ranging from toddlers to senior citizens. Meeting detailed cataloging and rights-management standards. Cultural and educational institutions have a wide range of needs and requirements to navigate. We can help your institution maintain a digital presence in a mobile-first world.
        </p>

        <p><a href="/hire-us/" class="btn btn-primary btn--gradient">Get in Touch</a></p>
      </div>
    </div>

    {{-- @php(the_content()) --}}
  </section>

  <footer class="wp-block-carney-section">
    {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
  </footer>
</article>

@include('partials.related-clients')
