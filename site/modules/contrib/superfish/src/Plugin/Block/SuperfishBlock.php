<?php

namespace Drupal\superfish\Plugin\Block;

use Drupal\system\Plugin\Block\SystemMenuBlock;
use Drupal\Core\Menu\InaccessibleMenuLink;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Provides a "Superfish" block.
 *
 * @Block(
 *   id = "superfish",
 *   admin_label = @Translation("Superfish"),
 *   cache = -1,
 *   category = @Translation("Superfish"),
 *   deriver = "Drupal\system\Plugin\Derivative\SystemMenuBlock"
 * )
 */
class SuperfishBlock extends SystemMenuBlock {

  /**
   * Overrides \Drupal\block\BlockBase::blockForm().
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $defaults = $this->defaultConfiguration();
    $form['sf'] = array(
      '#type' => 'details',
      '#title' => $this->t('Block settings'),
      '#open' => TRUE,
    );
    $form['sf']['superfish_type'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Menu type'),
      '#description' => '<em>(' . $this->t('Default') . ': ' . $this->t('Horizontal (single row)') . ')</em>',
      '#default_value' => $this->configuration['menu_type'],
      '#options' => array(
        'horizontal' => $this->t('Horizontal (single row)'),
        'navbar' => $this->t('Horizontal (double row)'),
        'vertical' => $this->t('Vertical (stack)')
      ),
    );
    $form['sf']['superfish_style'] = array(
      '#type' => 'select',
      '#title' => $this->t('Style'),
      '#description' => '<em>(' . $this->t('Default') . ': ' . $this->t('None') . ')</em>',
      '#default_value' => $this->configuration['style'],
      '#options' => array(
        'none' => $this->t('None'),
        'default' => $this->t('Default'),
        'black' => $this->t('Black'),
        'blue' => $this->t('Blue'),
        'coffee' => $this->t('Coffee'),
        'white' => $this->t('White')
      )
    );
    $form['sf']['superfish_arrow'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Add arrows to parent menus'),
      '#default_value' => $this->configuration['arrow'],
    );
    $form['sf']['superfish_shadow'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Drop shadows'),
      '#default_value' => $this->configuration['shadow'],
    );
    $form['sf']['superfish_slide'] = array(
      '#type' => 'select',
      '#title' => $this->t('Slide-in effect'),
      '#description' => '<em>(' . $this->t('Default') . ': ' . $this->t('Vertical') . ')</em><br />' . ((count(superfish_effects()) == 4) ? $this->t('jQuery Easing plugin is not installed.') . '<br />' . $this->t('The plugin provides a handful number of animation effects, they can be used by uploading the \'jquery.easing.js\' file to the libraries directory within the \'easing\' directory (for example: sites/all/libraries/easing/jquery.easing.js). Refresh this page after the plugin is uploaded, this will make more effects available in the above list.') . '<br />' : ''),
      '#default_value' => $this->configuration['slide'],
      '#options' => superfish_effects(),
    );
    $form['sf-plugins'] = array(
      '#type' => 'details',
      '#title' => $this->t('Superfish plugins'),
      '#open' => TRUE,
    );
    $form['sf-plugins']['superfish_supposition'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('jQuery Supposition'),
      '#description' => $this->t('Relocates sub-menus when they would otherwise appear outside the browser window area.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('enabled') . ')</em>',
      '#default_value' => $this->configuration['supposition'],
    );
    $form['sf-plugins']['superfish_hoverintent'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('jQuery hoverIntent'),
      '#description' => $this->t('Prevents accidental firing of animations by waiting until the user\'s mouse slows down enough, hence determinig user\'s <em>intent</em>.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('enabled') . ')</em>',
      '#default_value' => $this->configuration['hoverintent'],
    );
    $form['sf-plugins']['sf-touchscreen'] = array(
      '#type' => 'details',
      '#title' => $this->t('sf-Touchscreen'),
      '#description' => $this->t('<strong>sf-Touchscreen</strong> provides touchscreen compatibility.') . ' <sup>(' . $this->t('The first click on a parent hyperlink shows its children and the second click opens the hyperlink.') . ')</sup>',
      '#open' => FALSE,
    );
    $form['sf-plugins']['sf-touchscreen']['superfish_touch'] = array(
      '#type' => 'radios',
      '#default_value' => $this->configuration['touch'],
      '#options' => array(
        0 => $this->t('Disable') . '. <sup>(' . $this->t('Default') . ')</sup>',
        1 => $this->t('Enable jQuery sf-Touchscreen plugin for this menu.'),
        2 => $this->t('Enable jQuery sf-Touchscreen plugin for this menu depending on the user\'s Web browser <strong>window width</strong>.'),
        3 => $this->t('Enable jQuery sf-Touchscreen plugin for this menu depending on the user\'s Web browser <strong>user agent</strong>.'),
      ),
    );
    $form['sf-plugins']['sf-touchscreen']['superfish_touchbh'] = array(
      '#type' => 'radios',
      '#title' => 'Select a behaviour',
      '#description' => $this->t('Using this plugin, the first click or tap will expand the sub-menu, here you can choose what a second click or tap should do.'),
      '#default_value' => $this->configuration['touchbh'],
      '#options' => array(
        0 => $this->t('Opening the parent menu item link on the second tap.'),
        1 => $this->t('Hiding the sub-menu on the second tap.'),
        2 => $this->t('Hiding the sub-menu on the second tap, adding cloned parent links to the top of sub-menus as well.') . ' <sup>(' . $this->t('Default') . ')</sup>',
      ),
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-windowwidth'] = array(
      '#type' => 'details',
      '#title' => $this->t('Window width settings'),
      '#description' => $this->t('sf-Touchscreen will be enabled only if the width of user\'s Web browser window is smaller than the below value.') . '<br /><br />' . $this->t('Please note that in most cases such a meta tag is necessary for this feature to work properly:') . '<br /><code>&lt;meta name="viewport" content="width=device-width, initial-scale=1.0" /&gt;</code>',
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-windowwidth']['superfish_touchbp'] = array(
      '#type' => 'textfield',
      '#description' => $this->t('Also known as "Breakpoint".') . ' <em>(' . $this->t('Default') . ': 768)</em>',
      '#default_value' => $this->configuration['touchbp'],
      '#field_suffix' => $this->t('pixels'),
      '#size' => 10,
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent'] = array(
      '#type' => 'details',
      '#title' => $this->t('User agent settings'),
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchua'] = array(
      '#type' => 'radios',
      '#default_value' => $this->configuration['touchua'],
      '#options' => array(
        0 => $this->t('Use the pre-defined list of the <strong>user agents</strong>.') . '<sup>(' . $this->t('Default') . ') (' . $this->t('Recommended') . ')</sup>',
        1 => $this->t('Use the custom list of the <strong>user agents</strong>.'),
      ),
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchual'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Custom list of the user agents'),
      '#description' => $this->t('Could be partial or complete. (Asterisk separated)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Examples') . ':<ul><li>iPhone*Android*iPad <sup>(' . $this->t('Recommended') . ')</sup></li><li>Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 Pre/1.0 * Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405</li></ul>' . ((isset($_SERVER['HTTP_USER_AGENT'])) ? '<br />' . $this->t('<strong>UA string of the current Web browser:</strong>') . ' ' . $_SERVER['HTTP_USER_AGENT'] : ''),
      '#default_value' => $this->configuration['touchual'],
      '#size' => 100,
      '#maxlength' => 2000,
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchuam'] = array(
      '#type' => 'select',
      '#title' => $this->t('<strong>User agent</strong> detection method'),
      '#description' => '<em>(' . $this->t('Default') . ': ' . $this->t('Client-side (JavaScript)') . ')</em>',
      '#default_value' => $this->configuration['touchuam'],
      '#options' => array(
        0 => $this->t('Client-side (JavaScript)'),
        1 => $this->t('Server-side (PHP)')
      ),
    );
    $form['sf-plugins']['sf-smallscreen'] = array(
      '#type' => 'details',
      '#title' => $this->t('sf-Smallscreen'),
      '#description' => $this->t('<strong>sf-Smallscreen</strong> provides small-screen compatibility for your menus.') . ' <sup>(' . $this->t('Converts the dropdown into a &lt;select&gt; element.') . ')</sup>',
      '#open' => FALSE,
    );
    $form['sf-plugins']['sf-smallscreen']['superfish_small'] = array(
      '#type' => 'radios',
      '#default_value' => $this->configuration['small'],
      '#options' => array(
        0 => $this->t('Disable') . '.',
        1 => $this->t('Enable jQuery sf-Smallscreen plugin for this menu.'),
        2 => $this->t('Enable jQuery sf-Smallscreen plugin for this menu depending on the user\'s Web browser <strong>window width</strong>.') . ' <sup>(' . $this->t('Default') . ')</sup>',
        3 => $this->t('Enable jQuery sf-Smallscreen plugin for this menu depending on the user\'s Web browser <strong>user agent</strong>.'),
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-windowwidth'] = array(
      '#type' => 'details',
      '#title' => $this->t('Window width settings'),
      '#description' => $this->t('sf-Smallscreen will be enabled only if the width of user\'s Web browser window is smaller than the below value.') . '<br /><br />' . $this->t('Please note that in most cases such a meta tag is necessary for this feature to work properly:') . '<br /><code>&lt;meta name="viewport" content="width=device-width, initial-scale=1.0" /&gt;</code>',
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-windowwidth']['superfish_smallbp'] = array(
      '#type' => 'textfield',
      '#description' => $this->t('Also known as "Breakpoint".') . ' <em>(' . $this->t('Default') . ': 768)</em>',
      '#default_value' => $this->configuration['smallbp'],
      '#field_suffix' => $this->t('pixels'),
      '#size' => 10,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent'] = array(
      '#type' => 'details',
      '#title' => $this->t('User agent settings'),
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smallua'] = array(
      '#type' => 'radios',
      '#default_value' => $this->configuration['smallua'],
      '#options' => array(
        0 => $this->t('Use the pre-defined list of the <strong>user agents</strong>.') . '<sup>(' . $this->t('Default') . ') (' . $this->t('Recommended') . ')</sup>',
        1 => $this->t('Use the custom list of the <strong>user agents</strong>.'),
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smallual'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Custom list of the user agents'),
      '#description' => $this->t('Could be partial or complete. (Asterisk separated)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Examples') . ':<ul><li>iPhone*Android*iPad <sup>(' . $this->t('Recommended') . ')</sup></li><li>Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2 (KHTML, like Gecko) Version/1.0 Safari/532.2 Pre/1.0 * Mozilla/5.0 (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Mobile/7B405</li></ul>' . ((isset($_SERVER['HTTP_USER_AGENT'])) ? '<br />' . $this->t('<strong>UA string of the current Web browser:</strong>') . ' ' . $_SERVER['HTTP_USER_AGENT'] : ''),
      '#default_value' => $this->configuration['smallual'],
      '#size' => 100,
      '#maxlength' => 2000,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smalluam'] = array(
      '#type' => 'select',
      '#title' => $this->t('<strong>User agent</strong> detection method'),
      '#description' => '<em>(' . $this->t('Default') . ': ' . $this->t('Client-side (JavaScript)') . ')</em>',
      '#default_value' => $this->configuration['smalluam'],
      '#options' => array(
        0 => $this->t('Client-side (JavaScript)'),
        1 => $this->t('Server-side (PHP)')
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['superfish_smallact'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Select a type'),
      '#default_value' => $this->configuration['smallact'],
      '#options' => array(
        0 => $this->t('Convert the menu to a &lt;select&gt; element.'),
        1 => $this->t('Convert the menu to an accordion menu.') . ' <sup>(' . $this->t('Default') . ')</sup>',
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select'] = array(
      '#type' => 'details',
      '#title' => $this->t('&lt;select&gt; settings'),
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['superfish_smallset'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('&lt;select&gt; title'),
      '#description' => $this->t('By default the first item in the &lt;select&gt; element will be the name of the parent menu or the title of this block, you can change this by setting a custom title.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Example') . ': <em> - ' . $this->t('Main Menu') . ' - </em>.',
      '#default_value' => $this->configuration['smallset'],
      '#size' => 50,
      '#maxlength' => 500,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['superfish_smallasa'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Add <em>selected</em> attribute to the &lt;option&gt; element with the class <strong>active</strong> .'),
      '#description' => $this->t('Makes pre-selected the item linked to the active page when the page loads.'),
      '#default_value' => $this->configuration['smallasa'],
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more'] = array(
      '#type' => 'details',
      '#title' => $this->t('More'),
      '#open' => FALSE,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallcmc'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Copy the main &lt;ul&gt; classes to the &lt;select&gt;.') . ' <sup><em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em></sup>',
      '#default_value' => $this->configuration['smallcmc'],
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallecm'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Exclude these classes from the &lt;select&gt; element'),
      '#description' => $this->t('Comma separated') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em>',
      '#default_value' => $this->configuration['smallecm'],
      '#size' => 100,
      '#maxlength' => 1000,
      '#states' => array(
        'enabled' => array(
         ':input[name="superfish_smallcmc' . '"]' => array('checked' => TRUE),
        ),
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallchc'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Copy the hyperlink classes to the &lt;option&gt; elements of the &lt;select&gt;.') . ' <sup><em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em></sup>',
      '#default_value' => $this->configuration['smallchc'],
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallech'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Exclude these classes from the &lt;option&gt; elements of the &lt;select&gt;'),
      '#description' => $this->t('Comma separated') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em>',
      '#default_value' => $this->configuration['smallech'],
      '#size' => 100,
      '#maxlength' => 1000,
      '#states' => array(
        'enabled' => array(
         ':input[name="superfish_smallchc' . '"]' => array('checked' => TRUE),
        ),
      ),
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallicm'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Include these classes in the &lt;select&gt; element'),
      '#description' => $this->t('Comma separated') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em>',
      '#default_value' => $this->configuration['smallicm'],
      '#size' => 100,
      '#maxlength' => 1000,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallich'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Include these classes in the &lt;option&gt; elements of the &lt;select&gt;'),
      '#description' => $this->t('Comma separated') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em>',
      '#default_value' => $this->configuration['smallich'],
      '#size' => 100,
      '#maxlength' => 1000,
    );
  $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion'] = array(
      '#type' => 'details',
      '#title' => $this->t('Accordion settings'),
      '#open' => TRUE,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion']['superfish_smallamt'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Accordion menu title'),
      '#description' => $this->t('By default the caption of the accordion toggle switch will be the name of the parent menu or the title of this block, you can change this by setting a custom title.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Example') . ': <em>' . $this->t('Menu') . '</em>.',
      '#default_value' => $this->configuration['smallamt'],
      '#size' => 50,
      '#maxlength' => 500,
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion']['superfish_smallabt'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Accordion button type'),
      '#default_value' => $this->configuration['smallabt'],
      '#options' => array(
        0 => $this->t('Use parent menu items as buttons.'),
        1 => $this->t('Use parent menu items as buttons, add cloned parent links to sub-menus as well.') . ' <sup>(' . $this->t('Default') . ')</sup>',
        2 => $this->t('Create new links next to parent menu item links and use them as buttons.'),
      ),
    );
    $form['sf-plugins']['sf-supersubs'] = array(
      '#type' => 'details',
      '#title' => $this->t('Supersubs'),
      '#description' => $this->t('<strong>Supersubs</strong> makes it possible to define custom widths for your menus.'),
      '#open' => FALSE,
    );
    $form['sf-plugins']['sf-supersubs']['superfish_supersubs'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Supersubs for this menu.'),
      '#default_value' => $this->configuration['supersubs'],
    );
    $form['sf-plugins']['sf-supersubs']['superfish_minwidth'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Minimum width'),
      '#description' => $this->t('Minimum width for sub-menus, in <strong>em</strong> units.') . ' <em>(' . $this->t('Default') . ': 12)</em>',
      '#default_value' => $this->configuration['minwidth'],
      '#size' => 10,
    );
    $form['sf-plugins']['sf-supersubs']['superfish_maxwidth'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Maximum width'),
      '#description' => $this->t('Maximum width for sub-menus, in <strong>em</strong> units.') . ' <em>(' . $this->t('Default') . ': 27)</em>',
      '#default_value' => $this->configuration['maxwidth'],
      '#size' => 10,
    );
    $form['sf-multicolumn'] = array(
      '#type' => 'details',
      '#title' => $this->t('Multi-column sub-menus'),
      '#open' => FALSE,
    );
    $form['sf-multicolumn']['superfish_multicolumn'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable multi-column sub-menus.'),
      '#default_value' => $this->configuration['multicolumn'],
    );
    $form['sf-multicolumn']['superfish_multicolumn_depth'] = array(
      '#type' => 'select',
      '#title' => $this->t('Start from depth'),
      '#description' => $this->t('The depth of the first instance of multi-column sub-menus.') . ' <em>(' . $this->t('Default') . ': 1)</em>',
      '#default_value' => $this->configuration['multicolumn_depth'],
      '#options' => array_combine(range(1, 10),range(1, 10)),
    );
    $form['sf-multicolumn']['superfish_multicolumn_levels'] = array(
      '#type' => 'select',
      '#title' => $this->t('Levels'),
      '#description' => $this->t('The amount of sub-menu levels that will be included in the multi-column sub-menu.') . ' <em>(' . $this->t('Default') . ': 1)</em>',
      '#default_value' => $this->configuration['multicolumn_levels'],
      '#options' => array_combine(range(1, 10),range(1, 10)),
    );
    $form['sf-advanced'] = array(
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    );
    $form['sf-advanced']['sf-settings'] = array(
      '#type' => 'details',
      '#title' => $this->t('Superfish'),
      '#open' => FALSE,
    );
    $form['sf-advanced']['sf-settings']['superfish_speed'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Animation speed'),
      '#description' => $this->t('The speed of the animation either in <strong>milliseconds</strong> or pre-defined values (<strong>slow, normal, fast</strong>).') . ' <em>(' . $this->t('Default') . ': fast)</em>',
      '#default_value' => $this->configuration['speed'],
      '#size' => 15,
    );
    $form['sf-advanced']['sf-settings']['superfish_delay'] = array(
      '#type' => 'number',
      '#title' => $this->t('Mouse delay'),
      '#description' => $this->t('The delay in <strong>milliseconds</strong> that the mouse can remain outside a sub-menu without it closing.') . ' <em>(' . $this->t('Default') . ': 800)</em>',
      '#default_value' => $this->configuration['delay'],
      '#size' => 15,
    );
    $form['sf-advanced']['sf-settings']['superfish_pathlevels'] = array(
      '#type' => 'select',
      '#title' => $this->t('Path levels'),
      '#description' => $this->t('The amount of sub-menu levels that remain open or are restored using the ".active-trail" class.') . ' <em>(' . $this->t('Default') . ': 1)</em><br />' . $this->t('Change this setting <strong>only and only</strong> if you are <strong>totally sure</strong> of what you are doing.'),
      '#default_value' => $this->configuration['pathlevels'],
      '#options' => array_combine(range(0, 10),range(0, 10)),
    );
    $form['sf-advanced']['sf-hyperlinks'] = array(
      '#type' => 'details',
      '#title' => $this->t('Hyperlinks'),
      '#open' => TRUE,
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_expanded'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Take "Expanded" option into effect.'),
      '#description' => $this->t('By enabling this option, only parent menu items with <em>Expanded</em> option enabled will have their submenus appear.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em>',
      '#default_value' => $this->configuration['expanded'],
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_clone_parent'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Add cloned parent links to the top of sub-menus.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em>',
      '#default_value' => $this->configuration['clone_parent'],
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_hide_linkdescription'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Disable hyperlink descriptions ("title" attribute)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em>',
      '#default_value' => $this->configuration['hide_linkdescription'],
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_add_linkdescription'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Insert hyperlink descriptions ("title" attribute) into hyperlink texts.') . ' <em>(' . $this->t('Default') . ': ' . $this->t('disabled') . ')</em>',
      '#default_value' => $this->configuration['add_linkdescription'],
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_itemdepth'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Add <strong>item depth</strong> class to menu items and their hyperlinks.') . '<em>(sf-depth-1, sf-depth-2, sf-depth-3, ...)</em> <em>(' . $this->t('Default') . ': ' . $this->t('enabled') . ')</em>',
      '#default_value' => $this->configuration['link_depth_class'],
    );
    $form['sf-advanced']['sf-custom-classes'] = array(
      '#type' => 'details',
      '#title' => $this->t('Custom classes'),
      '#open' => TRUE,
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_ulclass'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('For the main UL'),
      '#description' => $this->t('(Space separated, without dots)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Example') . ': top-menu category-science',
      '#default_value' => $this->configuration['custom_list_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_liclass'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('For the list items'),
      '#description' => $this->t('(Space separated, without dots)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Example') . ': science-sub',
      '#default_value' => $this->configuration['custom_item_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_hlclass'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('For the hyperlinks'),
      '#description' => $this->t('(Space separated, without dots)') . ' <em>(' . $this->t('Default') . ': ' . $this->t('empty') . ')</em><br />' . $this->t('Example') . ': science-link',
      '#default_value' => $this->configuration['custom_link_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    );
    return $form;
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockValiate().
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    /**
     // Commented out for now as I couldn't get validation to work, with RC4 at least.

    $touch = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touch'));
    $touchbp = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-windowwidth', 'superfish_touchbp'));
    $touchua = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touchua'));
    $touchual = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touchual'));
    $small = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_small'));
    $smallbp = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smallbp'));
    $smallua = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smallua'));
    $smallual = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smallual'));
    $minwidth = $form_state->getValue(array('sf-plugins', 'sf-supersubs', 'superfish_minwidth'));
    $maxwidth = $form_state->getValue(array('sf-plugins', 'sf-supersubs', 'superfish_maxwidth'));
    $speed = $form_state->getValue(array('sf-advanced', 'sf-settings', 'superfish_speed'));
    $delay = $form_state->getValue(array('sf-advanced', 'sf-settings', 'superfish_delay'));

    if (!is_numeric($speed) && !in_array($speed, array('slow', 'normal', 'fast'))) {
      $form_state->setErrorByName('superfish_speed', t('Unacceptable value entered for the "Animation speed" option.'));
    }
    if (!is_numeric($delay)) {
      $form_state->setErrorByName('superfish_delay', t('Unacceptable value entered for the "Mouse delay" option.'));
    }
    if ($touch == 2 && $touchbp == '') {
      $form_state->setErrorByName('superfish_touchbp', t('"sfTouchscreen Breakpoint" option cannot be empty.'));
    }
    if (!is_numeric($touchbp)) {
      $form_state->setErrorByName('superfish_touchbp', t('Unacceptable value enterd for the "sfTouchscreen Breakpoint" option.'));
    }
    if ($touch == 3 && $touchua == 1 && $touchual == '') {
      $form_state->setErrorByName('superfish_touchual', t('List of the touch-screen user agents cannot be empty.'));
    }
    if ($small == 2 && $smallbp == '') {
      $form_state->setErrorByName('superfish_smallbp', t('"sfSmallscreen Breakpoint" option cannot be empty.'));
    }
    if (!is_numeric($smallbp)) {
      $form_state->setErrorByName('superfish_smallbp', t('Unacceptable value entered for the "sfSmallscreen Breakpoint" option.'));
    }
    if ($small == 3 && $smallua == 1 && $smallual == '') {
      $form_state->setErrorByName('superfish_smallual', t('List of the small-screen user agents cannot be empty.'));
    }

    $supersubs_error = FALSE;
    if (!is_numeric($minwidth)) {
      $form_state->setErrorByName('superfish_minwidth', t('Unacceptable value entered for the "Supersubs minimum width" option.'));
      $supersubs_error = TRUE;
    }
    if (!is_numeric($maxwidth)) {
      $form_state->setErrorByName('superfish_maxwidth', t('Unacceptable value entered for the "Supersubs maximum width" option.'));
      $supersubs_error = TRUE;
    }
    if ($supersubs_error !== TRUE && $minwidth > $maxwidth) {
      $form_state->setErrorByName('superfish_maxwidth', t('Supersubs "maximum width" has to be bigger than the "minimum width".'));
    }

    parent::blockValidate($form, $form_state);
    */
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    $this->configuration['level'] = $form_state->getValue('level');
    $this->configuration['depth'] = $form_state->getValue('depth');
    $this->configuration['menu_type'] = $form_state->getValue(array('sf', 'superfish_type'));
    $this->configuration['style'] = $form_state->getValue(array('sf', 'superfish_style'));
    $this->configuration['arrow'] = $form_state->getValue(array('sf', 'superfish_arrow'));
    $this->configuration['shadow'] = $form_state->getValue(array('sf', 'superfish_shadow'));
    $this->configuration['slide'] = $form_state->getValue(array('sf', 'superfish_slide'));

