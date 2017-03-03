jQuery(document).ready(function($) {
  if ($(".slider-revolution-video-gallery").length>0) {
    $(".slider-revolution-video-gallery").each(function(index, item) {
      $(this).revolution({
        sliderType:"standard",
        sliderLayout:"auto",
        dottedOverlay:"none",
        delay:9000,
        navigation: {
          keyboardNavigation:"off",
          keyboard_direction: "horizontal",
          mouseScrollNavigation:"off",
          onHoverStop:"off",
          arrows: {
            style:"uranus",
            enable:true,
            hide_onmobile:true,
            hide_under:778,
            hide_onleave:true,
            hide_delay:200,
            hide_delay_mobile:1200,
            tmp:'',
            left: {
              h_align:"left",
              v_align:"center",
              h_offset:20,
              v_offset:0
            },
            right: {
              h_align:"right",
              v_align:"center",
              h_offset:20,
              v_offset:0
            }
          },
          thumbnails: {
            style:"erinyen",
            enable:true,
            width:200,
            height:113,
            min_width:170,
            wrapper_padding:15,
            wrapper_color:"#ffffff",
            wrapper_opacity:"0.8",
            tmp:'<span class="tp-thumb-over"></span><span class="tp-thumb-image"></span><span class="tp-thumb-title">{{title}}</span><span class="tp-thumb-more"></span>',
            visibleAmount:10,
            hide_onmobile:false,
            hide_onleave:false,
            direction:"horizontal",
            span:true,
            position:"outer-bottom",
            space:15,
            h_align:"center",
            v_align:"bottom",
            h_offset:0,
            v_offset:0
          }
        },
        gridwidth:1170,
        gridheight:660,
        lazyType:"none",
        shadow:0,
        spinner:"spinner1",
        stopLoop:"on",
        stopAfterLoops:0,
        stopAtSlide:1,
        shuffle:"off",
        autoHeight:"off",
        disableProgressBar:"on",
        hideThumbsOnMobile:"off",
        hideSliderAtLimit:0,
        hideCaptionAtLimit:0,
        hideAllCaptionAtLilmit:0,
        debugMode:false,
        fallbacks: {
          simplifyAll:"off",
          nextSlideOnWindowFocus:"off",
          disableFocusListener:false,
        }
      });
    });
  }
});
