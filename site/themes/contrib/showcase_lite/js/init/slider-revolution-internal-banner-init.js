jQuery(document).ready(function($) {
  if (drupalSettings.showcaseplus.sliderRevolutionInternalBannerInit.slideshowInternalBannerNavigationStyle == "bullets") {
    var bulletsEnable = true,
    tabsEnable = false;
  } else {
    var tabsEnable = true,
    bulletsEnable = false;
  }
  jQuery(".slideshow-internal .slider-revolution").revolution({
    sliderType:"standard",
    sliderLayout:"auto",
    gridwidth: [1170,970,750,450],
    gridheight: drupalSettings.showcaseplus.sliderRevolutionInternalBannerInit.slideshowInternalBannerInitialHeight,
    delay: drupalSettings.showcaseplus.sliderRevolutionInternalBannerInit.slideshowInternalBannerEffectTime,
    disableProgressBar:'off',
    responsiveLevels:[1199,991,767,480],
    navigation: {
      onHoverStop:"off",
      arrows:{
        enable:true,
        tmp: '',
        left:{
          h_align:"left",
          v_align:"center",
          h_offset:40,
          v_offset:0
        },
        right:{
          h_align:"right",
          v_align:"center",
          h_offset:40,
          v_offset:0
        }
      },
      bullets:{
        style:"",
        enable:bulletsEnable,
        direction:"horizontal",
        space: 5,
        h_align: drupalSettings.showcaseplus.sliderRevolutionInternalBannerInit.slideshowInternalBannerBulletsPosition,
        v_align:"bottom",
        h_offset: 0,
        v_offset: 20,
        tmp:"",
      },
      tabs: {
        style:"",
        enable:tabsEnable,
        width:410,
        height:95,
        min_width:240,
        wrapper_padding: 0,
        wrapper_opacity:"1",
        tmp:'<div class="tp-tab-content"><span class="tp-tab-title">{{title}}</span></div>',
        visibleAmount: 6,
        hide_onmobile: false,
        hide_onleave: false,
        direction:"horizontal",
        span: true,
        position:"outer-bottom",
        space:0,
        h_align:"left",
        v_align:"bottom",
        h_offset:0,
        v_offset:0
      },
      touch:{
        touchenabled: drupalSettings.showcaseplus.sliderRevolutionInternalBannerInit.slideshowInternalBannerTouchSwipe,
        swipe_treshold:75,
        swipe_min_touches:1,
        drag_block_vertical:false,
        swipe_direction:"horizontal"
      }
    }
  });

  $('.slideshow-internal .slider-revolution').bind("revolution.slide.onloaded",function (e) {
    $(".slider-revolution-wrapper:not(.one-slide) .tparrows").fadeIn("slow");
  });
  $(".transparent-background").css("backgroundColor", "rgba(0, 0, 0," + drupalSettings.showcaseplus.slideshowCaptionOpacity + ")");

});
