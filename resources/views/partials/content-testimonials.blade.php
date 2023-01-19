<div class="container testimonial-group">
  @while($testimonials->have_posts()) @php($testimonials->the_post())
    <div class="row justify-content-center d-none">
      <div class="col-10 col-md-7">
        <div class="testimonial-block__image">
          {!! the_post_thumbnail('medium') !!}
        </div>
        <div class="pt-3 pb-4 testimonial-block">
          <div class="testimonial-block__copy">
            {!! the_excerpt() !!}
          </div>
          <div class="testimonial-block__details">
            {!! the_content() !!}
          </div>
          <p class="pb-1"><a role="button" data-toggle="true" tabindex="0">Read more</a></p>
          <span class="testimonial-block__author"><strong>{!! the_title() !!}</strong> | {!! the_field('position') !!}, {!! the_field('company') !!}</span>
        </div>
      </div>
    </div>
  @endwhile
  @php(wp_reset_postdata())
</div>
