import imagesLoaded from 'imagesloaded';
import Masonry from 'masonry-layout';
import jQueryBridget from 'jquery-bridget';

jQueryBridget('imagesLoaded', imagesLoaded, $);
jQueryBridget('masonry', Masonry, $);

export default {
  init() {
    $('.wp-block-carney-posts__list--past').imagesLoaded(() => {
      $('.wp-block-carney-posts__list--past').masonry({
        itemSelector: '.past-item',
        gutter: 32
      });
    });
  }
};
