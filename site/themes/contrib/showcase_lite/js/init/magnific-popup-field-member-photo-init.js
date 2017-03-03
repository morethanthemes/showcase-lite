jQuery(document).ready(function($) {
  $(window).load(function() {
    $(".field--name-field-mt-member-photo a.image-popup").magnificPopup({
      type:"image",
      removalDelay: 300,
      mainClass: "mfp-fade",
      gallery: {
        enabled: true, // set to true to enable gallery
      },
      image: {
        titleSrc: function(item) {
          return item.el.closest('.overlay-container').children()[1].title || '';
        }
      }
    });
  });
});
