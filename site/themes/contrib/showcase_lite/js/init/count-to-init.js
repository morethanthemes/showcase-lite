(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.mtCountTo = {
    attach: function (context, settings) {
      $(context).find('[data-to]').once('mtCountToInit').each(function() {
      var stat_item = $(this);
      var waypoints = stat_item.waypoint(function(direction) {
        var animatedObject = $(this.element);
          animatedObject.countTo();
          this.destroy();
        },{
          offset: "90%"
        });
    });
    }
  };
})(jQuery, Drupal, drupalSettings);
