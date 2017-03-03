jQuery(document).ready(function($) {
  $(".to-top").click(function() {
    $("body,html").animate({scrollTop:0},800);
  });
});
