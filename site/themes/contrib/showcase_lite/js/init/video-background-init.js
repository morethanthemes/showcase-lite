jQuery(document).ready(function($) {
  $("body").addClass("video-bg-active");
  $(".video-bg-active .media-background").vide({
    mp4: drupalSettings.showcaseplus.VideoBackgroundInit.PathToVideo_mp4,
    webm: drupalSettings.showcaseplus.VideoBackgroundInit.PathToVideo_webm,
    poster: drupalSettings.showcaseplus.VideoBackgroundInit.pathToVideo_jpg
  },{
    posterType: 'jpg',
    className: 'video-container'
  });
});