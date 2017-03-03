jQuery(document).ready(function($) {
  if ($("body:not(.path-admin) [data-animate-effect]").length>0) {
    $("body:not(.path-admin) [data-animate-effect]").each(function() {
      var thisObject = $(this);
      var animation = thisObject.attr("data-animate-effect");
      if(animation != "no-animation") {
        var waypoints = thisObject.waypoint(function(direction) {
          var animatedObject = $(this.element);
          setTimeout(function() {
            animatedObject.addClass("animated in " + animation);
          }, 100);
          this.destroy();
        },{
          offset: "90%"
        });
      }
    });
  }
})
