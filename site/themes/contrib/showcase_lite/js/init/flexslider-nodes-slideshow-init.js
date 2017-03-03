jQuery(document).ready(function($) {

  $(window).load(function() {
    $(".nodes-slideshow").each(function() {
      $(this).fadeIn("slow");
    });
    $(".nodes-slideshow-navigation").each(function() {
      $(this).fadeIn("slow");
    });

    // The slider being synced must be initialized first
    $(".nodes-slideshow").each(function() {
      var blockId = $(this).closest(".block").attr('id'),
      nodesSlideshow = "#" + blockId + " .nodes-slideshow",
      nodesSlideshowThumbs = "#" + blockId + " .nodes-slideshow-navigation";

      $(nodesSlideshowThumbs).flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        directionNav: false,
        prevText: "",
        nextText: "",
        asNavFor: nodesSlideshow
      });
      $(nodesSlideshow).flexslider({
        useCSS: false,
        animation: "slide",
        controlNav: false,
        directionNav: false,
        prevText: "",
        nextText: "",
        animationLoop: false,
        slideshow: false,
        sync: nodesSlideshowThumbs
      });
    });

  });

  $(".nodes-slideshow-navigation .slides > li:first-child").each(function() {
    $(this).addClass("is-active");
  });
  $(".nodes-slideshow-navigation .slides > li").click(function() {
    $(this).addClass("is-active");
    $(this).siblings().removeClass("is-active");
  });

});
