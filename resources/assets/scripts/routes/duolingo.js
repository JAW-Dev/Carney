import ImageComparison from 'image-comparison';

export default {
  init() {
    const phoneObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          const animation = entry.target.querySelector('[data-src]');
          if (animation) {
            if (entry.isIntersecting) {
              animation.setAttribute('src', animation.dataset.src);
              animation.parentNode.classList.add('phone__video--visible');
            } else {
              animation.parentNode.classList.remove('phone__video--visible');
            }
          }
        });
      },
      {
        threshold: 0.2
      }
    );

    phoneObserver.observe(document.querySelector('.duo-phone'));

    // set up wolf image comparison
    $('.wp-block-carney-image-comparison').each((index, comparison) => {
      const $comparison = $(comparison);
      const $container = $comparison.children('.wp-block-carney-image-comparison__inner');
      const $images = $container.children('img');
      if ($images.length === 2) {
        /* eslint-disable no-new */
        new ImageComparison({
          container: $container[0],
          startPosition: 45,
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
      }
    });

    const videoObserver = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.play();
          } else {
            entry.target.pause();
          }
        });
      },
      {
        threshold: window.outerWidth <= 768 ? 0.8 : 0.75,
        rootMargin: window.outerWidth > 768 ? '-150px' : '0px'
      }
    );

    videoObserver.observe(document.getElementById('duo-story'));
  }
};
