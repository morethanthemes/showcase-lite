<?php

function showcase_lite_form_system_theme_settings_alter(&$form, &$form_state) {

  $form['#attached']['library'][] = 'showcase_lite/theme-settings';

  $form['mtt_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('MtT Theme Settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  $form['mtt_settings']['tabs'] = array(
    '#type' => 'vertical_tabs',
    '#default_tab' => 'basic_tab',
  );

  $form['mtt_settings']['basic_tab']['basic_settings'] = array(
    '#type' => 'details',
    '#title' => t('Basic Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['breadcrumb_separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Breadcrumb separator'),
    '#description'   => t('Enter the class of the icon you want from the Font Awesome library e.g.: fa-angle-right. A list of the available classes is provided here: <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet" target="_blank">http://fortawesome.github.io/Font-Awesome/cheatsheet</a>.'),
    '#default_value' => theme_get_setting('breadcrumb_separator', 'showcase_lite'),
    '#size'          => 20,
    '#maxlength'     => 100,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['page_container'] = array(
    '#type' => 'fieldset',
    '#title' => t('Full Page border'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['page_container']['page_container_border'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable full-page body border'),
    '#description'   => t('Adds a body border around your page, right inside the browser window.'),
    '#default_value' => theme_get_setting('page_container_border', 'showcase_lite'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['header'] = array(
    '#type' => 'fieldset',
    '#title' => t('Header positioning'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['header']['fixed_header'] = array(
    '#type' => 'checkbox',
    '#title' => t('Fixed Header'),
    '#description'   => t('Use the checkbox to apply fixed position to the header regions.'),
    '#default_value' => theme_get_setting('fixed_header', 'showcase_lite'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop'] = array(
    '#type' => 'fieldset',
    '#title' => t('Scroll to top'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop']['scroll_to_top_display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show scroll to top button'),
    '#description'   => t('Use the checkbox to enable or disable scroll-to-top button.'),
    '#default_value' => theme_get_setting('scroll_to_top_display', 'showcase_lite'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop']['scroll_to_top_icon'] = array(
    '#type' => 'textfield',
    '#title' => t('Scroll to top icon'),
    '#description'   => t('Enter the class of the icon you want from the Font Awesome library e.g.: fa-long-arrow-up. A list of the available classes is provided here: <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet" target="_blank">http://fortawesome.github.io/Font-Awesome/cheatsheet</a>.'),
    '#default_value' => theme_get_setting('scroll_to_top_icon','showcase_lite'),
    '#size'          => 20,
    '#maxlength'     => 100,
  );

  $form['mtt_settings']['basic_tab']['basic_settings']['scrolltop']['scroll_to_top_region'] = array(
    '#type' => 'select',
    '#title' => t('Region'),
    '#description'   => t('Select the region that you want the scroll-to-top button to be displayed in.'),
    '#default_value' => theme_get_setting('scroll_to_top_region', 'showcase_lite'),
    '#options' => array(
    'footer-to-top-enabled' => t('Footer'),
    'footer-bottom-to-top-enabled' => t('Footer Bottom'),
    'subfooter-to-top-enabled' => t('Subfooter'),
    ),
  );

  $form['mtt_settings']['bootstrap_tab']['bootstrap'] = array(
    '#type' => 'details',
    '#title' => t('Bootstrap'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['bootstrap_tab']['bootstrap']['bootstrap_remote_type'] = array(
    '#type' => 'select',
    '#title' => t('Select the remote type'),
    '#description'   => t('From the drop down select box, select how to load the Bootstrap library. If you select "Local" make sure that you download and place Bootstrap folder into the root theme folder (showcase_lite/bootstrap).'),
    '#default_value' => theme_get_setting('bootstrap_remote_type', 'showcase_lite'),
    '#options' => array(
    'local' => t('Local / No remote'),
    'cdn' => t('CDN'),
    ),
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel'] = array(
    '#type' => 'details',
    '#title' => t('Look\'n\'Feel'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['looknfeel_tab']['looknfeel']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-01-looknfeel.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['regions_tab']['regions'] = array(
    '#type' => 'details',
    '#title' => t('Region settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['regions_tab']['regions']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-02-region.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['post_tab']['post'] = array(
    '#type' => 'details',
    '#title' => t('Article features'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['post_tab']['post']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-03-article.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['layout_tab']['layout_modes'] = array(
    '#type' => 'details',
    '#title' => t('Theme Layout'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['layout_tab']['layout_modes']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-04-layout.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['font_tab']['font'] = array(
    '#type' => 'details',
    '#title' => t('Font Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['font_tab']['font']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-05-fonts.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['slideshows_tab']['slideshow'] = array(
    '#type' => 'details',
    '#title' => t('Slideshow Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['slideshows_tab']['slideshow']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-06-slideshow.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['video_bg_tab']['video_bg'] = array(
    '#type' => 'details',
    '#title' => t('Video Background region'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['video_bg_tab']['video_bg']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-07-videobackground.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['isotope_tab'] = array(
    '#type' => 'details',
    '#title' => t('Isotope Filters'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['isotope_tab']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-08-isotope.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['google_maps_tab'] = array(
    '#type' => 'details',
    '#title' => t('Google Maps Settings'),
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['google_maps_tab']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Available in the Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium-09-googlemaps.jpg" />
   </a></div> ',
  );

  $form['mtt_settings']['premium_tab'] = array(
    '#type' => 'details',
    '#title' => t('Premium version'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
    '#group' => 'tabs',
  );

  $form['mtt_settings']['premium_tab']['premium_description'] = array(
   '#type' => 'item',
   '#markup' =>
   '<div class="theme-settings-title">'.t("Premium version of this theme").'</div>
   <div class="theme-settings-image-wrapper">
   <a href="http://morethanthemes.com/themes/showcaseplus?utm_source=showcase-lite-demo&utm_medium=theme-settings&utm_campaign=free-themes" target="_blank">
   <img src="' . base_path() . drupal_get_path('theme', 'showcase_lite') . '/images/premium.jpg" />
   </a></div> ',
  );

}
