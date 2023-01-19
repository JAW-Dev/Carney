export default {
  init() {
    if ($('.ie-gif').is(':visible')) {
      $('.ie-video').hide();
      $('.ie-gif').each(function updateSrc() {
        $(this).attr('src', $(this).data('src'));
      });
    }
  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  }
};
