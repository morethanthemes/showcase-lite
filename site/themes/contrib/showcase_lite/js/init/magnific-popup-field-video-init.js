jQuery(document).ready(function($) {
  $(window).load(function() {
    $(".iframe-popup > a").magnificPopup({
      disableOn: 700,
      type: "iframe",
      mainClass: "mfp-fade",
      removalDelay: 160,
      preloader: false,
      fixedContentPos: false,
      gallery: {
        enabled: true, // set to true to enable gallery
      }
    });
  });
});
