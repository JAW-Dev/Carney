<h1 class="sr-only">Get in touch</h1>

<article class="wp-block-carney-section">
  <div class="wp-block-carney-post-preview py-lg-lg">
    <div class="container order-last order-lg-first">
      <div class="row">
        <div class="col-lg-11 wp-block-carney-post-preview__content-col">
          <header>
            <h2 class="h4 entry-title mb-3">Want to have some&nbsp;fun?</h2>
          </header>
          <div class="entry-summary lead mb-sm ml-md-sm">
            <p>Better than just a walk in the park. We're talking trampoline park levels of fun when we work together.</p>
            <p>As an agency partner, we work closely alongside our clients. We’ll hear you, and we’ll make sure your brand is heard – not just seen.</p>
            <p>Our team's energy is boundless when it comes to delivering results. Hold on tight.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid wp-block-carney-post-preview__image-container">
      <div class="row">
        <div class="col-lg-5 offset-lg-7 wp-block-carney-post-preview__image-col" style="overflow: hidden">
          <img data-src="@asset('images/dog-trampoline.gif')" alt="dog on trampoline!" class="ie-gif" />
          <video src="@asset('videos/dog-trampoline.mp4')" preload="none" muted loop autoplay playsinline class="ie-video"></video>
        </div>
      </div>
    </div>
  </div>
</article>

<article @php(post_class())>
  <section class="wp-block-carney-section py-md-sm">
    <div class="container">
      @php(the_content())
    </div>

    <div class="mt-md">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-10 col-md-7">
            <img src="@asset('images/testimonials/cammie-dunaway.jpg')" alt="Cammie Dunaway portrait" class="testimonial-block__image">
            <div class="pt-3 pb-4 testimonial-block">
              <p class="testimonial-block__copy">“If you are looking for a smart, creative, hardworking bunch of digital experts, you can find them right here in Pittsburgh.”</p>
              <div class="testimonial-block__details">
                <p class="py-1">Duolingo, the world's most popular language learning app, turned to Carney to grow our user base during the critical 2019 New Year's time frame. The Carney team delivered a powerful concept, strong creative and a robust media plan. Then they executed the plan with excellence, constantly optimizing to ensure that we got the most for our money.</p>
                <p class="py-1">I have rarely seen an agency so focused on making sure they understand a client's goals and then driving to exceed them. Together we delivered record 30% growth and they ensured that I was one happy client.</p>
              </div>
              <p class="pb-1"><a role="button" data-toggle="true" tabindex="0">Read more</a></p>
              <span class="testimonial-block__author"><strong>Cammie Dunaway</strong> | CMO, Duolingo</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</article>

<?php
$related_posts = get_posts([
            'post_type' => 'client',
            'posts_per_page' => 4,
            'exclude' => $post->ID,
            'orderby' => 'rand'
        ]);
        ?>

<section class="wp-block-carney-section">
  <div class="wp-block-carney-related-clients pt-lg">
    <div class="container">
      <h2 class="dash mb-sm"><small>Case Studies</small></h2>
    </div>
    <div class="wp-block-carney-related-clients__wrap pb-md" data-swipe-item=".wp-block-carney-related-clients__list li">
      <div class="container">
        <ul class="wp-block-carney-related-clients__list">
          <?php global $post; ?>
          @foreach ($related_posts as $post) @php(setup_postdata($post))
            <li>
              <a href="{{ get_the_permalink() }}" class="btn btn-block btn-primary btn--gradient btn--mega btn--mega--with-image">
                <div class="btn__text-wrap">
                  <?php
                    $terms = @array_map(function($term) {
                      return $term->name;
                    }, get_the_terms($post, 'capability'));
                  ?>
                  <small style="white-space: normal">{{ @implode(' &bull; ', $terms) }}</small>
                  <span class="btn__text">Discover →</span>
                </div>
                <div class="btn__image-wrap">
                  {!! get_the_post_thumbnail(null, 'medium_large') !!}
                </div>
              </a>
            </li>
          @endforeach
          @php(wp_reset_postdata())
        </ul>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  document.addEventListener('wpcf7mailsent', function(event) {
    window.location.href = '/hire-us-thank-you/';
  }, false);
</script>
