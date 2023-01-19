import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    // set Seneca Creek photo animations
    const $squares = $('.wp-block-carney-collage__item:not(:first-child) img');
    const $largeImage = $squares.parent().siblings(':first-child').find('img');
    const $instaShots = $('.wp-block-carney-split--seneca-influencers .wp-block-carney-split__image-wrap img:not(:first-child)');
    const $instaContainer = $instaShots.parents('.wp-block-carney-split__image-wrap');

    let controller = new ScrollMagic.Controller();
    let senecaTimeout;

    let squareScene;
    let squareTween;

    let instaScene;
    let instaTween;

    $(window)
      .on('resize.carney load.carney', () => {
        if (senecaTimeout) {
          clearTimeout(senecaTimeout);
        }

        senecaTimeout = setTimeout(() => {
          const isMobile = $(window).width() <= 991;
          controller.destroy(true);
          controller = new ScrollMagic.Controller();

          if ($squares.length) {
            squareScene = new ScrollMagic.Scene({
              triggerElement: $largeImage[0],
              triggerHook: isMobile ? 0.5 : 0.25,
              duration: $largeImage.outerHeight(),
              offset: -$squares.outerHeight() * 0.75
            });
            squareTween = new TimelineMax()
              .set($squares, { opacity: 0, x: $largeImage.outerWidth() })
              .add(TweenMax.staggerTo($squares, 1, { x: 0, opacity: 1 }, 0.25));
            squareScene.setTween(squareTween).addTo(controller);
          }

          if ($instaShots.length) {
            instaScene = new ScrollMagic.Scene({
              triggerElement: $instaContainer[0],
              triggerHook: isMobile ? 0.5 : 0.25,
              duration: $instaContainer.outerHeight(),
              offset: -$instaContainer.outerHeight() * 0.25
            });
            instaTween = new TimelineMax()
              .set($instaShots, { yPercent: 50, opacity: 0 })
              .add(TweenMax.staggerFromTo($instaShots, 1, {
                yPercent: 50, opacity: 0
              }, {
                yPercent: -25, opacity: 1
              }, 0.25));
            instaScene.setTween(instaTween).addTo(controller);
          }
        }, 100);
      })
      .trigger('resize.carney');
  }
};
