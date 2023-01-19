<h1 class="sr-only">Astrobotic</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm wp-block-carney-hero--align-bottom">
      <div class="container d-flex flex-column align-items-start position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <img src="@asset('images/logo-astrobotic-white.png')" alt="Logo" class="order-first mb-4" width="180" height="104">
            <p class="client-lead">
              Complete website redesign for Google XPrize competitor.
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-astrobotic-grayscale.jpg')" alt="">
      </div>
    </div>

    <div class="wp-block-carney-client-meta bg-dark">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="row">
              <div class="col-md-3 wp-block-carney-client-meta__industry-col">
                <h3 class="text-purple-lighter">Industry</h3>
                <ul class="list-inline list-inline--separator">
                  <li class="list-inline-item">Technology</li>
                </ul>
              </div>
              <div class="col-md-9 wp-block-carney-client-meta__capabilities-col">
                <h3 class="text-purple-lighter">Capabilities</h3>
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
      </div>
    </div>
  </header>

  <section class="wp-block-carney-section">
    <div class="wp-block-carney-indent py-sm">
      <div class="container">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <div class="row row--lg-pad">
              <div class="col-12 pb-5">
                <p class="lead lead--larger">Astrobotic flies items into space for individuals, companies, universities, and even countries. Those items need to communicate with all of their counterparts on the ground. So, how do you bring concepts like “translunar injection” down to earth?</p>
              </div>
              <div class="col-md-6">
                <div class="lead">
                  <p>First, we needed to immerse ourselves in the world of commercial space travel.</p>
                  <p>We took a field trip to the Astrobotic labs to see their rover on a simulated lunar landscape. We read up on the nitty-gritty of space travel. We followed space industry news. And we asked an astronomical number of questions.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="lead">
                  <p>Then, it was time to play the soundtrack to <a href="https://www.warnerbros.com/gravity/index">Gravity</a>, roll up our sleeves, and start crafting content.</p>
                  <p>From copy to design elements, we needed to marry clarity and simplicity with credibility and accuracy. Custom icons, animations, diagrams, and step-by-step planning tools help convey Astrobotic’s services to everyone, from engineers to armchair space enthusiasts.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-collage wp-block-carney-collage--client-astrobotic py-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-5 wp-block-carney-collage__col d-md-flex flex-row align-items-stretch">
            <div class="row d-md-flex flex-row align-items-stretch">
              <div class="col-8 offset-2 col-sm-4 offset-sm-0 col-md-12 pt-sm py-sm-5 wp-block-carney-collage__item d-flex align-items-center justify-content-center">
                <img src="@asset('images/graphic-client-astrobotic-1.gif')" alt="">
              </div>
              <div class="col-8 offset-2 col-sm-4 offset-sm-0 col-md-12 py-5 wp-block-carney-collage__item d-flex align-items-center justify-content-center">
                <img src="@asset('images/graphic-client-astrobotic-2.gif')" alt="">
              </div>
              <div class="col-8 offset-2 col-sm-4 offset-sm-0 col-md-12 py-5 wp-block-carney-collage__item d-flex align-items-center justify-content-center">
                <img src="@asset('images/graphic-client-astrobotic-3.gif')" alt="">
              </div>
            </div>
          </div>
          <div class="col-8 offset-2 col-md-7 offset-md-0 order-first order-md-last wp-block-carney-collage__item d-flex align-items-center justify-content-center">
            <div class="video-wrap">
              <video src="@asset('videos/astrobotic-griffin-ipad.mp4')" autoplay muted loop playsinline></video>
              <img src="@asset('images/graphic-client-astrobotic-video-mask.png')" class="video-mask" width="1650" height="2120" />
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

    <div class="wp-block-carney-indent mt-md pb-1">
      <div class="container indent-md">
        <h2 class="h3 no-indent">
          <small class="text-purple-lighter">The Takeaway</small>
          We are mediators, making the complex <span class="hyphenate">understandable</span>
        </h2>

        <p class="lead lead--larger mt-sm mb-5">
          Even if your business isn’t rocket science, it’s full of insider knowledge often lost on the general public. Is your message clearly expressed? Having an objective set of eyes and ears can help.
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
