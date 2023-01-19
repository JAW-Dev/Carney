import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    // set up dick's collage
    const controller = new ScrollMagic.Controller();
    const $dicksCollage = $('.wp-block-carney-collage--client-dicks');
    if ($dicksCollage.length) {
      const dicksScene = new ScrollMagic.Scene({
        triggerElement: $dicksCollage[0],
        triggerHook: 1.25,
        duration: 2000
      });

      const dicks1 = $('.wp-block-carney-collage__item img:eq(0)');
      const dicks2 = $('.wp-block-carney-collage__item img:eq(1)');
      const dicks3 = $('.wp-block-carney-collage__item img:eq(2)');
      const dicks4 = $('.wp-block-carney-collage__item img:eq(3)');
      const dicksTween = new TimelineMax()
        .add(TweenMax.from(dicks1, 1, { yPercent: '50' }), 0)
        .add(TweenMax.from(dicks2, 1, { yPercent: '25' }), 0)
        .add(TweenMax.from(dicks3, 1, { yPercent: '125' }), 0)
        .add(TweenMax.from(dicks4, 1, { yPercent: '75' }), 0);

      dicksScene.setTween(dicksTween).addTo(controller);
    }
  }
};
