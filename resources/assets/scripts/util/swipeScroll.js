export default () => {
  const getTouches = (event, property = 'touches') => {
    const { originalEvent } = event;
    const { [property]: touches } = originalEvent;
    return touches;
  };

  const getTranslateX = el => {
    const { style } = el;
    const { transform = 'translateX(0px)' } = style;
    return parseFloat(transform.replace(/translateX\((-?[\d.]*)px\)/, '$1')) || 0;
  };

  const scrollEl = (el, deltaX, min = -(el.scrollWidth - el.clientWidth), max = 0) => {
    const $el = $(el);
    const offset = $el.data('offset') || 0;
    const translateX = Math.max(Math.min(offset + deltaX, max), min);
    const transform = `translateX(${translateX}px)`;
    $el.css({ transform }).data('offset', translateX);
  };

  const snapEl = (el, itemWidth, swipeDistance) => {
    const $el = $(el);

    if (itemWidth) {
      $el.data('offset', 0).css({ transitionDuration: '0.25s' });
      const translateX = getTranslateX(el);
      const snapDistance = Math.abs(translateX % itemWidth);
      let offset;

      if (Math.abs(swipeDistance) >= 50) {
        offset =
          swipeDistance > 0 ? translateX + snapDistance : translateX + snapDistance + -itemWidth;
      } else {
        offset =
          snapDistance < itemWidth / 2
            ? translateX + snapDistance
            : translateX + snapDistance + -itemWidth;
      }

      const min = -(el.scrollWidth - el.querySelector('.container').clientWidth);
      scrollEl(el, offset, min);
    }
  };

  $('.wp-block-carney-posts__wrap, .wp-block-carney-related-clients__wrap').each((index, el) => {
    const $el = $(el);
    const swipeItemSelector = $el.data('swipe-item');
    const itemWidth = swipeItemSelector ? $el.find(swipeItemSelector).outerWidth() : 0;

    $el.on('dragstart.carney', e => {
      e.preventDefault();
      return false;
    });

    $el.on('mousedown.carney', mousedown => {
      const { ctrlKey, shiftKey, metaKey, altKey } = mousedown;
      let { screenX } = mousedown;
      const startX = screenX;

      if (!ctrlKey && !shiftKey && !metaKey && !altKey) {
        // add mousemove handler
        $el.on('mousemove.carney', mousemove => {
          const { screenX: mousemoveX } = mousemove;
          const deltaX = mousemoveX - screenX;
          $el.css({ transitionDuration: '0s' });
          if (deltaX) {
            $el.one('click', e => e.preventDefault());
            const min = -(el.scrollWidth - el.querySelector('.container').clientWidth);
            scrollEl(el, deltaX, min);
            screenX = mousemoveX;
          }
        });

        // remove mousemove handler on mouseup or mouseleave
        $el.one('mouseup.carney, mouseleave.carney', event => {
          $el.off('mousemove.carney');

          if (itemWidth) {
            const { screenX: eventX } = event;
            const swipeDistance = eventX - startX;
            snapEl(el, itemWidth, swipeDistance);
          }
        });
      }
    });

    $el.on('touchstart.carney', touchstart => {
      const touches = getTouches(touchstart);
      const [touch] = touches;
      let { screenX } = touch;
      const startX = screenX;

      if (touches.length === 1) {
        // add touchmove handler
        $el.on('touchmove.carney', touchmove => {
          const { originalEvent: originalTouchmove } = touchmove;
          const { changedTouches } = originalTouchmove;
          if (changedTouches.length === 1) {
            $el.css({ transitionDuration: '0s' });
            const [touchmoveTouch] = changedTouches;
            const { screenX: touchmoveX } = touchmoveTouch;
            const deltaX = touchmoveX - screenX;
            if (deltaX) {
              const min = -(el.scrollWidth - el.querySelector('.container').clientWidth);
              scrollEl(el, deltaX, min);
              screenX = touchmoveX;
            }
          }
        });

        // snap to nearest item on touchend
        $el.one('touchend.carney', touchend => {
          $el.off('touchmove.carney');

          if (itemWidth) {
            const [touchendTouch] = getTouches(touchend, 'changedTouches');
            const { screenX: touchendX } = touchendTouch;
            const swipeDistance = touchendX - startX;
            snapEl(el, itemWidth, swipeDistance);
          }
        });
      }
    });
  });
};
