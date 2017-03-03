jQuery(document).ready(function($) {

  //Masonry Layout Style 2
  $(".masonry-container").each(function() {
    var $this = $(this);
    $this.fadeIn("slow");

    // load images first
    $this.imagesLoaded(function() {
      var blockId = $this.closest(".block").attr('id'),
      masonryItem = "#" + blockId + " .masonry-item";
      $this.isotope({
        itemSelector: masonryItem,
        layoutMode: "fitRows"
      });
      $this.isotope("layout");
    });

    // initialise inside bootstrap tab
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      $this.isotope('layout');
    });
  });

});
