jQuery(document).ready(function($) {
  var color = $(".media-background").css("background-color").replace(")", "," + drupalSettings.showcaseplus.VideoBg.VideoBgOpacity + ")").replace("rgb", "rgba");
  $(".media-background-transparent-bg").css("background-color", color);
});