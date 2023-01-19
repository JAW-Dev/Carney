import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    // set CMOA color pallete animation
    const $colors = $('.cmoa-colors__color');
    const $split = $colors.closest('.wp-block-carney-split');
    const $imageWrap = $split.find('.wp-block-carney-split__image-wrap');
    if ($split.length) {
      let controller = new ScrollMagic.Controller();
      let colorScene;
      let colorTween;
      let colorTimeout;

      $(window)
        .on('resize.carney load.carney', () => {
          if (colorTimeout) {
            clearTimeout(colorTimeout);
          }

          colorTimeout = setTimeout(() => {
            const isMobile = $(window).width() <= 991;

            controller.destroy(true);
            controller = new ScrollMagic.Controller();
            colorScene = new ScrollMagic.Scene({
              triggerElement: $colors[0],
              triggerHook: isMobile ? 0.8 : 1,
              duration: $imageWrap.outerHeight()
            });
            colorTween = new TimelineMax()
              .set($colors, { opacity: 0, x: $colors.parent().outerWidth() })
              .add(TweenMax.staggerTo($colors, 1, { x: 0, opacity: 1 }, 0.25));

            colorScene.setTween(colorTween).addTo(controller);
          }, 100);
        })
        .trigger('resize.carney');
    }
  }
};
