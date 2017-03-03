jQuery(document).ready(function($) {
  if ($('.header-container .header').length > 0 ) {
  var sticky = new Waypoint.Sticky({
    element: $('.header-container .header')[0],
    stuckClass: 'js-fixed',
    handler: function(direction) {
      if (Waypoint.viewportWidth() > 752) {
        $('body').toggleClass('onscroll');
      } else {
        $('body').removeClass('onscroll');
      }
      var topValue = $('body').css('padding-top');
      $(".header.js-fixed").css("top", topValue);
    },
    offset: function() {
      var offsetValue = parseInt($('body').css('padding-top')) - 1;
      return offsetValue;
    }
  });
  }
});
