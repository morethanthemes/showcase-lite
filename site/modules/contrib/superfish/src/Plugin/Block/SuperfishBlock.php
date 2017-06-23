<?php

namespace Drupal\superfish\Plugin\Block;

use Drupal\system\Plugin\Block\SystemMenuBlock;
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
    $form['sf'] = [
      '#type' => 'details',
      '#title' => $this->t('Block settings'),
      '#open' => TRUE,
    ];
    $description = sprintf('<em>(%s: %s)</em>',
      $this->t('Default'),
      $this->t('Horizontal (single row)')
    );
    $form['sf']['superfish_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Menu type'),
      '#description' => $description,
      '#default_value' => $this->configuration['menu_type'],
      '#options' => [
        'horizontal' => $this->t('Horizontal (single row)'),
        'navbar' => $this->t('Horizontal (double row)'),
        'vertical' => $this->t('Vertical (stack)'),
      ],
    ];
    $description = sprintf('<em>(%s: %s)</em>',
      $this->t('Default'),
      $this->t('None')
    );
    $form['sf']['superfish_style'] = [
      '#type' => 'select',
      '#title' => $this->t('Style'),
      '#description' => $description,
      '#default_value' => $this->configuration['style'],
      '#options' => [
        'none' => $this->t('None'),
        'default' => $this->t('Default'),
        'black' => $this->t('Black'),
        'blue' => $this->t('Blue'),
        'coffee' => $this->t('Coffee'),
        'white' => $this->t('White'),
      ],
    ];
    $form['sf']['superfish_arrow'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add arrows to parent menus'),
      '#default_value' => $this->configuration['arrow'],
    ];
    $form['sf']['superfish_shadow'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Drop shadows'),
      '#default_value' => $this->configuration['shadow'],
    ];
    if (count(superfish_effects()) == 4) {
      $easing_instructions = $this->t('jQuery Easing plugin is not installed.');
    }
    else {
      $easing_instructions = $this->t("The plugin provides a handful number of animation effects, they can be used by uploading the 'jquery.easing.js' file to the libraries directory within the 'easing' directory (for example: libraries/easing/jquery.easing.js). Refresh this page after the plugin is uploaded, this will make more effects available in the above list.");
    }
    $description = sprintf('<em>(%s: %s)</em><br>%s<br>',
      $this->t('Default'),
      $this->t('Vertical'),
      $easing_instructions
    );
    $form['sf']['superfish_slide'] = [
      '#type' => 'select',
      '#title' => $this->t('Slide-in effect'),
      '#description' => $description,
      '#default_value' => $this->configuration['slide'],
      '#options' => superfish_effects(),
    ];
    $form['sf-plugins'] = [
      '#type' => 'details',
      '#title' => $this->t('Superfish plugins'),
      '#open' => TRUE,
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Relocates sub-menus when they would otherwise appear outside the browser window area.'),
      $this->t('Default'),
      $this->t('enabled')
    );
    $form['sf-plugins']['superfish_supposition'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('jQuery Supposition'),
      '#description' => $description,
      '#default_value' => $this->configuration['supposition'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t("Prevents accidental firing of animations by waiting until the user's mouse slows down enough, hence determinig user's <em>intent</em>."),
      $this->t('Default'),
      $this->t('enabled')
    );
    $form['sf-plugins']['superfish_hoverintent'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('jQuery hoverIntent'),
      '#description' => $description,
      '#default_value' => $this->configuration['hoverintent'],
    ];
    $description = sprintf('%s <em>(%s)</em>',
      $this->t('<strong>sf-Touchscreen</strong> provides touchscreen compatibility.'),
      $this->t('The first click on a parent hyperlink shows its children and the second click opens the hyperlink.')
    );
    $form['sf-plugins']['sf-touchscreen'] = [
      '#type' => 'details',
      '#title' => $this->t('sf-Touchscreen'),
      '#description' => $description,
      '#open' => FALSE,
    ];
    $default = sprintf('%s <em>(%s)</em>',
      $this->t('Disable'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-touchscreen']['superfish_touch'] = [
      '#type' => 'radios',
      '#default_value' => $this->configuration['touch'],
      '#options' => [
        0 => $default,
        1 => $this->t('Enable jQuery sf-Touchscreen plugin for this menu.'),
        2 => $this->t("Enable jQuery sf-Touchscreen plugin for this menu depending on the user's Web browser <strong>window width</strong>."),
        3 => $this->t("Enable jQuery sf-Touchscreen plugin for this menu depending on the user's Web browser <strong>user agent</strong>."),
      ],
    ];
    $default = sprintf('%s <em>(%s)</em>',
      $this->t('Hiding the sub-menu on the second tap, adding cloned parent links to the top of sub-menus as well.'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-touchscreen']['superfish_touchbh'] = [
      '#type' => 'radios',
      '#title' => 'Select a behaviour',
      '#description' => $this->t('Using this plugin, the first click or tap will expand the sub-menu, here you can choose what a second click or tap should do.'),
      '#default_value' => $this->configuration['touchbh'],
      '#options' => [
        0 => $this->t('Opening the parent menu item link on the second tap.'),
        1 => $this->t('Hiding the sub-menu on the second tap.'),
        2 => $default,
      ],
    ];
    $description = sprintf('%s<br><br>%s<br><code>&lt;meta name="viewport" content="width=device-width, initial-scale=1.0" /&gt;</code>',
      $this->t("sf-Touchscreen will be enabled only if the width of user's Web browser window is smaller than the below value."),
      $this->t('Please note that in most cases such a meta tag is necessary for this feature to work properly:')
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-windowwidth'] = [
      '#type' => 'details',
      '#title' => $this->t('Window width settings'),
      '#description' => $description,
      '#open' => TRUE,
    ];
    $description = sprintf('%s <em>(%s: 768)</em>',
      $this->t('Also known as "Breakpoint".'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-windowwidth']['superfish_touchbp'] = [
      '#type' => 'number',
      '#description' => $description,
      '#default_value' => $this->configuration['touchbp'],
      '#field_suffix' => $this->t('pixels'),
      '#size' => 10,
    ];
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent'] = [
      '#type' => 'details',
      '#title' => $this->t('User agent settings'),
      '#open' => TRUE,
    ];
    $default = sprintf('%s <em>(%s) (%s)</em>',
      $this->t('Use the pre-defined list of the <strong>user agents</strong>.'),
      $this->t('Default'),
      $this->t('Recommended')
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchua'] = [
      '#type' => 'radios',
      '#default_value' => $this->configuration['touchua'],
      '#options' => [
        0 => $default,
        1 => $this->t('Use the custom list of the <strong>user agents</strong>.'),
      ],
    ];
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = sprintf('<br><strong>%s</strong> %s',
        $this->t('UA string of the current Web browser:'),
        $_SERVER['HTTP_USER_AGENT']
      );
    }
    else {
      $user_agent = '';
    }
    $description = sprintf('%s <em>(%s: %s)</em><br>%s:<ul>
    <li>iPhone*Android*iPad <em><sup>(%s)</sup></em></li>
    <li>Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2
    (KHTML, like Gecko) Version/1.0 Safari/532.2 Pre/1.0 * Mozilla/5.0
    (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10
    (KHTML, like Gecko) Mobile/7B405</li>
    </ul>%s',
      $this->t('Could be partial or complete. (Asterisk separated)'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Examples'),
      $this->t('Recommended'),
      $user_agent
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchual'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom list of the user agents'),
      '#description' => $description,
      '#default_value' => $this->configuration['touchual'],
      '#size' => 100,
      '#maxlength' => 2000,
    ];
    $description = sprintf('<em>(%s: %s)</em>',
      $this->t('Default'),
      $this->t('Client-side (JavaScript)')
    );
    $form['sf-plugins']['sf-touchscreen']['sf-touchscreen-useragent']['superfish_touchuam'] = [
      '#type' => 'select',
      '#title' => $this->t('<strong>User agent</strong> detection method'),
      '#description' => $description,
      '#default_value' => $this->configuration['touchuam'],
      '#options' => [
        0 => $this->t('Client-side (JavaScript)'),
        1 => $this->t('Server-side (PHP)'),
      ],
    ];
    $form['sf-plugins']['sf-smallscreen'] = [
      '#type' => 'details',
      '#title' => $this->t('sf-Smallscreen'),
      '#description' => $this->t('<strong>sf-Smallscreen</strong> provides small-screen compatibility for your menus.'),
      '#open' => FALSE,
    ];
    $default = sprintf('%s <em>(%s)</em>',
      $this->t("Enable jQuery sf-Smallscreen plugin for this menu depending on the user's Web browser <strong>window width</strong>."),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-smallscreen']['superfish_small'] = [
      '#type' => 'radios',
      '#default_value' => $this->configuration['small'],
      '#options' => [
        0 => sprintf('%s.', $this->t('Disable')),
        1 => $this->t('Enable jQuery sf-Smallscreen plugin for this menu.'),
        2 => $default,
        3 => $this->t("Enable jQuery sf-Smallscreen plugin for this menu depending on the user's Web browser <strong>user agent</strong>."),
      ],
    ];
    $description = sprintf('%s<br><br>%s<br><code>&lt;meta name="viewport" content="width=device-width, initial-scale=1.0" /&gt;</code>',
      $this->t("sf-Smallscreen will be enabled only if the width of user's Web browser window is smaller than the below value."),
      $this->t('Please note that in most cases such a meta tag is necessary for this feature to work properly:')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-windowwidth'] = [
      '#type' => 'details',
      '#title' => $this->t('Window width settings'),
      '#description' => $description,
      '#open' => TRUE,
    ];
    $description = sprintf('%s <em>(%s: 768)</em>',
      $this->t('Also known as "Breakpoint".'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-windowwidth']['superfish_smallbp'] = [
      '#type' => 'number',
      '#description' => $description,
      '#default_value' => $this->configuration['smallbp'],
      '#field_suffix' => $this->t('pixels'),
      '#size' => 10,
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent'] = [
      '#type' => 'details',
      '#title' => $this->t('User agent settings'),
      '#open' => TRUE,
    ];
    $default = sprintf('%s <em>(%s) (%s)</em>',
      $this->t('Use the pre-defined list of the <strong>user agents</strong>.'),
      $this->t('Default'),
      $this->t('Recommended')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smallua'] = [
      '#type' => 'radios',
      '#default_value' => $this->configuration['smallua'],
      '#options' => [
        0 => $default,
        1 => $this->t('Use the custom list of the <strong>user agents</strong>.'),
      ],
    ];
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $user_agent = sprintf('<br><strong>%s</strong> %s',
        $this->t('UA string of the current Web browser:'),
        $_SERVER['HTTP_USER_AGENT']
      );
    }
    else {
      $user_agent = '';
    }
    $description = sprintf('%s <em>(%s: %s)</em><br>%s:<ul>
    <li>iPhone*Android*iPad <em><sup>(%s)</sup></em></li>
    <li>Mozilla/5.0 (webOS/1.4.0; U; en-US) AppleWebKit/532.2
    (KHTML, like Gecko) Version/1.0 Safari/532.2 Pre/1.0 * Mozilla/5.0
    (iPad; U; CPU OS 3_2_1 like Mac OS X; en-us) AppleWebKit/531.21.10
    (KHTML, like Gecko) Mobile/7B405</li>
    </ul>%s',
      $this->t('Could be partial or complete. (Asterisk separated)'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Examples'),
      $this->t('Recommended'),
      $user_agent
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smallual'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom list of the user agents'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallual'],
      '#size' => 100,
      '#maxlength' => 2000,
    ];
    $description = sprintf('<em>(%s: %s)</em>',
      $this->t('Default'),
      $this->t('Client-side (JavaScript)')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-useragent']['superfish_smalluam'] = [
      '#type' => 'select',
      '#title' => $this->t('<strong>User agent</strong> detection method'),
      '#description' => $description,
      '#default_value' => $this->configuration['smalluam'],
      '#options' => [
        0 => $this->t('Client-side (JavaScript)'),
        1 => $this->t('Server-side (PHP)'),
      ],
    ];
    $default = sprintf('%s <em>(%s)</em>',
      $this->t('Convert the menu to an accordion menu.'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-smallscreen']['superfish_smallact'] = [
      '#type' => 'radios',
      '#title' => $this->t('Select a type'),
      '#default_value' => $this->configuration['smallact'],
      '#options' => [
        1 => $default,
        0 => $this->t('Convert the menu to a &lt;select&gt; element.'),
      ],
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select'] = [
      '#type' => 'details',
      '#title' => $this->t('&lt;select&gt; settings'),
      '#open' => FALSE,
    ];
    $description = sprintf('%s <em>(%s: %s)</em><br>%s: <em> - %s - </em>',
      $this->t('By default the first item in the &lt;select&gt; element will be the name of the parent menu or the title of this block, you can change this by setting a custom title.'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Example'),
      $this->t('Main Menu')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['superfish_smallset'] = [
      '#type' => 'textfield',
      '#title' => $this->t('&lt;select&gt; title'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallset'],
      '#size' => 50,
      '#maxlength' => 500,
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['superfish_smallasa'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Add <em>selected</em> attribute to the &lt;option&gt; element with the class <strong>active</strong> .'),
      '#description' => $this->t('Makes pre-selected the item linked to the active page when the page loads.'),
      '#default_value' => $this->configuration['smallasa'],
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more'] = [
      '#type' => 'details',
      '#title' => $this->t('More'),
      '#open' => FALSE,
    ];
    $title = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Copy the main &lt;ul&gt; classes to the &lt;select&gt;.'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallcmc'] = [
      '#type' => 'checkbox',
      '#title' => $title,
      '#default_value' => $this->configuration['smallcmc'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Comma separated'),
      $this->t('Default'),
      $this->t('empty')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallecm'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Exclude these classes from the &lt;select&gt; element'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallecm'],
      '#size' => 100,
      '#maxlength' => 1000,
      '#states' => [
        'enabled' => [
          ':input[name="superfish_smallcmc"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    $title = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Copy the hyperlink classes to the &lt;option&gt; elements of the &lt;select&gt;.'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallchc'] = [
      '#type' => 'checkbox',
      '#title' => $title,
      '#default_value' => $this->configuration['smallchc'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Comma separated'),
      $this->t('Default'),
      $this->t('empty')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallech'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Exclude these classes from the &lt;option&gt; elements of the &lt;select&gt;'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallech'],
      '#size' => 100,
      '#maxlength' => 1000,
      '#states' => [
        'enabled' => [
          ':input[name="superfish_smallchc"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallicm'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Include these classes in the &lt;select&gt; element'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallicm'],
      '#size' => 100,
      '#maxlength' => 1000,
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-select']['sf-smallscreen-select-more']['superfish_smallich'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Include these classes in the &lt;option&gt; elements of the &lt;select&gt;'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallich'],
      '#size' => 100,
      '#maxlength' => 1000,
    ];
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion'] = [
      '#type' => 'details',
      '#title' => $this->t('Accordion settings'),
      '#open' => FALSE,
    ];
    $description = sprintf('%s <em>(%s: %s)</em><br>%s: <em>%s</em>.',
      $this->t('By default the caption of the accordion toggle switch will be the name of the parent menu or the title of this block, you can change this by setting a custom title.'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Example'),
      $this->t('Menu')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion']['superfish_smallamt'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Accordion menu title'),
      '#description' => $description,
      '#default_value' => $this->configuration['smallamt'],
      '#size' => 50,
      '#maxlength' => 500,
    ];
    $default = sprintf('%s <em>(%s)</em>',
      $this->t('Use parent menu items as buttons, add cloned parent links to sub-menus as well.'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-smallscreen']['sf-smallscreen-accordion']['superfish_smallabt'] = [
      '#type' => 'radios',
      '#title' => $this->t('Accordion button type'),
      '#default_value' => $this->configuration['smallabt'],
      '#options' => [
        0 => $this->t('Use parent menu items as buttons.'),
        1 => $default,
        2 => $this->t('Create new links next to parent menu item links and use them as buttons.'),
      ],
    ];
    $form['sf-plugins']['sf-supersubs'] = [
      '#type' => 'details',
      '#title' => $this->t('Supersubs'),
      '#description' => $this->t('<strong>Supersubs</strong> makes it possible to define custom widths for your menus.'),
      '#open' => FALSE,
    ];
    $form['sf-plugins']['sf-supersubs']['superfish_supersubs'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Supersubs for this menu.'),
      '#default_value' => $this->configuration['supersubs'],
    ];
    $description = sprintf('%s <em>(%s: 12)</em>',
      $this->t('Minimum width for sub-menus, in <strong>em</strong> units.'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-supersubs']['superfish_minwidth'] = [
      '#type' => 'number',
      '#title' => $this->t('Minimum width'),
      '#description' => $description,
      '#default_value' => $this->configuration['minwidth'],
      '#size' => 10,
    ];
    $description = sprintf('%s <em>(%s: 27)</em>',
      $this->t('Maximum width for sub-menus, in <strong>em</strong> units.'),
      $this->t('Default')
    );
    $form['sf-plugins']['sf-supersubs']['superfish_maxwidth'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum width'),
      '#description' => $description,
      '#default_value' => $this->configuration['maxwidth'],
      '#size' => 10,
    ];
    $form['sf-multicolumn'] = [
      '#type' => 'details',
      '#description' => $this->t('Please refer to the Superfish module documentation for how you should setup multi-column sub-menus.'),
      '#title' => $this->t('Multi-column sub-menus'),
      '#open' => FALSE,
    ];
    $form['sf-multicolumn']['superfish_multicolumn'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable multi-column sub-menus.'),
      '#default_value' => $this->configuration['multicolumn'],
    ];
    $description = sprintf('%s <em>(%s: 1)</em>',
      $this->t('The depth of the first instance of multi-column sub-menus.'),
      $this->t('Default')
    );
    $form['sf-multicolumn']['superfish_multicolumn_depth'] = [
      '#type' => 'select',
      '#title' => $this->t('Start from depth'),
      '#description' => $description,
      '#default_value' => $this->configuration['multicolumn_depth'],
      '#options' => array_combine(range(1, 10), range(1, 10)),
    ];
    $description = sprintf('%s <em>(%s: 1)</em>',
      $this->t('The amount of sub-menu levels that will be included in the multi-column sub-menu.'),
      $this->t('Default')
    );
    $form['sf-multicolumn']['superfish_multicolumn_levels'] = [
      '#type' => 'select',
      '#title' => $this->t('Levels'),
      '#description' => $description,
      '#default_value' => $this->configuration['multicolumn_levels'],
      '#options' => array_combine(range(1, 10), range(1, 10)),
    ];
    $form['sf-advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    ];
    $form['sf-advanced']['sf-settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Superfish'),
      '#open' => FALSE,
    ];
    $description = sprintf('%s <em>(%s: fast)</em>',
      $this->t('The speed of the animation either in <strong>milliseconds</strong> or pre-defined values (<strong>slow, normal, fast</strong>).'),
      $this->t('Default')
    );
    $form['sf-advanced']['sf-settings']['superfish_speed'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Animation speed'),
      '#description' => $description,
      '#default_value' => $this->configuration['speed'],
      '#size' => 15,
    ];
    $description = sprintf('%s <em>(%s: 800)</em>',
      $this->t('The delay in <strong>milliseconds</strong> that the mouse can remain outside a sub-menu without it closing.'),
      $this->t('Default')
    );
    $form['sf-advanced']['sf-settings']['superfish_delay'] = [
      '#type' => 'number',
      '#title' => $this->t('Mouse delay'),
      '#description' => $description,
      '#default_value' => $this->configuration['delay'],
      '#size' => 15,
    ];
    $description = sprintf('%s <em>(%s: 1)</em><br>%s',
      $this->t('The amount of sub-menu levels that remain open or are restored using the ".active-trail" class.'),
      $this->t('Default'),
      $this->t('Change this setting <strong>only and only</strong> if you are <strong>totally sure</strong> of what you are doing.')
    );
    $form['sf-advanced']['sf-settings']['superfish_pathlevels'] = [
      '#type' => 'select',
      '#title' => $this->t('Path levels'),
      '#description' => $description,
      '#default_value' => $this->configuration['pathlevels'],
      '#options' => array_combine(range(0, 10), range(0, 10)),
    ];
    $form['sf-advanced']['sf-hyperlinks'] = [
      '#type' => 'details',
      '#title' => $this->t('Hyperlinks'),
      '#open' => TRUE,
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('By enabling this option, only parent menu items with <em>Expanded</em> option enabled will have their submenus appear.'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_expanded'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Take "Expanded" option into effect.'),
      '#description' => $description,
      '#default_value' => $this->configuration['expanded'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Add cloned parent links to the top of sub-menus.'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_clone_parent'] = [
      '#type' => 'checkbox',
      '#title' => $description,
      '#default_value' => $this->configuration['clone_parent'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Disable hyperlink descriptions ("title" attribute)'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_hide_linkdescription'] = [
      '#type' => 'checkbox',
      '#title' => $description,
      '#default_value' => $this->configuration['hide_linkdescription'],
    ];
    $description = sprintf('%s <em>(%s: %s)</em>',
      $this->t('Insert hyperlink descriptions ("title" attribute) into hyperlink texts.'),
      $this->t('Default'),
      $this->t('disabled')
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_add_linkdescription'] = [
      '#type' => 'checkbox',
      '#title' => $description,
      '#default_value' => $this->configuration['add_linkdescription'],
    ];
    $title = sprintf('%s <em>(sf-depth-1, sf-depth-2, sf-depth-3, ...)</em> <em>(%s: %s)</em>',
      $this->t('Add item depth classes to menu items and their hyperlinks.'),
      $this->t('Default'),
      $this->t('enabled')
    );
    $form['sf-advanced']['sf-hyperlinks']['superfish_itemdepth'] = [
      '#type' => 'checkbox',
      '#title' => $title,
      '#default_value' => $this->configuration['link_depth_class'],
    ];
    $form['sf-advanced']['sf-custom-classes'] = [
      '#type' => 'details',
      '#title' => $this->t('Custom classes'),
      '#open' => TRUE,
    ];
    $description = sprintf('%s <em>(%s: %s)</em><br>%s: top-menu category-science',
      $this->t('(Space separated, without dots)'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Example')
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_ulclass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('For the main UL'),
      '#description' => $description,
      '#default_value' => $this->configuration['custom_list_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    ];
    $description = sprintf('%s <em>(%s: %s)</em><br>%s: science-sub',
      $this->t('(Space separated, without dots)'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Example')
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_liclass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('For the list items'),
      '#description' => $description,
      '#default_value' => $this->configuration['custom_item_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    ];
    $description = sprintf('%s <em>(%s: %s)</em><br>%s: science-link',
      $this->t('(Space separated, without dots)'),
      $this->t('Default'),
      $this->t('empty'),
      $this->t('Example')
    );
    $form['sf-advanced']['sf-custom-classes']['superfish_hlclass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('For the hyperlinks'),
      '#description' => $description,
      '#default_value' => $this->configuration['custom_link_class'],
      '#size' => 50,
      '#maxlength' => 1000,
    ];
    return $form;
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockValiate().
   */
  public function blockValidate($form, FormStateInterface $form_state) {
    $touch = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touch',
    ]);
    $touchbp = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-windowwidth',
      'superfish_touchbp',
    ]);
    $touchua = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touchua',
    ]);
    $touchual = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touchual',
    ]);
    $small = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_small',
    ]);
    $smallbp = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-windowwidth',
      'superfish_smallbp',
    ]);
    $smallua = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_smallua',
    ]);
    $smallual = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_smallual',
    ]);
    $minwidth = $form_state->getValue([
      'sf-plugins',
      'sf-supersubs',
      'superfish_minwidth',
    ]);
    $maxwidth = $form_state->getValue([
      'sf-plugins',
      'sf-supersubs',
      'superfish_maxwidth',
    ]);
    $speed = $form_state->getValue([
      'sf-advanced',
      'sf-settings',
      'superfish_speed',
    ]);
    $delay = $form_state->getValue([
      'sf-advanced',
      'sf-settings',
      'superfish_delay',
    ]);

    if (!is_numeric($speed) && !in_array($speed, ['slow', 'normal', 'fast'])) {
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
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   */
  public function blockSubmit($form, FormStateInterface $form_state) {

    $this->configuration['level'] = $form_state->getValue('level');
    $this->configuration['depth'] = $form_state->getValue('depth');
    $this->configuration['menu_type'] = $form_state->getValue([
      'sf',
      'superfish_type',
    ]);
    $this->configuration['style'] = $form_state->getValue([
      'sf',
      'superfish_style',
    ]);
    $this->configuration['arrow'] = $form_state->getValue([
      'sf',
      'superfish_arrow',
    ]);
    $this->configuration['shadow'] = $form_state->getValue([
      'sf',
      'superfish_shadow',
    ]);
    $this->configuration['slide'] = $form_state->getValue([
      'sf',
      'superfish_slide',
    ]);

    $this->configuration['supposition'] = $form_state->getValue([
      'sf-plugins',
      'superfish_supposition',
    ]);
    $this->configuration['hoverintent'] = $form_state->getValue([
      'sf-plugins',
      'superfish_hoverintent',
    ]);

    $this->configuration['touch'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'superfish_touch',
    ]);
    $this->configuration['touchbh'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'superfish_touchbh',
    ]);
    $this->configuration['touchbp'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-windowwidth',
      'superfish_touchbp',
    ]);
    $this->configuration['touchua'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touchua',
    ]);
    $this->configuration['touchual'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touchual',
    ]);
    $this->configuration['touchuam'] = $form_state->getValue([
      'sf-plugins',
      'sf-touchscreen',
      'sf-touchscreen-useragent',
      'superfish_touchuam',
    ]);

    $this->configuration['small'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'superfish_small',
    ]);
    $this->configuration['smallact'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'superfish_smallact',
    ]);
    $this->configuration['smallbp'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-windowwidth',
      'superfish_smallbp',
    ]);
    $this->configuration['smallua'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_smallua',
    ]);
    $this->configuration['smallual'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_smallual',
    ]);
    $this->configuration['smalluam'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-useragent',
      'superfish_smalluam',
    ]);
    $this->configuration['smallset'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'superfish_smallset',
    ]);
    $this->configuration['smallasa'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'superfish_smallasa',
    ]);
    $this->configuration['smallcmc'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallcmc',
    ]);
    $this->configuration['smallecm'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallecm',
    ]);
    $this->configuration['smallchc'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallchc',
    ]);
    $this->configuration['smallech'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallech',
    ]);
    $this->configuration['smallicm'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallicm',
    ]);
    $this->configuration['smallich'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-select',
      'sf-smallscreen-select-more',
      'superfish_smallich',
    ]);
    $this->configuration['smallamt'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-accordion',
      'superfish_smallamt',
    ]);
    $this->configuration['smallabt'] = $form_state->getValue([
      'sf-plugins',
      'sf-smallscreen',
      'sf-smallscreen-accordion',
      'superfish_smallabt',
    ]);

    $this->configuration['supersubs'] = $form_state->getValue([
      'sf-plugins',
      'sf-supersubs',
      'superfish_supersubs',
    ]);
    $this->configuration['minwidth'] = $form_state->getValue([
      'sf-plugins',
      'sf-supersubs',
      'superfish_minwidth',
    ]);
    $this->configuration['maxwidth'] = $form_state->getValue([
      'sf-plugins',
      'sf-supersubs',
      'superfish_maxwidth',
    ]);
    $this->configuration['multicolumn'] = $form_state->getValue([
      'sf-multicolumn',
      'superfish_multicolumn',
    ]);
    $this->configuration['multicolumn_depth'] = $form_state->getValue([
      'sf-multicolumn',
      'superfish_multicolumn_depth',
    ]);
    $this->configuration['multicolumn_levels'] = $form_state->getValue([
      'sf-multicolumn',
      'superfish_multicolumn_levels',
    ]);

    $this->configuration['speed'] = $form_state->getValue([
      'sf-advanced',
      'sf-settings',
      'superfish_speed',
    ]);
    $this->configuration['delay'] = $form_state->getValue([
      'sf-advanced',
      'sf-settings',
      'superfish_delay',
    ]);
    $this->configuration['pathlevels'] = $form_state->getValue([
      'sf-advanced',
      'sf-settings',
      'superfish_pathlevels',
    ]);
    $this->configuration['expanded'] = $form_state->getValue([
      'sf-advanced',
      'sf-hyperlinks',
      'superfish_expanded',
    ]);
    $this->configuration['clone_parent'] = $form_state->getValue([
      'sf-advanced',
      'sf-hyperlinks',
      'superfish_clone_parent',
    ]);
    $this->configuration['hide_linkdescription'] = $form_state->getValue([
      'sf-advanced',
      'sf-hyperlinks',
      'superfish_hide_linkdescription',
    ]);
    $this->configuration['add_linkdescription'] = $form_state->getValue([
      'sf-advanced',
      'sf-hyperlinks',
      'superfish_add_linkdescription',
    ]);
    $this->configuration['link_depth_class'] = $form_state->getValue([
      'sf-advanced',
      'sf-hyperlinks',
      'superfish_itemdepth',
    ]);
    $this->configuration['custom_list_class'] = $form_state->getValue([
      'sf-advanced',
      'sf-custom-classes',
      'superfish_ulclass',
    ]);
    $this->configuration['custom_item_class'] = $form_state->getValue([
      'sf-advanced',
      'sf-custom-classes',
      'superfish_liclass',
    ]);
    $this->configuration['custom_link_class'] = $form_state->getValue([
      'sf-advanced',
      'sf-custom-classes',
      'superfish_hlclass',
    ]);
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {

    $build = [];

    // Block settings which will be passed to the Superfish themes.
    $sfsettings                         = [];
    $sfsettings['level']                = $this->configuration['level'];
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
    $sfoptions = [];
    $sfoptions['pathClass'] = ($sfsettings['menu_type'] == 'navbar') ? 'active-trail' : '';
    $sfoptions['pathLevels'] = ($this->configuration['pathlevels'] != 1) ? $this->configuration['pathlevels'] : '';
    $sfoptions['delay'] = ($this->configuration['delay'] != 800) ? $this->configuration['delay'] : '';
    $sfoptions['animation']['opacity'] = 'show';
    $slide = $this->configuration['slide'];
    if (strpos($slide, '_')) {
      $slide = explode('_', $slide);
      switch ($slide[1]) {
        case 'vertical':
          $sfoptions['animation']['height'] = ['show', $slide[0]];
          break;

        case 'horizontal':
          $sfoptions['animation']['width'] = ['show', $slide[0]];
          break;

        case 'diagonal':
          $sfoptions['animation']['height'] = ['show', $slide[0]];
          $sfoptions['animation']['width'] = ['show', $slide[0]];
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
      if (is_numeric($speed)) {
        $sfoptions['speed'] = (int) $speed;
      }
      elseif (in_array($speed, ['slow', 'normal', 'fast'])) {
        $sfoptions['speed'] = $speed;
      }
    }
    if ($this->configuration['arrow'] == 0) {
      $sfoptions['autoArrows'] = FALSE;
    }
    if ($this->configuration['shadow'] == 0) {
      $sfoptions['dropShadows'] = FALSE;
    }

    if ($this->configuration['hoverintent']) {
      $build['#attached']['library'][] = 'superfish/superfish_hoverintent';
    }
    else {
      $sfoptions['disableHI'] = TRUE;
    }
    $sfoptions = sf_array_filter($sfoptions);

    // Options for Superfish sub-plugins.
    $sfplugins = [];
    $touchscreen = $this->configuration['touch'];
    if ($touchscreen) {
      $build['#attached']['library'][] = 'superfish/superfish_touchscreen';
      $behaviour = $this->configuration['touchbh'];
      $sfplugins['touchscreen']['behaviour'] = ($behaviour != 2) ? $behaviour : '';
      switch ($touchscreen) {
        case 1:
          $sfplugins['touchscreen']['mode'] = 'always_active';
          break;

        case 2:
          $sfplugins['touchscreen']['mode'] = 'window_width';
          $tsbp = $this->configuration['touchbp'];
          $sfplugins['touchscreen']['breakpoint'] = ($tsbp != 768) ? (int) $tsbp : '';
          break;

        case 3:
          // Which method to use for UA detection.
          $tsuam = $this->configuration['touchuam'];
          $tsua = $this->configuration['touchua'];
          switch ($tsuam) {
            // Client-side.
            case 0:
              switch ($tsua) {
                case 0:
                  $sfplugins['touchscreen']['mode'] = 'useragent_predefined';
                  break;

                case 1:
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
            case 1:
              if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $hua = drupal_strtolower($_SERVER['HTTP_USER_AGENT']);
                switch ($tsua) {
                  // Use the pre-defined list of mobile UA strings.
                  case 0:
                    if (preg_match('/(android|bb\d+|meego)|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $hua)) {
                      $sfplugins['touchscreen']['mode'] = 'always_active';
                      if ($behaviour == 2) {
                        $sfsettings['clone_parent'] = 1;
                      }
                    }
                    break;

                  // Use the custom list of UA strings.
                  case 1:
                    $tsual = drupal_strtolower($this->configuration['touchual']);
                    $tsuac = [];
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
        case 1:
          $sfplugins['smallscreen']['mode'] = 'always_active';
          break;

        case 2:
          $sfplugins['smallscreen']['mode'] = 'window_width';
          $ssbp = $this->configuration['smallbp'];
          if ($ssbp != 768) {
            $sfplugins['smallscreen']['breakpoint'] = (int) $ssbp;
          }
          else {
            $sfplugins['smallscreen']['breakpoint'] = '';
          }
          break;

        case 3:
          // Which method to use for UA detection.
          $ssuam = $this->configuration['smalluam'];
          $ssua = $this->configuration['smallua'];
          switch ($ssuam) {
            // Client-side.
            case 0:
              switch ($ssua) {
                case 0:
                  $sfplugins['smallscreen']['mode'] = 'useragent_predefined';
                  break;

                case 1:
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
            case 1:
              if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $hua = drupal_strtolower($_SERVER['HTTP_USER_AGENT']);
                switch ($ssua) {
                  // Use the pre-defined list of mobile UA strings.
                  case 0:
                    if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $hua)) {
                      $sfplugins['smallscreen']['mode'] = 'always_active';
                    }
                    break;

                  // Use the custom list of UA strings.
                  case 1:
                    $ssual = $this->configuration['smallual'];
                    $ssual = drupal_strtolower($ssual);
                    $ssuac = [];
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
          if ($chc == 1) {
            $sfplugins['smallscreen']['hyperlinkClasses'] = TRUE;
          }
          if ($cmc == 1 && !empty($ecm)) {
            $sfplugins['smallscreen']['excludeClass_menu'] = $ecm;
          }
          if ($chc == 1 && !empty($ech)) {
            $sfplugins['smallscreen']['excludeClass_hyperlink'] = $ech;
          }
          if (!empty($icm)) {
            $sfplugins['smallscreen']['includeClass_menu'] = $icm;
          }
          if (!empty($ich)) {
            $sfplugins['smallscreen']['includeClass_hyperlink'] = $ich;
          }
          break;

        case 1:
          $ab = $this->configuration['smallabt'];
          $sfplugins['smallscreen']['accordionButton'] = ($ab != 1) ? $ab : '';
          if ($this->t('Expand') != 'Expand') {
            $sfplugins['smallscreen']['expandText'] = $this->t('Expand');
          }
          if ($this->t('Collapse') != 'Collapse') {
            $sfplugins['smallscreen']['collapseText'] = $this->t('Collapse');
          }
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
      if (empty($sfplugins['supersubs']['minWidth']) &&
          empty($sfplugins['supersubs']['maxWidth'])) {
        $sfplugins['supersubs'] = TRUE;
      }
    }

    // Attaching the requires JavaScript and CSS files.
    $build['#attached']['library'][] = 'superfish/superfish';
    if ($sfsettings['style'] != 'none') {
      $style = 'superfish/superfish_style_' . $sfsettings['style'];
      $build['#attached']['library'][] = $style;
    }

    // Title for the small-screen menu.
    if ($smallscreen) {
      $title = '';
      switch ($type) {
        case 0:
          $title = $this->configuration['smallset'];
          break;

        case 1:
          $title = $this->configuration['smallamt'];
          break;

      }
      $sfplugins['smallscreen']['title'] = $title ? $title : $this->label();
    }
    $sfplugins = sf_array_filter($sfplugins);

    // Menu block ID.
    $menu_name = $this->getDerivativeId();

    // Menu tree.
    $level = $this->configuration['level'];
    // Menu display depth.
    $depth = $sfsettings['depth'];

    /*
     * By not setting the any expanded parents we don't limit the loading of the
     * subtrees.
     * Calling MenuLinkTreeInterface::getCurrentRouteMenuTreeParameters we
     * would be doing so.
     * We don't actually need the parents expanded as we do different rendering.
     */
    if ($depth) {
      $maxdepth = min($level + ($depth - 1), $this->menuTree->maxDepth());
    }
    else {
      $maxdepth = NULL;
    }
    $parameters = (new MenuTreeParameters())
      ->setMinDepth($level)
      ->setMaxDepth($maxdepth)
      ->setActiveTrail($this->menuActiveTrail->getActiveTrailIds($menu_name))
      ->onlyEnabledLinks();

    $tree = $this->menuTree->load($menu_name, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $this->menuTree->transform($tree, $manipulators);

    // Unique HTML ID.
    $id = Html::getUniqueId('superfish-' . $menu_name);

    // Preparing the Drupal behavior.
    $build['#attached']['drupalSettings']['superfish'][$id]['id'] = $id;
    if (isset($sfoptions)) {
      $build['#attached']['drupalSettings']['superfish'][$id]['sf'] = $sfoptions;
    }
    else {
      $build['#attached']['drupalSettings']['superfish'][$id]['sf'] = [];
    }
    if (!empty($sfplugins)) {
      $build['#attached']['drupalSettings']['superfish'][$id]['plugins'] = $sfplugins;
    }

    // Calling the theme.
    $build['content'] = [
      '#theme'  => 'superfish',
      '#menu_name' => $menu_name,
      '#html_id' => $id,
      '#tree' => $tree,
      '#settings' => $sfsettings,
    ];
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
      'custom_link_class' => '',
    ];
  }

}
