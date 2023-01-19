import 'select2';
import Headroom from 'headroom.js';
import swipeScroll from '../util/swipeScroll';

export default {
  init() {
    // JavaScript to be fired on all pages

    // init peekaboo menu
    $('.site-header, #wpadminbar').each((index, menu) => {
      const headroom = new Headroom(menu, {
        offset: $(menu).height(),
        tolerance: 5
      });
      headroom.init();
      $(window).scroll();
    });

    // init mobile menu
    $('.site-nav-toggle ').click(function toggleNav() {
      $(this).toggleClass('is-active');
      $('body').toggleClass('nav-open');
      $('.site-nav').toggleClass('is-active');
    });

    // init random hero
    $('.wp-block-carney-hero--random[data-random]').each((index, hero) => {
      const $hero = $(hero);
      const sources = $hero.data('random');
      const { src, style } = sources[Math.floor(Math.random() * sources.length)];
      $hero
        .find('.wp-block-carney-hero__media-wrap > img')
        .attr('src', src)
        .attr('style', style);
    });

    // init scrollspy
    $('body').scrollspy({
      target: '.wp-block-carney-section-nav',
      offset: 100
    });

    $(window).on('activate.bs.scrollspy', () => {
      const $activeLink = $('.nav-link.active');
      $activeLink
        .each((index, link) => {
          const $link = $(link);
          const $target = $($link.attr('href'));
          $target.addClass('active');
        })
        .parent('.nav-item')
        .addClass('active')
        .siblings()
        .removeClass('active')
        .find('.nav-link')
        .each((index, link) => {
          const $link = $(link);
          const $target = $($link.attr('href'));
          $target.removeClass('active');
        });
    });

    // init scroll-to-anchor
    const scrollToAnchor = $target => {
      if ($target.length) {
        const duration = $target.data('scroll-duration') || 1000;
        $('html, body').animate(
          {
            scrollTop: $target.offset().top
          },
          duration
        );
      }
    };

    $('a[href^="#"], [data-action="scroll"]')
      .not('.no-scroll')
      .click(event => {
        const $trigger = $(event.currentTarget);
        const target = $trigger.data('target') || $trigger.attr('href');
        const $target = target ? $(target) : $trigger.closest('section').next();
        if ($target.length) {
          event.preventDefault();
          scrollToAnchor($target);
        }
      });

    const { hash } = document.location;
    if (hash) {
      window.scrollTo(0, 0);
      $(window).load(() => {
        scrollToAnchor($(hash));
      });
    }

    // init horizontal mouse/touch swipe scroll
    swipeScroll();

    // init fader
    $('.wp-block-carney-fader').each((index, fader) => {
      const $fader = $(fader);
      const $items = $fader.find('.wp-block-carney-fader__item');
      const delay = $fader.data('delay') || 5000;

      // set minHeight of fader based on tallest item
      $(window)
        .on('load.carney resize.carney', () => {
          const minHeight = $items.get().reduce((height, item) => {
            const itemHeight = $(item).height();
            return itemHeight > height ? itemHeight : height;
          }, 0);
          $fader.css({ minHeight });
        })
        .resize();

      // show a new item every 5 seconds
      $fader.addClass('active');
      setInterval(() => {
        // hide current item and get index
        const activeIndex = $items
          .filter('.active')
          .removeClass('active')
          .index();

        // show next item after a delay
        const nextIndex = activeIndex === $items.length - 1 ? 0 : activeIndex + 1;
        setTimeout(() => {
          $items.eq(nextIndex).addClass('active');
        }, 250);
      }, delay);
    });

    // init select navs
    $('.select-nav').change(event => {
      const val = event.target.value;
      if (val) {
        document.location = val;
      }
    });

    // init select2
    $('select.styled-select').select2({
      width: '100%'
    });

    // ensure heros are large enough for fixed content
    $('.wp-block-carney-hero .container.position-fixed').each((index, container) => {
      const $container = $(container);
      const $hero = $container.closest('.wp-block-carney-hero');
      const $mediaWrap = $hero.find('.wp-block-carney-hero__media-wrap');

      $(window)
        .on('load.carney resize.carney', () => {
          // remove fixed positioning to get true height
          const heroFixed = $hero.hasClass('wp-block-carney-hero--fixed');
          $hero.removeClass('wp-block-carney-hero--fixed').css({
            height: '',
            minHeight: '',
            maxHeight: ''
          });
          $hero.add($mediaWrap).addClass('no-transition');
          $container.removeClass('position-fixed');

          // set true height
          const heroHeight = $hero.outerHeight();
          const { paddingTop, paddingBottom } = window.getComputedStyle($hero[0]);
          const newHeight =
            $container.outerHeight() +
            parseFloat(paddingTop.replace('px', '')) +
            parseFloat(paddingBottom.replace('px', ''));
          $hero.add($mediaWrap).css({
            height: newHeight > heroHeight ? newHeight : heroHeight,
            minHeight: '0',
            maxHeight: 'none'
          });

          // restore fixed positioning
          $container.addClass('position-fixed');
          if (heroFixed) {
            $hero.addClass('wp-block-carney-hero--fixed');
            // ... unless hero is taller than the viewport
            $mediaWrap.toggleClass('position-absolute', $hero.outerHeight() > $(window).height());
            $container.toggleClass('position-fixed', $hero.outerHeight() <= $(window).height());
          }
        })
        .trigger('resize');
    });

    // send MailChimp sign-up forms via AJAX
    $('.sign-up-form--ajax').each((index, form) => {
      const $form = $(form);
      const $result = $form.find('.sign-up-form__result');
      $form.submit(event => {
        event.preventDefault();
        $.get({
          url: `${form.action}&c=?`,
          data: $form.serialize(),
          dataType: 'jsonp',
          success: data => {
            const { result, msg } = data;
            if (result === 'error') {
              // show error message
              $form.removeClass('has-success has-error');
              const error =
                msg === '0 - Please enter a value' ? 'Please enter your email address.' : msg;
              $result.html(`<strong>Error.</strong> ${error}`);
              $form.addClass('has-error');
            } else {
              // show success message
              $form.removeClass('has-success has-error');
              $result.html("<strong>Thanks!</strong> We'll send you updates by email.");
              $form.addClass('has-success');
              form.reset();
            }
          }
        });
      });
    });

    // reset forms when parent modal is close
    $('.modal').on('hidden.bs.modal', event => {
      const $forms = $(event.target).find('form');
      $forms
        .find(
          '.wpcf7-not-valid-tip, .wpcf7-response-output, .wpcf7-validation-errors, .wpcf7-acceptance-missing'
        )
        .hide();
      $forms.each((index, form) => {
        form.reset();
      });
    });

    $('.testimonial-block [data-toggle]').click(function toggleTestimonial() {
      const label = $(this).text() === 'Read more' ? 'Hide' : 'Read more';
      $(this)
        .text(label)
        .closest('.testimonial-block')
        .toggleClass('testimonial-block--open')
        .find('.testimonial-block__details')
        .slideToggle();
    });

    // random number between 0 and max (exclusive)
    const getRandom = max => Math.floor(Math.random() * Math.floor(max));

    const testimonials = $('.testimonial-group .row');
    testimonials.eq(getRandom(testimonials.length)).removeClass('d-none');
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  }
};
