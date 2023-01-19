import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    $(window).load(() => {
      const controller = new ScrollMagic.Controller();

      const el = $('.wp-block-carney-section:not(.capabilities-header)');
      const duration = $(window).width() <= 375 ? el.height() - 400 : el.height();
      const scene = new ScrollMagic.Scene({
        triggerElement: el[0],
        triggerHook: 0.7,
        duration
      });

      // magic numbers ahoy!
      const box = $('.capabilities-box');
      const strategy = $('#svg-strategy');
      const content = $('#svg-content');
      const design = $('#svg-design');
      const development = $('#svg-development');
      const marketing = $('#svg-marketing');
      const analytics = $('#svg-analytics');
      const all = [strategy, content, design, development, marketing, analytics];
      const timeline = new TimelineMax()
        .add(TweenMax.to(box, 0.1, { autoAlpha: 1 }))
        .add(
          TweenMax.set([content, design, development, marketing, analytics], {
            opacity: 1,
            display: 'none',
            immediateRender: true
          })
        )
        .add(TweenMax.to(strategy, 0.01, { opacity: 1 }))
        .add(TweenMax.to(strategy.find('.mask1'), 0.675, { strokeDashoffset: 0 }))
        .add(TweenMax.set(all, { display: 'none', immediateRender: false }))
        .add(TweenMax.set(content, { display: 'block', immediateRender: false }))
        .add(TweenMax.from(content, 0, { opacity: '0' }))
        .add(TweenMax.to(content.find('.line-1'), 0.25, { strokeDashoffset: '0' }), '+=0.5')
        .add(TweenMax.to(content.find('.line-2'), 0.25, { strokeDashoffset: '0' }))
        .add(TweenMax.to(content.find('.line-3'), 0.25, { strokeDashoffset: '0' }))
        .add(TweenMax.set(all, { display: 'none', immediateRender: false }))
        .add(TweenMax.set(design, { display: 'block', immediateRender: false }))
        .add(TweenMax.from(design, 0, { autoAlpha: '0' }))
        .add(TweenMax.to(design.find('.mask2'), 1, { strokeDashoffset: 0 }), '+=0.5')
        .add(TweenMax.set(all, { display: 'none', immediateRender: false }))
        .add(TweenMax.set(development, { display: 'block', immediateRender: false }))
        .add(TweenMax.from(development, 0, { opacity: '0' }))
        .add(TweenMax.from(development.find('.corner'), 0.25, { opacity: 0 }), '+=0.25')
        .add(TweenMax.from(development.find('.corner'), 0.5, { x: 25, y: -25 }), '-=0.25')
        .add(TweenMax.to(development.find('.line'), 0.25, { opacity: 0 }), '+=0.5')
        .add(TweenMax.set(all, { display: 'none', immediateRender: false }))
        .add(TweenMax.set(marketing, { display: 'block', immediateRender: false }))
        .add(TweenMax.to(marketing.find('.line-1'), 0.75, { strokeDashoffset: '0' }))
        .add(TweenMax.to(marketing.find('.line-2'), 0.75, { strokeDashoffset: '0' }), '-=0.5')
        .add(TweenMax.to(marketing.find('.line'), 0.15, { opacity: '0' }))
        .add(TweenMax.set(all, { display: 'none', immediateRender: false }))
        .add(TweenMax.set(analytics, { display: 'block', immediateRender: false }))
        .add(TweenMax.from(analytics, 0, { autoAlpha: '0' }))
        .add(TweenMax.to(analytics.find('.line'), 1, { strokeDashoffset: '0' }));

      scene.setTween(timeline).addTo(controller);

      const endScene = new ScrollMagic.Scene({
        triggerElement: '#analytics',
        triggerHook: 0,
        duration: 200,
        offset: 100
      });

      const endTimeline = new TimelineMax();
      endTimeline.add(TweenMax.to(box, 0.25, { autoAlpha: 0 }));
      endScene.setTween(endTimeline).addTo(controller);
    });
  },
  finalize() {}
};
