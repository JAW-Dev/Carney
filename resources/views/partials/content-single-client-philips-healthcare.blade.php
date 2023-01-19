<h1 class="sr-only">Philips Healthcare</h1>

<article @php(post_class())>
  <header class="wp-block-carney-section">
    <div class="wp-block-carney-hero wp-block-carney-hero--fixed wp-block-carney-hero--fade wp-block-carney-hero--sm wp-block-carney-hero--align-bottom">
      <div class="container d-flex flex-column align-items-start position-fixed">
        <div class="row">
          <div class="col-md-10 offset-md-1">
            <img src="@asset('images/logo-philips-white.png')" alt="Logo" class="order-first mb-4" width="84" height="108">
            <p class="client-lead">
              Strategic planning and innovation with a leading health technology company.
            </p>
          </div>
        </div>
      </div>
      <div class="wp-block-carney-hero__media-wrap">
        <img src="@asset('images/hero-philips-grayscale.jpg')" alt="">
      </div>
    </div>

    <div class="wp-block-carney-client-meta bg-dark">
      <div class="container indent-md">
        <div class="row">
          <div class="col-12 col-md-3 wp-block-carney-client-meta__industry-col">
            <h3 class="text-blue-light">Industry</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Healthcare</li>
            </ul>
          </div>
          <div class="col-12 col-md-9 wp-block-carney-client-meta__capabilities-col">
            <h3 class="text-blue-light">Capabilities</h3>
            <ul class="list-inline list-inline--separator">
              <li class="list-inline-item">Strategy</li><!--
              --><li class="list-inline-item">Design</li><!--
              --><li class="list-inline-item">Content</li><!--
              --><li class="list-inline-item">Development</li><!--
              --><li class="list-inline-item">Marketing</li><!--
              --><li class="list-inline-item">Analytics</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </header>

  <section class="wp-block-carney-section">
    <div class="wp-block-carney-split wp-block-carney-split--image-right pt-md mb-md">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col mb-4">
            <h2 class="h3 no-indent hyphenate">Product innovation &amp; strategy</h2>
            <p class="lead">Targeted surveys, questionnaires, A/B testing, and research into the consumer mindset helps us help Philips. Roundtable discussions between Carney and the Philips team combine business objectives with consumer-driven insights. Our collaboration has helped avoid costly communication pitfalls and informed the next generation of products and services.</p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col">
            <div class="wp-block-carney-split__image-wrap">
              <img src="@asset('images/photo-client-philips-1.png')" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-split wp-block-carney-split--image-left my-md">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col mb-4">
            <h2 class="h3 no-indent">B2B Sales Tools</h2>
            <p class="lead">Your salespeople are on the front lines and reporting back on challenges they face making a sale. We’ve helped Philips create a variety of B2B sales tools to quickly and distinctly demonstrate the advantage of Philips products and services.</p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col">
            <div class="wp-block-carney-split__image-wrap wp-block-carney-split__image-wrap--landscape">
              <img src="@asset('images/photo-client-philips-2.png')" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wp-block-carney-split wp-block-carney-split--image-right my-md">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 indent-md wp-block-carney-split__text-col mb-4">
            <h2 class="h3 no-indent hyphenate">Internal communication</h2>
            <p class="lead">Moving company initiatives off the drawing board and into production can be a challenge. When an idea is daring enough, complex enough, important enough, a simple PowerPoint deck isn’t always enough. We’ve helped Philips design and wordsmith internal pitch decks to secure buy-in from internal stakeholders and decision makers.</p>
          </div>
          <div class="col-lg-6 wp-block-carney-split__image-col">
            <div class="wp-block-carney-split__image-wrap">
              <img src="@asset('images/photo-client-philips-3.jpg')" alt="">
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

    <div class="wp-block-carney-indent pt-3 pb-1">
      <div class="container indent-md">
        <h2 class="h3 no-indent">
          <small class="text-blue-light">The Takeaway</small>
          Done right. Done on time.
        </h2>

        <p class="lead lead--larger mt-5 mb-5">
          Even at large corporations, internal resources can be limited. Outside perspectives can clarify your message. We’ve helped companies like Philips with tight turn-arounds and strategic planning. We commit to getting it done right and on time.
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
