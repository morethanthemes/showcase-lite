jQuery(document).ready(function($) {
  $(".mt-carousel-testimonials").owlCarousel({
    singleItem: true,
    items: 1,
    itemsDesktop: [1200,1],
    itemsDesktopSmall: [992,1],
    itemsTablet: [768,1],
    autoPlay: drupalSettings.showcaseplus.owlCarouselTestimonialsInit.owlTestimonialsEffectTime,
    navigation: true,
    pagination: true,
    navigationText: false
  });
});
