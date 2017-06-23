Superfish module

About
-----
This module allows for integration of jQuery Superfish plug-in with Drupal CMS.


Requirement
-----------
- Superfish library.
  Link: https://github.com/mehrpadin/Superfish-for-Drupal/


Installation instructions (composer)
------------------------------------
0. You can skip this step if you use the popular
   https://github.com/drupal-composer/drupal-project.
   Otherwise check that your composer.json file has directives to install
   libraries in the libraries directory like this:

    "require": {
      "composer/installers": "^1.0",
    },
    "extra": {
        "installer-paths": {
            "web/libraries/{$name}": ["type:drupal-library"]
        }
    }

    This assumes that your drupal root resides in the "web" direectory.
    If it is in another directory (e.g. "docroot"), change accordingly.

1. Require the module

    $ composer require drupal/superfish

   Composer will then put the module *and* its library into the right places.

2. Go to "Administer" -> "Modules" and enable the module.

3. Go to "Administer" -> "Structure" -> "Block layout" -> click a "Place block"
   button to add a Superfish block to a region.


Installation instructions (manual)
----------------------------------
1. Download and extract the Superfish library into the libraries directory
   (usually "DRUPAL_ROOT/libraries").
   Link: https://github.com/mehrpadin/Superfish-for-Drupal/zipball/2.x

2. Download and extract the Superfish module into the modules directory
   (usually "DRUPAL_ROOT/modules").
   Link: http://drupal.org/project/superfish

3. Go to "Administer" -> "Modules" and enable the module.

4. Go to "Administer" -> "Structure" -> "Block layout" -> click a "Place block"
   button to add a Superfish block to a region.


How to style
------------

Here are some tips and tricks:

A) Utilise the "Default" style as reference.

B) Always use a DOM inspector utility (such as Firebug).

C) Set the "Menu delay" option in the block configuration to a very high number
   such as 99999999 while creating your own CSS. This will give you enough time
   to work with sub-menus.

C) If your theme supports the Superfish module, set the "Style" option in the
   block configuration to "None".


Support requests
----------------
You can request support here: http://drupal.org/project/issues/superfish
