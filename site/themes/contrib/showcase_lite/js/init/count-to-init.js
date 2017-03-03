jQuery(document).ready(function($) {
  if ($("[data-to]").length>0) {
    $("[data-to]").each(function() {
      var stat_item = $(this);
      var waypoints = stat_item.waypoint(function(direction) {
        var animatedObject = $(this.element);
          animatedObject.countTo();
          this.destroy();
        },{
          offset: "90%"
        });
    });
  };
});
