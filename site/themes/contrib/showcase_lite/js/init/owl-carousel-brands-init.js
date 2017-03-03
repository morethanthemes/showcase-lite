jQuery(document).ready(function($) {
  $(".mt-carousel-brands").owlCarousel({
    itemsCustom: [[0, 2], [480, 3], [768, 4], [992, 5], [1200, 5]],
    autoPlay: drupalSettings.showcaseplus.owlCarouselBrandsInit.owlBrandsEffectTime,
    navigation: true,
    pagination: false,
    navigationText: false
  });
});
