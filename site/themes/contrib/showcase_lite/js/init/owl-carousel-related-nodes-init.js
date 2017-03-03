jQuery(document).ready(function($) {
  $(".mt-carousel-related-nodes").owlCarousel({
    items: 4,
    itemsDesktopSmall: [992,2],
    itemsTablet: [768,2],
    autoPlay: drupalSettings.showcaseplus.owlCarouselRelatedNodesInit.owlRelatedNodesEffectTime,
    navigation: true,
    pagination: false,
    navigationText: false
  });
});
