import ScrollMagic from 'scrollmagic';
import { TweenMax, TimelineMax, Linear } from 'gsap/TweenMax';

require('imports-loader?define=>false!scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');

/* eslint-disable no-mixed-operators */

export default {
  init() {
    $('.site-header').hide();

    let controller;

    $(window)
      .on('load.carney resize.carney', () => {
        if (controller) {
          controller.destroy(true);
        }

        controller = new ScrollMagic.Controller();

        // top
        const $top = $('.capabilities-top');
        const $box = $('.capabilities-box');

        // objectives
        const $objectivesEl = $('#objectives');
        const $objectives = $('#svg-objectives');
        const $objectivesCircle = $objectives.find('#svg-objectives_circle');
        const $objectivesTopX = $objectives.find('#svg-objectives_top-x');
        const $objectivesBottomX = $objectives.find('#svg-objectives_bottom-x');
        const $objectivesPath = $objectives.find('#svg-objectives_path');
        const $objectivesArrow = $objectives.find('#svg-objectives_arrow');
        const objectivesTotalDuration = 3.5;
        const objectivesCircleDuration = 3 / objectivesTotalDuration;
        const objectivesPathTotalLength = 362.5;
        const objectivesPath1Length = 93.77;
        const objectivesPath1Ratio = objectivesPath1Length / objectivesPathTotalLength;
        const objectivesPath1Duration = objectivesPath1Ratio * objectivesCircleDuration;
        const objectivesPath2Length = 153;
        const objectivesPath2Ratio = objectivesPath2Length / objectivesPathTotalLength;
        const objectivesPath2Duration = objectivesPath2Ratio * objectivesCircleDuration;
        const objectivesPath3Length = 115.73;
        const objectivesPath3Ratio = objectivesPath3Length / objectivesPathTotalLength;
        const objectivesPath3Duration = objectivesPath3Ratio * objectivesCircleDuration;
        const objectivesXDuration = 1.5 / objectivesTotalDuration;
        const objectivesXStartOffset = 0.5 / objectivesTotalDuration;
        const objectivesQuickHideDuration = 0.25 / objectivesTotalDuration;

        // audience
        const $audienceEl = $('#audience');
        const $audience = $('#svg-audience');
        const audienceTotalDuration = 6.25;
        const $audienceUsers = $audience.find('#svg-audience_users');
        const $audienceUser1 = $audience.find('#svg-audience_user-1');
        const $audienceUser2 = $audience.find('#svg-audience_user-2');
        const $audienceUser3 = $audience.find('#svg-audience_user-3');
        const $audienceUser4 = $audience.find('#svg-audience_user-4');
        const $audienceUser5 = $audience.find('#svg-audience_user-5');
        const audienceAllUsers = [
          $audienceUser1,
          $audienceUser2,
          $audienceUser3,
          $audienceUser4,
          $audienceUser5
        ];
        const audienceCartUsers = [$audienceUser1, $audienceUser4];
        const audienceOtherUsers = [$audienceUser2, $audienceUser3, $audienceUser5];
        const $audienceLines = $audience.find('#svg-audience_lines');
        const $audienceCircle = $audience.find('#svg-audience_circle');
        const $audienceUser1cart = $audienceUser1.find('#svg-audience_user-1_cart');
        const $audienceUser1head = $audienceUser1.find('#svg-audience_user-1_head');

        // style
        const $styleEl = $('#style');
        const $style = $('#svg-style');
        const $styleEqualizer = $style.find('#svg-style_equalizer');
        const $styleHead = $style.find('#svg-style_head');
        const $styleBars = $style.find('#svg-style_equalizer_bars');
        const $styleLines = $styleBars.find('line');
        const styleYOrigin = 101.96;
        const styleLineDuration = 0.1;

        const getRandomInt = (min, max) => {
          const minInt = Math.ceil(min);
          const maxInt = Math.floor(max);
          return Math.floor(Math.random() * (maxInt + -minInt + 1)) + minInt;
        };

        const styleShuffleLines = () =>
          $styleLines
            .toArray()
            .map(a => [Math.random(), a])
            .sort((a, b) => a[0] - b[0])
            .map(a => a[1]);

        const styleLineSets = [];
        for (let i = 0; i < 101; i += 1) {
          styleLineSets.push(styleShuffleLines());
        }

        const styleWidth = 238.88; // viewbox width of style SVG
        const contentWidth = 199.92; // viewbox width of content SVG
        const styleWidthRatio = styleWidth / contentWidth;
        const styleContentTranslateYOffset = 0;
        const styleFinalLinePositions = [
          // from content SVG text block lines
          [[15.61, 117.76], [83.89, 117.76]],
          [[15.61, 124.72], [76.09, 124.72]],
          [[15.61, 131.68], [83.89, 131.68]],
          [[15.61, 138.64], [76.09, 138.64]],
          [[15.61, 145.61], [55.11, 145.61]],
          [[15.61, 159.53], [83.89, 159.53]],
          [[15.61, 166.49], [76.09, 166.49]],
          [[15.61, 173.45], [83.89, 173.45]],
          [[15.61, 180.41], [76.09, 180.41]],
          [[15.61, 187.37], [55.11, 187.37]]
        ].map(([start, end]) => [
          [start[0] * styleWidthRatio, start[1] * styleWidthRatio + styleContentTranslateYOffset],
          [end[0] * styleWidthRatio, end[1] * styleWidthRatio + styleContentTranslateYOffset]
        ]);

        // content
        const $contentEl = $('#content');
        const $content = $('#svg-content');
        const $contentBorder = $content.find('#svg-content_border');
        const $contentImageBlock = $content.find('#svg-content_image-block');
        const $contentTextBlock = $content.find('#svg-content_text-block');
        const $contentTextBlockBorder = $content.find('#svg-content_text-block_border');
        const $contentVideoBlock = $content.find('#svg-content_video-block');

        // frequency
        const $frequencyEl = $('#frequency');
        const $frequency = $('#svg-frequency');
        const $frequencyPage1 = $frequency.find('#svg-frequency_page-1');
        const $frequencyPage2 = $frequency.find('#svg-frequency_page-2');
        const $frequencyPage3 = $frequency.find('#svg-frequency_page-3');
        const $frequencyPage4 = $frequency.find('#svg-frequency_page-4');
        const $frequencyPage5 = $frequency.find('#svg-frequency_page-5');
        const $frequencyClock = $frequency.find('#svg-frequency_clock');
        const $frequencyClockHourHand = $frequency.find('#svg-frequency_clock_hour-hand');
        const $frequencyClockMinuteHand = $frequency.find('#svg-frequency_clock_minute-hand');

        // distribution
        const $distributionEl = $('#distribution');
        const $distribution = $('#svg-distribution');
        const $distributionPage = $distribution.find('#svg-distribution_page');
        const $distributionSocialIcons = $distribution.find('#svg-distribution_social-icons');
        const $distributionSymbols = $distribution.find('.mountain, .sun, .text, .triangle');

        // analytics
        const $analyticsEl = $('#analytics');
        const $analytics = $('#svg-analytics');
        const $analyticsLineChartLine = $analytics.find('#svg-analytics_line-chart_line');
        const $analyticsLineChartCircle1 = $analytics.find('#svg-analytics_line-chart_circle-1');
        const $analyticsLineChartCircle2 = $analytics.find('#svg-analytics_line-chart_circle-2');
        const $analyticsBarChartLines = $analytics.find('#svg-analytics_bar-chart line');
        const $analyticsPie = $analytics.find('.pie');

        // sections
        const sections = [
          $objectivesEl,
          $audienceEl,
          $styleEl,
          $contentEl,
          $frequencyEl,
          $distributionEl,
          $analyticsEl
        ];

        const starterTimeline = new TimelineMax();
        const starterScene = new ScrollMagic.Scene({
          triggerElement: $top[0],
          triggerHook: 0,
          offset: 300,
          duration: 150
        });
        starterTimeline.add(TweenMax.to($box, 1, { autoAlpha: 1 }));
        starterScene.setTween(starterTimeline).addTo(controller);

        // add a scene at the beginning
        let timeline = new TimelineMax();
        let duration = 400;
        let scene = new ScrollMagic.Scene({
          triggerElement: $top[0],
          triggerHook: 0,
          offset: 200,
          duration
        });

        // fade in
        timeline.add(TweenMax.to($objectives, 1, { autoAlpha: 1 }));
        scene.setTween(timeline).addTo(controller);

        const width = $(window).width();

        // add a scene for each section
        sections.forEach($section => {
          timeline = new TimelineMax();
          duration = width <= 375 ? $section.outerHeight() - 400 : $section.outerHeight();
          scene = new ScrollMagic.Scene({
            triggerElement: $section[0],
            triggerHook: 0,
            duration
          });

          switch ($section) {
            case $objectivesEl:
              timeline
                .set($objectivesPath, {
                  strokeDasharray: 362.5,
                  strokeDashoffset: 0
                })
                .set([$objectivesCircle, $objectivesTopX, $objectivesBottomX], {
                  transformOrigin: '50% 50%'
                })
                .set([$audienceUsers, $audienceLines], {
                  transformOrigin: '50.47% 54.15%',
                  scale: 0,
                  opacity: 0
                })

                // first part of path
                .add(
                  TweenMax.to($objectivesCircle, objectivesPath1Duration, {
                    y: `-=${objectivesPath1Length}`
                  })
                )
                .add(
                  TweenMax.to($objectivesPath, objectivesPath1Duration, {
                    strokeDashoffset: `-${objectivesPath1Length}`
                  }),
                  `-=${objectivesPath1Duration}`
                )

                // top x
                .add(
                  TweenMax.to($objectivesTopX, objectivesXDuration, {
                    rotation: '-=270',
                    scale: 0.5,
                    xPercent: -10,
                    yPercent: -10,
                    opacity: 0.5
                  }),
                  `-=${objectivesXStartOffset}`
                )

                // second part of path
                .add(
                  TweenMax.to($objectivesCircle, objectivesPath2Duration, {
                    x: `+=${objectivesPath2Length}`
                  }),
                  objectivesPath1Duration
                )
                .add(
                  TweenMax.to($objectivesPath, objectivesPath2Duration, {
                    strokeDashoffset: `-${objectivesPath1Length + objectivesPath2Length}`
                  }),
                  `-=${objectivesPath2Duration}`
                )

                // bottom x
                .add(
                  TweenMax.to($objectivesBottomX, objectivesXDuration, {
                    scale: 0.5,
                    xPercent: 10,
                    yPercent: 10,
                    opacity: 0.5,
                    rotation: '+=270'
                  }),
                  `-=${objectivesXStartOffset}`
                )

                // third part of path
                .add(
                  TweenMax.to($objectivesCircle, objectivesPath3Duration, {
                    y: `-=${objectivesPath3Length - 21.5}`
                  }),
                  objectivesPath1Duration + objectivesPath2Duration
                )
                .add(
                  TweenMax.to($objectivesPath, objectivesPath3Duration, {
                    strokeDashoffset: `-${objectivesPathTotalLength}`
                  }),
                  `-=${objectivesPath3Duration}`
                )

                // hide path and arrow
                .set([$objectivesPath, $objectivesArrow], { display: 'none' })

                // shrink circle
                .add(
                  TweenMax.to(
                    $objectivesCircle,
                    objectivesQuickHideDuration,
                    { scale: 0.17, strokeWidth: 0, fill: '#fff' },
                    objectivesCircleDuration
                  )
                )

                // hide Xs
                .add(
                  TweenMax.to(
                    [$objectivesTopX, $objectivesBottomX],
                    objectivesQuickHideDuration,
                    { scale: 0, opacity: 0 },
                    `-=${objectivesQuickHideDuration}`
                  )
                )

                // move circle to center
                .add(
                  TweenMax.to(
                    $objectivesCircle,
                    objectivesQuickHideDuration,
                    {
                      x: '-=79.51',
                      y: '+=87.770'
                    },
                    objectivesCircleDuration + objectivesQuickHideDuration
                  )
                )

                .add(TweenMax.to($audience, 0.01, { autoAlpha: 1 }))
                .add(TweenMax.to($objectives, 0.01, { autoAlpha: 0 }), '-=0.01')

                // pop out users
                .add(
                  TweenMax.to(
                    [$audienceUsers, $audienceLines, ...audienceCartUsers],
                    objectivesTotalDuration / 5,
                    {
                      scale: 1,
                      opacity: 1
                    }
                  ),
                  objectivesCircleDuration + objectivesQuickHideDuration * 2
                )
                .add(
                  TweenMax.to(audienceOtherUsers, objectivesTotalDuration / 5, {
                    scale: 1,
                    opacity: 0.5
                  }),
                  `-=${objectivesTotalDuration / 5}`
                );
              break;

            case $audienceEl:
              timeline
                .add(
                  TweenMax.set(audienceAllUsers, {
                    transformOrigin: '50% 50%'
                  })
                )

                // start rotating users and lines
                .add(
                  TweenMax.to([$audienceUsers, $audienceLines], 5 / audienceTotalDuration, {
                    rotation: 144,
                    ease: Linear.easeNone
                  }),
                  0
                )
                .add(
                  TweenMax.to(audienceAllUsers, 5 / audienceTotalDuration, {
                    rotation: -144,
                    ease: Linear.easeNone
                  }),
                  0
                )

                // hide lines/center
                .add(
                  TweenMax.to([$audienceLines, $audienceCircle], 1 / audienceTotalDuration, {
                    autoAlpha: 0
                  }),
                  `-=${1 / audienceTotalDuration}`
                )

                // scale cart users up and center them
                .add(
                  TweenMax.to(audienceCartUsers, 1 / audienceTotalDuration, { scale: 2 }),
                  `-=${0.5 / audienceTotalDuration}`
                )
                .add(
                  TweenMax.to($audienceUser1, 1 / audienceTotalDuration, {
                    x: `${236.649808 - 227.64980799599545}`,
                    y: `${260.493587 - 139.4935870035305}`
                  }),
                  `-=${1 / audienceTotalDuration}`
                )
                .add(
                  TweenMax.to($audienceUser4, 1 / audienceTotalDuration, {
                    x: `${51.148369 - -25.851630854013123}`,
                    y: `${400.125854 - 501.12585442746854}`
                  }),
                  `-=${1 / audienceTotalDuration}`
                )
                .add(
                  TweenMax.to($audienceUser4, 0.25 / audienceTotalDuration, { autoAlpha: 0 }),
                  `-=${0.25 / audienceTotalDuration}`
                )

                // hide other users totally
                .add(
                  TweenMax.to(audienceOtherUsers, 0.5 / audienceTotalDuration, { autoAlpha: 0 }),
                  `-=${0.5 / audienceTotalDuration}`
                )

                // scale cart user up to full size, fade out, and hide cart
                .add(
                  TweenMax.to($audienceUser1, 1 / audienceTotalDuration, {
                    scale: 4.978,
                    x: `-=${599.2954146258555 - 602.295415}`,
                    y: `-=${685.0684975020218 - 681.068498}`
                  })
                )
                .add(
                  TweenMax.to($audienceUser1cart, 0.5 / audienceTotalDuration, { autoAlpha: 0 }),
                  `-=${0.5 / audienceTotalDuration}`
                )
                .add(
                  TweenMax.to($audienceUser1head, 0.5 / audienceTotalDuration, { opacity: 0.1 }),
                  `-=${0.5 / audienceTotalDuration}`
                )

                // show equalizer lines
                .add(TweenMax.to($style, 0.01, { autoAlpha: 1 }))
                .add(TweenMax.to($audience, 0.01, { autoAlpha: 0 }), '-=0.01')
                .add(TweenMax.to($styleEqualizer, 0.5 / audienceTotalDuration, { autoAlpha: 1 }))
                .add(
                  TweenMax.to($styleLines, 0.5 / audienceTotalDuration, {
                    attr: { y1: '-=2', y2: '+=2' }
                  }),
                  `-=${0.5 / audienceTotalDuration}`
                );

              [0, 2, 4, 6, 8].forEach(i => {
                const position = i === 0 ? '+=0' : '-=0.25';
                const yOffset = getRandomInt(112, 133) - styleYOrigin;
                timeline.add(
                  TweenMax.to(styleLineSets[0].slice(i, i + 3), 0.25, {
                    attr: {
                      y1: styleYOrigin + yOffset,
                      y2: styleYOrigin - yOffset
                    }
                  }),
                  position
                );
              });
              break;

            case $styleEl:
              // set up content SVG
              timeline
                .add(
                  TweenMax.to(
                    [
                      $contentBorder,
                      $contentImageBlock,
                      $contentTextBlockBorder,
                      $contentTextBlock.find('> line'),
                      $contentVideoBlock
                    ],
                    0.01,
                    { autoAlpha: 0 }
                  ),
                  0
                )
                .add(TweenMax.to($content, 0.01, { autoAlpha: 1 }))
                .add(
                  TweenMax.to($contentBorder, 0.01, { scale: 1.07, transformOrigin: '50% 50%' })
                );

              // play equalizer
              styleLineSets.forEach((set, setIndex) => {
                if (setIndex === 0) {
                  return;
                }

                [0, 2, 4, 6, 8].forEach(i => {
                  const position = i === 0 ? '+=0' : `-=${setIndex * 0.05}`;
                  const yOffset = getRandomInt(112, 133) - styleYOrigin;
                  timeline.add(
                    TweenMax.to(set.slice(i, i + 3), 0.05, {
                      attr: {
                        y1: styleYOrigin + yOffset,
                        y2: styleYOrigin - yOffset
                      }
                    }),
                    position
                  );
                });
              });

              // fade out horizontal line and head
              timeline
                .add(
                  TweenMax.to($styleEqualizer.find('> line'), 0.5, { autoAlpha: 0 }),
                  `-=${styleLineDuration * $styleLines.length / 2}`
                )
                .add(TweenMax.to($styleHead, 0.5, { autoAlpha: 0 }), '-=0.5');

              // move lines to final positions
              $styleLines.each((index, line) => {
                const [start, end] = styleFinalLinePositions[index];
                const position = index === 0 ? '+=0.05' : null;
                timeline.add(
                  TweenMax.to(line, styleLineDuration, {
                    strokeWidth: 3 * styleWidthRatio,
                    attr: {
                      x1: start[0],
                      y1: start[1],
                      x2: end[0],
                      y2: end[1]
                    }
                  }),
                  position
                );
              });

              timeline
                .add(
                  TweenMax.fromTo(
                    $contentTextBlockBorder,
                    0.5,
                    { autoAlpha: 0, fill: 'rgba(38, 38, 38, 0)' },
                    { autoAlpha: 1, fill: 'rgba(38, 38, 38, 0)' }
                  ),
                  '-=0.5'
                )
                .add(TweenMax.to([$contentImageBlock, $contentVideoBlock], 0.5, { autoAlpha: 1 }))
                .add(TweenMax.to($contentTextBlockBorder, 0.01, { fill: 'rgba(38, 38, 38, 1)' }))
                .add(TweenMax.to($contentTextBlock.find('> line'), 0.01, { autoAlpha: 1 }))
                .add(TweenMax.to($style, 0.01, { autoAlpha: 0 }))

                // scale image and video blocks
                .add(TweenMax.to($contentImageBlock, 0.5, { x: 14, y: 20 }))
                .add(TweenMax.to($contentTextBlock, 0.5, { x: 27, y: -2 }), '-=0.5')
                .add(TweenMax.to($contentVideoBlock, 0.5, { x: -11, y: -13 }), '-=0.5')

                // fade in border
                .add(TweenMax.to($contentBorder, 0.5, { autoAlpha: 1 }), '-=0.5')

                // set up frequency SVG
                .set($frequency, { autoAlpha: 1 })
                .set($content, { autoAlpha: 0 });
              break;

            case $contentEl:
              timeline

                // scale up frequency SVG
                .add(
                  TweenMax.fromTo(
                    $frequency,
                    1,
                    {
                      scale: 1.41,
                      x: 6.6,
                      y: 33.5
                    },
                    {
                      scale: 1,
                      x: 0,
                      y: 0
                    }
                  ),
                  0
                )

                // unstack pages
                .add(
                  TweenMax.fromTo(
                    $frequencyPage1,
                    1,
                    { scale: 0.91, x: 68, y: -38 },
                    { scale: 1, x: 0, y: 0 }
                  ),
                  0
                )
                .add(
                  TweenMax.fromTo(
                    $frequencyPage2,
                    1,
                    { scale: 0.96, x: 32, y: -16, autoAlpha: 0 },
                    { scale: 1, x: 0, y: 0, autoAlpha: 1 }
                  ),
                  0
                )
                .add(TweenMax.fromTo($frequencyPage3, 1, { autoAlpha: 0 }, { autoAlpha: 1 }), 0)
                .add(
                  TweenMax.fromTo(
                    $frequencyPage4,
                    1,
                    { scale: 1.11, x: -53, y: 14, autoAlpha: 0 },
                    { scale: 1, x: 0, y: 0, autoAlpha: 1 }
                  ),
                  0
                )
                .add(
                  TweenMax.fromTo(
                    $frequencyPage5,
                    1,
                    { scale: 1.235, x: -110, y: 26, autoAlpha: 0 },
                    { scale: 1, x: 0, y: 0, autoAlpha: 1 }
                  ),
                  0
                )

                // slide and fade in clock
                .add(
                  TweenMax.fromTo(
                    $frequencyClock,
                    1,
                    { autoAlpha: 0, x: 110 },
                    { autoAlpha: 1, x: 0 }
                  ),
                  0
                )

                // rotate hour hand
                .add(
                  TweenMax.fromTo(
                    $frequencyClockHourHand,
                    1,
                    { rotation: -60, transformOrigin: '0% 0%' },
                    { rotation: 0, transformOrigin: '0% 0%' }
                  ),
                  0
                )

                // rotate minute hand
                .add(
                  TweenMax.fromTo(
                    $frequencyClockMinuteHand,
                    1,
                    { rotation: -720, transformOrigin: '0% 100%' },
                    { rotation: 0, transformOrigin: '0% 100%' }
                  ),
                  0
                );
              break;

            case $frequencyEl:
              timeline

                // reposition frequency SVG to line up with distribution SVG
                .add(
                  TweenMax.to($frequency, 0.5, {
                    x: 9,
                    y: -13
                  }),
                  0
                )

                // stack pages
                .add(
                  TweenMax.fromTo(
                    $frequencyPage1,
                    0.5,
                    { scale: 1, x: 0, y: 0 },
                    { scale: 0.91, x: 68, y: -38 }
                  ),
                  0
                )
                .add(
                  TweenMax.fromTo(
                    $frequencyPage2,
                    0.5,
                    { scale: 1, x: 0, y: 0 },
                    { scale: 0.96, x: 32, y: -16 }
                  ),
                  0
                )
                .add(
                  TweenMax.fromTo(
                    $frequencyPage4,
                    0.5,
                    { scale: 1, x: 0, y: 0 },
                    { scale: 1.11, x: -53, y: 14 }
                  ),
                  0
                )
                .add(
                  TweenMax.fromTo(
                    $frequencyPage5,
                    0.5,
                    { scale: 1, x: 0, y: 0 },
                    { scale: 1.235, x: -110, y: 26 }
                  ),
                  0
                )

                // slide and fade out clock
                .add(TweenMax.to($frequencyClock, 0.5, { autoAlpha: 0, x: 110 }), 0)

                // rotate hour hand
                .add(
                  TweenMax.fromTo(
                    $frequencyClockHourHand,
                    0.5,
                    { rotation: 0, transformOrigin: '0% 0%' },
                    { rotation: 30, transformOrigin: '0% 0%' }
                  ),
                  0
                )

                // rotate minute hand
                .add(
                  TweenMax.fromTo(
                    $frequencyClockMinuteHand,
                    0.5,
                    { rotation: 0, transformOrigin: '0% 100%' },
                    { rotation: 360, transformOrigin: '0% 100%' }
                  ),
                  0
                )

                // scale up distribution SVG
                .set($distribution, {
                  scale: 1.4285714285714286,
                  transformOrigin: '50% 0%'
                })

                .add(TweenMax.to($distribution, 0.1, { autoAlpha: 1 }))
                .add(TweenMax.to($frequency, 0.1, { autoAlpha: 0 }), '-=0.1')

                // pop out social icons
                .add(
                  TweenMax.fromTo(
                    $distributionSocialIcons,
                    0.4,
                    {
                      autoAlpha: 0,
                      transformOrigin: '50% 0%',
                      scale: 0.5,
                      y: 30
                    },
                    {
                      autoAlpha: 1,
                      transformOrigin: '50% 0%',
                      scale: 1,
                      y: 60
                    }
                  ),
                  '-=0.1'
                );
              break;

            case $distributionEl:
              timeline
                // fade out social icons
                .add(
                  TweenMax.to($distributionSocialIcons, 0.35, {
                    autoAlpha: 0
                  })
                )

                // scale up page
                .add(
                  TweenMax.to($distributionPage, 0.35, {
                    scale: 1.54,
                    x: -14,
                    y: -1,
                    transformOrigin: '50% 0%'
                  }),
                  0.15
                )

                // fade out page elements
                .add(TweenMax.to($distributionSymbols, 0.25, { autoAlpha: 0 }), 0.25)

                // set up analytics SVG
                .add(TweenMax.to($analytics, 0.01, { autoAlpha: 1 }))
                .add(TweenMax.to($distribution, 0.01, { autoAlpha: 0 }), '-=0.01')

                // draw line chart line
                .add(
                  TweenMax.fromTo(
                    $analyticsLineChartLine,
                    0.25,
                    {
                      strokeDasharray: 340,
                      strokeDashoffset: 340
                    },
                    {
                      strokeDashoffset: 0
                    }
                  )
                )

                // draw line chart circle 1
                .add(
                  TweenMax.fromTo(
                    $analyticsLineChartCircle1,
                    0.125,
                    {
                      strokeDasharray: 80,
                      strokeDashoffset: 80
                    },
                    {
                      strokeDashoffset: 0
                    }
                  ),
                  0.675
                )

                // draw line chart circle 2
                .add(
                  TweenMax.fromTo(
                    $analyticsLineChartCircle2,
                    0.125,
                    {
                      strokeDasharray: 80,
                      strokeDashoffset: 80
                    },
                    {
                      strokeDashoffset: 0
                    }
                  ),
                  0.75
                )

                // draw bar chart
                .add(
                  TweenMax.fromTo(
                    $analyticsBarChartLines,
                    0.25,
                    {
                      strokeDasharray: 135,
                      strokeDashoffset: 135
                    },
                    {
                      strokeDashoffset: 0
                    }
                  ),
                  0.75
                )

                // draw pie chart
                .add(
                  TweenMax.fromTo(
                    $analyticsPie,
                    0.125,
                    {
                      strokeWidth: 22,
                      stroke: '#fff',
                      strokeDasharray: '0, 65',
                      strokeDashoffset: 13.5
                    },
                    {
                      strokeWidth: 22,
                      stroke: '#fff',
                      strokeDasharray: '25, 40'
                    }
                  ),
                  0.875
                );
              break;

            case $analyticsEl:
              scene.duration(100);
              timeline.add(TweenMax.to($box, 0.5, { autoAlpha: 0 }));
              // timeline.add(TweenMax.to($analytics, 1, { autoAlpha: 0 }));
              break;

            default:
          }

          scene.setTween(timeline).addTo(controller);
        });
      })
      .trigger('resize.carney');
  },
  finalize() {}
};

/* eslint-enable no-mixed-operators */
