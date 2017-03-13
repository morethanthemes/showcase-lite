jQuery(document).ready(function($) {

  // store the slider in a local variable
  var $window = $(window),
  flexslider;

  // tiny helper function to add breakpoints
  function getGridSize() {
    return (window.innerWidth < 768) ? 2 : 4;
  }

  $(window).load(function() {

    $(".in-page-images-slider").fadeIn("slow");
    $(".in-page-images-carousel").fadeIn("slow");

    // The slider being synced must be initialized first
    $(".in-page-images-carousel").flexslider({
      animation: "slide",
      controlNav: false,
      animationLoop: false,
      slideshow: false,
      itemWidth: 172.5,
      itemMargin: 20,
      prevText: "",
      nextText: "",
      asNavFor: ".in-page-images-slider",
      minItems: getGridSize(), // use function to pull in initial value
      maxItems: getGridSize(), // use function to pull in initial value
      start: function(slider){
        flexslider = slider;
      }
    });
    $(".in-page-images-slider").flexslider({
      useCSS: false,
      animation: drupalSettings.showcase_lite.flexsliderInPageInit.inPageSliderEffect,
      controlNav: false,
      directionNav: true,
      prevText: "",
      nextText: "",
      animationLoop: false,
      slideshow: false,
      sync: ".in-page-images-carousel"
    });

  });

  // check grid size on resize event
  $window.resize(function() {
    var gridSize = getGridSize();
    flexslider.vars.minItems = gridSize;
    flexslider.vars.maxItems = gridSize;
  });

});
