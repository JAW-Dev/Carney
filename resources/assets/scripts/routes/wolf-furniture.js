import ImageComparison from 'image-comparison';
import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax } from 'gsap/TweenMax';
import Donut from '../components/donut';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

export default {
  init() {
    let controller = new ScrollMagic.Controller();
    let resizeTimeout;

    // set up wolf calendar section
    const $calendar = $('.wp-block-carney-calendar');
    const $days = $('.wp-block-carney-calendar__day[data-chart-selector]');
    $days.each((index, day) => {
      const $day = $(day);
      const $chart = $($day.data('chart-selector'));
      const $donut = $chart.find('.wp-block-carney-donut');
      const donut = new Donut($donut);
      $donut[0].donut = donut;

      let chartScene;
      let chartCollageTimeout;

      $(window).on(`controller-ready.donut-${index}.carney`, () => {
        if (chartCollageTimeout) {
          clearTimeout(chartCollageTimeout);
        }

        chartCollageTimeout = setTimeout(() => {
          const isMobile = $(window).width() <= 991;

          chartScene = new ScrollMagic.Scene({
            triggerElement: [$day[0], $chart[0]][index],
            triggerHook: isMobile ? 0.75 : 1,
            duration:
              $calendar
                .closest('.row')
                .closest('[class*="col-"]')
                .height() * 1.2
          });

          donut.setActiveIndex(1, false /* no transition */);
          $day.removeClass('is-active');

          chartScene
            .setClassToggle($day[0], 'is-active')
            .on('enter', () => {
              $calendar.removeClass('wp-block-carney-calendar--no-transition');
              $day.addClass('is-active');
              donut.setActiveIndex(0);
            })
            .on('leave', () => {
              $calendar.addClass('wp-block-carney-calendar--no-transition');
              $day.removeClass('is-active');
              donut.setActiveIndex(1, false /* no transition */);
            })
            .addTo(controller);
        }, 100);
      });
    });

    // set up wolf image comparison
    $('.wp-block-carney-image-comparison').each((index, comparison) => {
      const $comparison = $(comparison);
      const chartsSelector = $comparison.data('chart-selector');
      const $container = $comparison.children('.wp-block-carney-image-comparison__inner');
      const $images = $container.children('img');
      if ($images.length === 2) {
        /* eslint-disable no-new */
        new ImageComparison({
          container: $container[0],
          startPosition: 30,
          data: [
            {
              image: $images[0],
              label: null
            },
            {
              image: $images[1],
              label: null
            }
          ]
        });

        if (chartsSelector) {
          const $charts = $(chartsSelector);

          if ($charts.length) {
            let lastActiveIndex;
            const $chartHeadings = $charts.find('.wp-block-carney-charts__heading > div');
            const $chartDonuts = $charts.find('.wp-block-carney-donut');

            const toggleChart = activeIndex => {
              if (lastActiveIndex !== activeIndex) {
                // transition chart heading text
                $chartHeadings
                  .eq(activeIndex)
                  .addClass('is-active')
                  .prop('aria-hidden', false)
                  .siblings()
                  .removeClass('is-active')
                  .prop('aria-hidden', true);

                // transition chart segments and values
                $chartDonuts.each((donutIndex, el) => {
                  /* eslint-disable no-param-reassign */
                  el.donut = el.donut || new Donut($(el));
                  el.donut.setActiveIndex(activeIndex);
                });
              }
              lastActiveIndex = activeIndex;
            };

            toggleChart(1);

            $comparison.on('mousedown.carney touchstart.carney', '.comparison-item--first', () => {
              $comparison.on(
                'mousemove.carney touchmove.carney',
                '.comparison-item--first',
                event => {
                  const style = $(event.currentTarget).attr('style');
                  const width = parseFloat(style.replace(/.*?width:\s*(\d*\.*\d+)%/, '$1')) || 0;
                  toggleChart(width < 50 ? 1 : 0);
                }
              );
            });

            $comparison.on('mouseup.carney touchend.carney', '.comparison-item--first', () => {
              $comparison.off('mousemove.carney touchmove.carney');
            });
          }
        }
      }
    });

    // set up wolf collage
    const $wolfCollage = $('.wp-block-carney-collage--client-wolfs');
    if ($wolfCollage.length) {
      let wolfScene;
      let wolfTween;
      let wolfCollageTimeout;

      const wolf1 = $('.wp-block-carney-collage__item img:eq(0)');
      const wolf2 = $('.wp-block-carney-collage__item img:eq(1)');

      $(window).on('controller-ready.collage.carney', () => {
        if (wolfCollageTimeout) {
          clearTimeout(wolfCollageTimeout);
        }

        wolfCollageTimeout = setTimeout(() => {
          const isMobile = $(window).width() <= 991;

          wolfTween = new TimelineMax();
          wolfScene = new ScrollMagic.Scene({
            triggerElement: $wolfCollage[0],
            triggerHook: isMobile ? 0.75 : 1,
            offset: isMobile ? 0 : -$(window).width() * 0.15,
            duration: isMobile ? $wolfCollage.outerHeight() : $(window).height() * 1.25
          });

          wolfTween.set([wolf1, wolf2], {
            opacity: 1,
            clearProps: 'transform, opacity'
          });

          if (isMobile) {
            // set tweens for mobile
            wolfTween.add(TweenMax.from(wolf1, 1, { opacity: 0, yPercent: 500 }), 0.5);
            wolfTween.add(TweenMax.from(wolf2, 1, { opacity: 0, yPercent: 300 }), 1.2);
          } else {
            // set tweens for desktop
            wolfTween.add(TweenMax.from(wolf1, 1, { opacity: 0, yPercent: 250 }), 0.5);
            wolfTween.add(TweenMax.from(wolf2, 1, { opacity: 0, yPercent: 150 }), 1.2);
          }

          wolfScene.setTween(wolfTween).addTo(controller);
        }, 100);
      });
    }

    // set up resize handler
    $(window)
      .on('resize.carney load.carney', () => {
        if (resizeTimeout) {
          clearTimeout(resizeTimeout);
        }

        resizeTimeout = setTimeout(() => {
          controller.destroy(true);
          controller = new ScrollMagic.Controller();
          $(window).trigger('controller-ready');
        }, 100);
      })
      .trigger('resize');
  }
};
