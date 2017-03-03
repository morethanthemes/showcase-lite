jQuery(document).ready(function($) {
  if($(".banner .slideshow-fullscreen").length>0) {
    $(".header-container").addClass("js-transparent-header");
  } else {
    $(".header-container").removeClass("js-transparent-header");
  };
  var color = $(".header-container.js-transparent-header header.header").css("background-color").replace(")", "," + drupalSettings.showcaseplus.transparentHeader.transparentHeaderOpacity + ")").replace("rgb", "rgba");
  $(".header-container.js-transparent-header header.header").css("background-color", color);
});
