import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    const controller = new ScrollMagic.Controller();
    const $main = $('main.main');
    const $heros = $main.find('.wp-block-carney-hero');

    $heros.css({ opacity: 0.15 }).each((index, hero) => {
      const isMobile = $(window).width() < 640;
      const $hero = $(hero);
      const $section = $hero.closest('.wp-block-carney-section');
      const duration = isMobile ? $(window).height() : $section.height();

      const scene1 = new ScrollMagic.Scene({
        triggerElement: hero,
        triggerHook: 1,
        duration
      });

      const scene2 = new ScrollMagic.Scene({
        triggerElement: hero,
        triggerHook: 0,
        duration
      });

      const timeline1 = new TimelineMax();
      const timeline2 = new TimelineMax();

      if (!isMobile) {
        timeline1.add(TweenMax.to(hero, 1, { opacity: 0.15 }));
      }
      timeline1.add(TweenMax.to(hero, 1, { opacity: 1 }));

      timeline2.add(TweenMax.to(hero, 1, { opacity: 1 }));
      timeline2.add(TweenMax.to(hero, 1, { opacity: 1 }));
      timeline2.add(TweenMax.to(hero, 1, { opacity: 0.15 }));

      scene1.setTween(timeline1).addTo(controller);
      scene2.setTween(timeline2).addTo(controller);
    });
  }
};
