import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    // set up about us collage
    const $aboutCollage = $('.wp-block-carney-collage--about-us');
    if ($aboutCollage.length) {
      let controller = new ScrollMagic.Controller();
      let aboutScene;
      let aboutTween;
      let aboutCollageTimeout;

      const about1 = $('.wp-block-carney-collage__item img:eq(0)');
      const about2 = $('.wp-block-carney-collage__item img:eq(1)');

      $(window)
        .on('resize.carney load.carney', () => {
          if (aboutCollageTimeout) {
            clearTimeout(aboutCollageTimeout);
          }

          aboutCollageTimeout = setTimeout(() => {
            const isMobile = $(window).width() <= 991;

            controller.destroy(true);
            controller = new ScrollMagic.Controller();
            aboutTween = new TimelineMax();
            aboutScene = new ScrollMagic.Scene({
              triggerElement: $aboutCollage[0],
              triggerHook: isMobile ? 0.75 : 1,
              duration: isMobile ? $aboutCollage.outerHeight() : $(window).height()
            });

            aboutTween.set([about1, about2], {
              opacity: 1,
              clearProps: 'transform, opacity'
            });

            if (isMobile) {
              // set tweens for mobile
              aboutTween.add(TweenMax.from(about1, 1, { opacity: 0, yPercent: 500 }), 0.5);
              aboutTween.add(TweenMax.from(about2, 1, { opacity: 0, yPercent: 300 }), 1.2);
            } else {
              // set tweens for desktop
              aboutTween.add(TweenMax.from(about1, 1, { opacity: 0, yPercent: 250 }), 0.5);
              aboutTween.add(TweenMax.from(about2, 1, { opacity: 0, yPercent: 150 }), 1.2);
            }

            aboutScene.setTween(aboutTween).addTo(controller);
          }, 100);
        })
        .trigger('resize.carney');
    }
  },
  finalize() {
    // JavaScript to be fired on the about page, after the init JS
  }
};
