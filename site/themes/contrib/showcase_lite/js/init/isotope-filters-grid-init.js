jQuery(document).ready(function($) {
  var masonryContainer = $(".isotope-container"),
  filtersMasonry = $(".view-promoted-items .filters");
  filtersMasonry.prepend( "<li class=\"active\"><a href=\"#\" data-filter=\"*\">" + drupalSettings.showcaseplus.isotopeFiltersGridInit.isotopeFiltersText + "</a></li>" );

  $(".isotope-container, .view-promoted-items .filters").fadeIn("slow");

  masonryContainer.imagesLoaded(function() {
    masonryContainer.isotope({
      itemSelector: ".isotope-item",
      layoutMode : drupalSettings.showcaseplus.isotopeFiltersGridInit.isotopeLayoutMode,
      transitionDuration: "0.6s",
      filter: "*"
    });
    filtersMasonry.find("a").click(function(){
      var $this = $(this);
      var selector = $this.attr("data-filter").replace(/\s+/g, "-");
      filtersMasonry.find("li.active").removeClass("active");
      $this.parent().addClass("active");
      masonryContainer.isotope({ filter: selector });
      return false;
    });
    masonryContainer.isotope("layout");
  });
});
