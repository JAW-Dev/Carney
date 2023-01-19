<h1 class="sr-only">Dick's Sporting Goods</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm wp-block-carney-hero--align-bottom">
      <div class="container d-flex flex-column align-items-start position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <img src="@asset('images/logo-dicks-white.png')" alt="Logo" class="order-first mb-4" width="250" height="120">
            <p class="client-lead">
              Digital advertising with display ads and micro sites.
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-dicks-grayscale.jpg')" alt="">
      </div>
    </div>

    <div class="wp-block-carney-client-meta bg-dark">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="row">
              <div class="col-md-3 wp-block-carney-client-meta__industry-col">
                <h3 class="text-green-light">Industry</h3>
                <ul class="list-inline list-inline--separator">
                  <li class="list-inline-item">Retail</li>
                </ul>
              </div>
              <div class="col-md-9 wp-block-carney-client-meta__capabilities-col">
                <h3 class="text-green-light">Capabilities</h3>
                <ul class="list-inline list-inline--separator">
                  <li class="list-inline-item">Strategy</li><!--
                  --><li class="list-inline-item">Design</li><!--
                  --><li class="list-inline-item">Development</li>
                </ul>
              </div>
            </div>
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
              <div class="col-md-6">
                <p class="lead">
                  Dick’s Sporting Good is committed to serving and inspiring athletes of all types. From product launches to interviews with Olympic swimmers, Dick’s wanted to get engaging content and products in front of sports enthusiasts.
                </p>
              </div>
              <div class="col-md-6">
                <p class="lead">
                  We helped create animated digital ads to shine a spotlight on deals, from camping to golf equipment. Custom micro-sites for Gatorade and performance swimwear reinforced Dick’s position as the sports and fitness retailer of choice.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-collage wp-block-carney-collage--client-dicks pb-sm">
      <div class="container-fluid pl-md-0 pr-md-5">
        <div class="row">
          <div class="col-md-9 wp-block-carney-collage__item">
            <img src="@asset('images/photo-client-dicks-1.jpg')" alt="">
          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="col-6 col-md-12 pr-3 px-md-3 wp-block-carney-collage__item">
                <img src="@asset('images/photo-client-dicks-2.jpg')" alt="">
              </div>
              <div class="col-6 col-md-12 pl-3 px-md-3 wp-block-carney-collage__item">
                <img src="@asset('images/photo-client-dicks-3.jpg')" alt="">
              </div>
              <div class="col-12 wp-block-carney-collage__item wp-block-carney-collage__item--last">
                <img src="@asset('images/photo-client-dicks-4.jpg')" alt="" class="mb-auto">
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

    <div class="wp-block-carney-indent mt-sm pb-1">
      <div class="container indent-md">
        <h2 class="h3 no-indent">
          <small class="text-green-light">The Takeaway</small>
          Quality retail campaigns.
        </h2>

        <p class="lead lead--larger mt-sm mb-5">
          In the world of retail, new product launches and special promotions roll in faster than your internal team can keep up with. You need to get quality ads out on time. You need custom landing pages and micro-sites that comply with existing branding and technical specs. Carney has mastered those needs.
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
