import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    // init video
    const $hero = $('.wp-block-carney-hero__media-wrap');
    const $video = $hero.find('video');

    if ($video.length) {
      $video.hide();
      const video = $video[0];

      // set video size so it covers screen
      $video.one('loadeddata', () => {
        const { videoWidth, videoHeight } = video;
        const videoRatio = videoHeight / videoWidth;

        $(window)
          .resize(() => {
            const heroWidth = $hero.outerWidth();
            const heroHeight = $hero.outerHeight();
            let newWidth = heroWidth;
            let newHeight = newWidth * videoRatio;
            if (newHeight < heroHeight) {
              newHeight = heroHeight;
              newWidth = newHeight / videoRatio;
            }
            $video.css({ width: newWidth, height: newHeight, minWidth: 0, minHeight: 0 }).show();
          })
          .resize();

        // autoplay video on desktop
        // safari ¯\_(ツ)_/¯
        if (video.paused) {
          setTimeout(() => {
            video.play();
          }, 250);
        }
      });

      // play video when hero is tapped/clicked
      $hero.click(event => {
        if (!$(event.target).is('a, button')) {
          if (video.paused) {
            video.play();
          } else {
            video.pause();
          }
        }
      });
    }

    // set up home collage
    const $homeCollage = $('.wp-block-carney-collage--home');
    if ($homeCollage.length) {
      let controller = new ScrollMagic.Controller();
      let homeScene;
      let homeTween;
      let homeCollageTimeout;

      const home1 = $('.wp-block-carney-collage__item img:eq(0)');
      const home2 = $('.wp-block-carney-collage__item img:eq(1)');
      const home3 = $('.wp-block-carney-collage__item img:eq(2)');
      const home4 = $('.wp-block-carney-collage__item img:eq(3)');
      const home5 = $('.wp-block-carney-collage__item img:eq(4)');
      const home6 = $('.wp-block-carney-collage__item img:eq(5)');

      $(window)
        .on('resize.carney load.carney', () => {
          if (homeCollageTimeout) {
            clearTimeout(homeCollageTimeout);
          }

          homeCollageTimeout = setTimeout(() => {
            const isMobile = $(window).width() <= 991;

            controller.destroy(true);
            controller = new ScrollMagic.Controller();
            homeTween = new TimelineMax();
            homeScene = new ScrollMagic.Scene({
              triggerElement: $homeCollage[0],
              triggerHook: 1,
              duration: $homeCollage.outerHeight()
            });

            if (isMobile) {
              // set initial positions for mobile
              homeTween
                .set([home1, home3, home5], {
                  right: '25vw',
                  left: 'auto',
                  xPercent: 50,
                  clearProps: 'transform'
                })
                .set([home2, home4, home6], {
                  right: 'auto',
                  left: '25vw',
                  xPercent: -50,
                  clearProps: 'transform'
                });

              // set tweens for mobile
              homeTween.add(TweenMax.fromTo(home2, 1, { yPercent: 300 }, { yPercent: 40 }), 0.5);
              homeTween.add(TweenMax.fromTo(home1, 1, { yPercent: 500 }, { yPercent: -20 }), 0.6);
              homeTween.add(TweenMax.fromTo(home4, 1, { yPercent: 1800 }, { yPercent: 70 }), 0.7);
              homeTween.add(TweenMax.fromTo(home3, 1, { yPercent: 800 }, { yPercent: -10 }), 0.8);
              homeTween.add(TweenMax.fromTo(home6, 1, { yPercent: 800 }, { yPercent: 10 }), 0.9);
              homeTween.add(TweenMax.fromTo(home5, 1, { yPercent: 1200 }, { yPercent: -10 }), 1);
            } else {
              // set initial positions for desktop
              homeTween
                .set([home1, home3, home5], {
                  left: '25vw',
                  right: 'auto',
                  xPercent: -50,
                  clearProps: 'transform'
                })
                .set(home2, {
                  left: 'auto',
                  right: '25vw',
                  xPercent: 50,
                  clearProps: 'transform'
                })
                .set(home4, {
                  left: 'auto',
                  right: 0,
                  xPercent: 0,
                  clearProps: 'transform'
                })
                .set(home6, {
                  left: 'auto',
                  right: '20vw',
                  xPercent: 50,
                  clearProps: 'transform'
                });

              // set tweens for desktop
              homeTween.add(TweenMax.fromTo(home1, 1, { yPercent: 250 }, { yPercent: -20 }), 0);
              homeTween.add(TweenMax.fromTo(home2, 1, { yPercent: 150 }, { yPercent: -10 }), 0.2);
              homeTween.add(TweenMax.fromTo(home3, 1, { yPercent: 400 }, { yPercent: 0 }), 0.4);
              homeTween.add(TweenMax.fromTo(home4, 1, { yPercent: 600 }, { yPercent: -20 }), 0.6);
              homeTween.add(TweenMax.fromTo(home5, 1, { yPercent: 600 }, { yPercent: -10 }), 0.8);
              homeTween.add(TweenMax.fromTo(home6, 1, { yPercent: 200 }, { yPercent: -30 }), 1);
            }

            homeScene.setTween(homeTween).addTo(controller);
          }, 100);
        })
        .trigger('resize.carney');
    }
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  }
};