    $this->configuration['supposition'] = $form_state->getValue(array('sf-plugins', 'superfish_supposition'));
    $this->configuration['hoverintent'] = $form_state->getValue(array('sf-plugins', 'superfish_hoverintent'));

    $this->configuration['touch'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'superfish_touch'));
    $this->configuration['touchbh'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'superfish_touchbh'));
    $this->configuration['touchbp'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-windowwidth', 'superfish_touchbp'));
    $this->configuration['touchua'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touchua'));
    $this->configuration['touchual'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touchual'));
    $this->configuration['touchuam'] = $form_state->getValue(array('sf-plugins', 'sf-touchscreen', 'sf-touchscreen-useragent', 'superfish_touchuam'));

    $this->configuration['small'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'superfish_small'));
    $this->configuration['smallact'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'superfish_smallact'));
    $this->configuration['smallbp'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-windowwidth', 'superfish_smallbp'));
    $this->configuration['smallua'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smallua'));
    $this->configuration['smallual'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smallual'));
    $this->configuration['smalluam'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-useragent', 'superfish_smalluam'));
    $this->configuration['smallset'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'superfish_smallset'));
    $this->configuration['smallasa'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'superfish_smallasa'));
    $this->configuration['smallcmc'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallcmc'));
    $this->configuration['smallecm'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallecm'));
    $this->configuration['smallchc'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallchc'));
    $this->configuration['smallech'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallech'));
    $this->configuration['smallicm'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallicm'));
    $this->configuration['smallich'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-select', 'sf-smallscreen-select-more', 'superfish_smallich'));
    $this->configuration['smallamt'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-accordion', 'superfish_smallamt'));
    $this->configuration['smallabt'] = $form_state->getValue(array('sf-plugins', 'sf-smallscreen', 'sf-smallscreen-accordion', 'superfish_smallabt'));

    $this->configuration['supersubs'] = $form_state->getValue(array('sf-plugins', 'sf-supersubs', 'superfish_supersubs'));
    $this->configuration['minwidth'] = $form_state->getValue(array('sf-plugins', 'sf-supersubs', 'superfish_minwidth'));
    $this->configuration['maxwidth'] = $form_state->getValue(array('sf-plugins', 'sf-supersubs', 'superfish_maxwidth'));

    $this->configuration['multicolumn'] = $form_state->getValue(array('sf-multicolumn', 'superfish_multicolumn'));
    $this->configuration['multicolumn_depth'] = $form_state->getValue(array('sf-multicolumn', 'superfish_multicolumn_depth'));
    $this->configuration['multicolumn_levels'] = $form_state->getValue(array('sf-multicolumn', 'superfish_multicolumn_levels'));

    $this->configuration['speed'] = $form_state->getValue(array('sf-advanced', 'sf-settings', 'superfish_speed'));
    $this->configuration['delay'] = $form_state->getValue(array('sf-advanced', 'sf-settings', 'superfish_delay'));
    $this->configuration['pathlevels'] = $form_state->getValue(array('sf-advanced', 'sf-settings', 'superfish_pathlevels'));
    $this->configuration['expanded'] = $form_state->getValue(array('sf-advanced', 'sf-hyperlinks', 'superfish_expanded'));
    $this->configuration['clone_parent'] = $form_state->getValue(array('sf-advanced', 'sf-hyperlinks', 'superfish_clone_parent'));
    $this->configuration['hide_linkdescription'] = $form_state->getValue(array('sf-advanced', 'sf-hyperlinks', 'superfish_hide_linkdescription'));
    $this->configuration['add_linkdescription'] = $form_state->getValue(array('sf-advanced', 'sf-hyperlinks', 'superfish_add_linkdescription'));
    $this->configuration['link_depth_class'] = $form_state->getValue(array('sf-advanced', 'sf-hyperlinks', 'superfish_itemdepth'));
    $this->configuration['custom_list_class'] = $form_state->getValue(array('sf-advanced', 'sf-custom-classes', 'superfish_ulclass'));
    $this->configuration['custom_item_class'] = $form_state->getValue(array('sf-advanced', 'sf-custom-classes', 'superfish_liclass'));
    $this->configuration['custom_link_class'] = $form_state->getValue(array('sf-advanced', 'sf-custom-classes', 'superfish_hlclass'));
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {

  $build = array();

    // Block settings which will be passed to the Superfish themes.
    $sfsettings = array();
    $sfsettings['level']               = $this->configuration['level'];
    $sfsettings['depth']                = $this->configuration['depth'];
    $sfsettings['menu_type']            = $this->configuration['menu_type'];
    $sfsettings['style']                = $this->configuration['style'];
    $sfsettings['expanded']             = $this->configuration['expanded'];
    $sfsettings['itemdepth']            = $this->configuration['link_depth_class'];
    $sfsettings['ulclass']              = $this->configuration['custom_list_class'];
    $sfsettings['liclass']              = $this->configuration['custom_item_class'];
    $sfsettings['hlclass']              = $this->configuration['custom_link_class'];
    $sfsettings['clone_parent']         = $this->configuration['clone_parent'];
    $sfsettings['hide_linkdescription'] = $this->configuration['hide_linkdescription'];
    $sfsettings['add_linkdescription']  = $this->configuration['add_linkdescription'];
    $sfsettings['multicolumn']          = $this->configuration['multicolumn'];
    $sfsettings['multicolumn_depth']    = ($this->configuration['menu_type'] == 'navbar' && $this->configuration['multicolumn_depth'] == 1) ? 2 : $this->configuration['multicolumn_depth'];
    $sfsettings['multicolumn_levels']   = $this->configuration['multicolumn_levels'] + $sfsettings['multicolumn_depth'];

    // jQuery plugin options which will be passed to the Drupal behavior.
    $sfoptions = array();
    $sfoptions['pathClass'] = ($sfsettings['menu_type'] == 'navbar') ? 'active-trail' : '';
    $sfoptions['pathLevels'] = ($this->configuration['pathlevels'] != 1) ? $this->configuration['pathlevels'] : '';
    $sfoptions['delay'] = ($this->configuration['delay'] != 800) ? $this->configuration['delay'] : '';
    $sfoptions['animation']['opacity'] = 'show';
    $slide = $this->configuration['slide'];
    if (strpos($slide, '_')) {
      $slide = explode('_', $slide);
      switch ($slide[1]) {
        case 'vertical':
          $sfoptions['animation']['height'] = array('show', $slide[0]);
        break;
        case 'horizontal':
          $sfoptions['animation']['width'] = array('show', $slide[0]);
        break;
        case 'diagonal':
          $sfoptions['animation']['height'] = array('show', $slide[0]);
          $sfoptions['animation']['width'] = array('show', $slide[0]);
        break;
      }
      $build['#attached']['library'][] = 'superfish/superfish_easing';
    }
    else {
      switch ($slide) {
        case 'vertical':
          $sfoptions['animation']['height'] = 'show';
        break;
        case 'horizontal':
          $sfoptions['animation']['width'] = 'show';
        break;
        case 'diagonal':
          $sfoptions['animation']['height'] = 'show';
          $sfoptions['animation']['width'] = 'show';
        break;
      }
    }
    $speed = $this->configuration['speed'];
    if ($speed != 'normal') {
      $sfoptions['speed'] = ((is_numeric($speed)) ? (int)$speed : (($speed == ('slow' || 'normal' || 'fast')) ? $speed : ''));
    }
    $sfoptions['autoArrows'] = ($this->configuration['arrow'] == 0) ? FALSE : '';
    $sfoptions['dropShadows'] = ($this->configuration['shadow'] == 0) ? FALSE : '';

    if ($this->configuration['hoverintent']) {
      $build['#attached']['library'][] = 'superfish/superfish_hoverintent';
    }
    else {
      $sfoptions['disableHI'] = TRUE;
    }
    $sfoptions = superfish_array_remove_empty($sfoptions);

    // Options for Superfish sub-plugins.
    $sfplugins = array();
    $touchscreen = $this->configuration['touch'];
    if ($touchscreen) {
      $build['#attached']['library'][] = 'superfish/superfish_touchscreen';
      $behaviour = $this->configuration['touchbh'];
      $sfplugins['touchscreen']['behaviour'] = ($behaviour != 2) ? $behaviour : '';
      switch ($touchscreen) {
        case 1 :
          $sfplugins['touchscreen']['mode'] = 'always_active';
        break;
        case 2 :
          $sfplugins['touchscreen']['mode'] = 'window_width';
          $tsbp = $this->configuration['touchbp'];
          $sfplugins['touchscreen']['breakpoint'] = ($tsbp != 768) ? (int)$tsbp : '';
        break;
        case 3 :
          // Which method to use for UA detection.
          $tsuam = $this->configuration['touchuam'];
          $tsua = $this->configuration['touchua'];
          switch ($tsuam) {
            // Client-side.
            case 0 :
              switch ($tsua) {
                case 0 :
                  $sfplugins['touchscreen']['mode'] = 'useragent_predefined';
                break;
                case 1 :
                  $sfplugins['touchscreen']['mode'] = 'useragent_custom';
                  $tsual = drupal_strtolower($this->configuration['touchual']);
                  if (strpos($tsual, '*')) {
                    $tsual = str_replace('*', '|', $tsual);
                  }
                  $sfplugins['touchscreen']['useragent'] = $tsual;
                break;
              }
            break;
            // Server-side.
            case 1 :
              if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $hua = drupal_strtolower($_SERVER['HTTP_USER_AGENT']);
                switch ($tsua) {
                  // Use the pre-defined list of mobile UA strings.
                  case 0 :
                    if (preg_match('/(android|bb\d+|meego)|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $hua)) {
                      $sfplugins['touchscreen']['mode'] = 'always_active';
                      if ($behaviour == 2) {
                        $sfsettings['clone_parent'] = 1;
                      }
                    }
                  break;
                  // Use the custom list of UA strings.
                  case 1 :
                    $tsual = drupal_strtolower($this->configuration['touchual']);
                    $tsuac = array();
                    if (strpos($tsual, '*')) {
                      $tsual = explode('*', $tsual);
                      foreach ($tsual as $ua) {
                        $tsuac[] = (strpos($hua, $ua)) ? 1 : 0;
                      }
                    }
                    else {
                      $tsuac[] = (strpos($hua, $tsual)) ? 1 : 0;
                    }
                    if (in_array(1, $tsuac)) {
                      $sfplugins['touchscreen']['mode'] = 'always_active';
                      if ($behaviour == 2) {
                        $sfsettings['clone_parent'] = 1;
                      }
                    }
                  break;
                }
              }
            break;
          }
        break;
      }
    }

    $smallscreen = $this->configuration['small'];
    if ($smallscreen) {
      $build['#attached']['library'][] = 'superfish/superfish_smallscreen';
      switch ($smallscreen) {
        case 1 :
          $sfplugins['smallscreen']['mode'] = 'always_active';
        break;
        case 2 :
          $sfplugins['smallscreen']['mode'] = 'window_width';
          $ssbp = $this->configuration['smallbp'];
          $sfplugins['smallscreen']['breakpoint'] = ($ssbp != 768) ? (int)$ssbp : '';
        break;
        case 3 :
          // Which method to use for UA detection.
          $ssuam = $this->configuration['smalluam'];
          $ssua = $this->configuration['smallua'];
          switch ($ssuam) {
            // Client-side.
            case 0 :
              switch ($ssua) {
                case 0 :
                  $sfplugins['smallscreen']['mode'] = 'useragent_predefined';
                break;
                case 1 :
                  $sfplugins['smallscreen']['mode'] = 'useragent_custom';
                  $ssual = drupal_strtolower($this->configuration['smallual']);
                  if (strpos($ssual, '*')) {
                    $ssual = str_replace('*', '|', $ssual);
                  }
                  $sfplugins['smallscreen']['useragent'] = $ssual;
                break;
              }

            break;
            // Server-side.
            case 1 :
              if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $hua = drupal_strtolower($_SERVER['HTTP_USER_AGENT']);
                switch ($ssua) {
                  // Use the pre-defined list of mobile UA strings.
                  case 0 :
                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $hua)) {
                      $sfplugins['smallscreen']['mode'] = 'always_active';
                    }
                  break;
                  // Use the custom list of UA strings.
                  case 1 :
                    $ssual = drupal_strtolower($this->configuration['smallual']);
                    $ssuac = array();
                    if (strpos($ssual, '*')) {
                      $ssual = explode('*', $ssual);
                      foreach ($ssual as $ua) {
                        $ssuac[] = (strpos($hua, $ua)) ? 1 : 0;
                      }
                    }
                    else {
                      $ssuac[] = (strpos($hua, $ssual)) ? 1 : 0;
                    }
                    if (in_array(1, $ssuac)) {
                      $sfplugins['smallscreen']['mode'] = 'always_active';
                    }
                  break;
                }
              }
            break;
          }
        break;
      }
      $type = $this->configuration['smallact'];
      switch ($type) {
        case 0:
          $asa = $this->configuration['smallasa'];
          $cmc = $this->configuration['smallcmc'];
          $chc = $this->configuration['smallchc'];
          $ecm = $this->configuration['smallecm'];
          $ech = $this->configuration['smallech'];
          $icm = $this->configuration['smallicm'];
          $ich = $this->configuration['smallich'];

          $sfplugins['smallscreen']['type'] = 'select';
          $sfplugins['smallscreen']['addSelected'] = ($asa == 1) ? TRUE : '';
          $sfplugins['smallscreen']['menuClasses'] = ($cmc == 1) ? TRUE : '';
          $sfplugins['smallscreen']['hyperlinkClasses'] = ($chc == 1) ? TRUE : '';
          $sfplugins['smallscreen']['excludeClass_menu'] = ($cmc == 1 && !empty($ecm)) ? $ecm : '';
          $sfplugins['smallscreen']['excludeClass_hyperlink'] = ($chc == 1 && !empty($ech)) ? $ech : '';
          $sfplugins['smallscreen']['includeClass_menu'] = (!empty($icm)) ? $icm : '';
          $sfplugins['smallscreen']['includeClass_hyperlink'] = (!empty($ich)) ? $ich : '';
        break;
        case 1:
          $ab = $this->configuration['smallabt'];
          $sfplugins['smallscreen']['accordionButton'] = ($ab != 1) ? $ab : '';
          $sfplugins['smallscreen']['expandText'] = ($this->t('Expand') != 'Expand') ? $this->t('Expand') : '';
          $sfplugins['smallscreen']['collapseText'] = ($this->t('Collapse') != 'Collapse') ? $this->t('Collapse') : '';
        break;
      }
    }

    if ($this->configuration['supposition']) {
      $sfplugins['supposition'] = TRUE;
      $build['#attached']['library'][] = 'superfish/superfish_supposition';
    }

    if ($this->configuration['supersubs']) {
      $build['#attached']['library'][] = 'superfish/superfish_supersubs';
      $minwidth = $this->configuration['minwidth'];
      $maxwidth = $this->configuration['maxwidth'];
      $sfplugins['supersubs']['minWidth'] = ($minwidth != 12) ? $minwidth : '';
      $sfplugins['supersubs']['maxWidth'] = ($maxwidth != 27) ? $maxwidth : '';
      if (empty($sfplugins['supersubs']['minWidth']) && empty($sfplugins['supersubs']['maxWidth'])) {
        $sfplugins['supersubs'] = TRUE;
      }
    }

    // Attaching the requires JavaScript and CSS files.
    $build['#attached']['library'][] = 'superfish/superfish';
    if ($sfsettings['style'] != 'none') {
      $build['#attached']['library'][] = 'superfish/superfish_style_'. $sfsettings['style'];
    }

    // Title for the small-screen menu.
    if ($smallscreen) {
      $title = '';
      switch ($type) {
        case 0 :
          $title = $this->configuration['smallset'];
        break;
        case 1 :
          $title = $this->configuration['smallamt'];
        break;
      }
      $sfplugins['smallscreen']['title'] = $title ? $title : $this->label();
    }
    $sfplugins = superfish_array_remove_empty($sfplugins);

    // Menu block ID.
    $menu_name = $this->getDerivativeId();

    // Menu tree.
    $level = $this->configuration['level'];
    // Menu display depth.
    $depth = $sfsettings['depth'];

    // By not setting the any expanded parents we don't limit the loading of the subtrees.
    // Calling MenuLinkTreeInterface::getCurrentRouteMenuTreeParameters we would be
    // doing so. We don't actually need the parents expanded as we do different rendering.
    $parameters = (new MenuTreeParameters())
      ->setMinDepth($level)
      ->setMaxDepth($depth ? min($level + ($depth - 1), $this->menuTree->maxDepth()) : NULL)
      ->setActiveTrail($this->menuActiveTrail->getActiveTrailIds($menu_name))
      ->onlyEnabledLinks();

    $tree = $this->menuTree->load($menu_name, $parameters);
    $manipulators = array(
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort']
    );
    $tree = $this->menuTree->transform($tree, $manipulators);

    // Unique HTML ID.
    $html_id = Html::getUniqueId('superfish-' . $menu_name);

    // Preparing the Drupal behavior.
    $build['#attached']['drupalSettings']['superfish'][$html_id]['id'] = $html_id;
    $build['#attached']['drupalSettings']['superfish'][$html_id]['sf'] = isset($sfoptions) ? $sfoptions : array();
    if (!empty($sfplugins)) {
      $build['#attached']['drupalSettings']['superfish'][$html_id]['plugins'] = $sfplugins;
    }

    // Calling the theme.
    $build['content'] = array(
      '#theme'  => 'superfish',
      '#menu_name' => $menu_name,
      '#html_id' => $html_id,
      '#tree' => $tree,
      '#settings' => $sfsettings
    );
    // Build the original menu tree to calculate cache tags and contexts.
    $treeBuild = $this->menuTree->build($tree);
    $build['#cache'] = $treeBuild['#cache'];

    return $build;
  }

  /**
   * Overrides \Drupal\block\BlockBase::defaultConfiguration().
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'level' => 1,
      'depth' => 0,
      'menu_type' => 'horizontal',
      'style' => 'none',
      'arrow' => 1,
      'shadow' => 1,
      'speed' => 'fast',
      'delay' => 800,
      'slide' => 'vertical',
      'supposition' => 1,
      'hoverintent' => 1,
      'touch' => 0,
      'touchbh' => 2,
      'touchbp' => 768,
      'touchua' => 0,
      'touchual' => '',
      'touchuam' => 0,
      'small' => 2,
      'smallbp' => 768,
      'smallua' => 0,
      'smallual' => '',
      'smalluam' => 0,
      'smallact' => 1,
      'smallset' => '',
      'smallasa' => 0,
      'smallcmc' => 0,
      'smallecm' => '',
      'smallchc' => 0,
      'smallech' => '',
      'smallicm' => '',
      'smallich' => '',
      'smallamt' => '',
      'smallabt' => 1,
      'supersubs' => 1,
      'minwidth' => 12,
      'maxwidth' => 27,
      'multicolumn' => 0,
      'multicolumn_depth' => 1,
      'multicolumn_levels' => 0,
      'pathlevels' => 1,
      'expanded' => 0,
      'clone_parent' => 0,
      'hide_linkdescription' => 0,
      'add_linkdescription' => 0,
      'link_depth_class' => 1,
      'custom_list_class' => '',
      'custom_item_class' => '',
      'custom_link_class' => ''
    ];
  }

}