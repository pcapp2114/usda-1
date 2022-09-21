INTRODUCTION
-----------

Layout Builder Kit is a set of pre-made components you can use in your Layout
Builder pages. They are ready for Drupal 9.

Layout Builder Kit can now render media objects.


DESCRIPTION
-----------

Layout Builder Kit includes basic components that you can use within your Drupal
site. The components can be used in Block Layout or Layout Builder.

This module contains 7 components:
- Book Navigation
- Icon Text
- Image
- Render
- Rich Text
- Tab
- Video


REQUIREMENTS
------------

This module requires the following modules:

    * hook_event_dispatcher:core_event_dispatcher
    * drupal:layout_builder


DOCUMENTATION
-------------

Find the documentation at:
https://performantlabs.com/layout-builder-kit/layout-builder-kit


INSTALLATION
------------

 * Install as you would normally install a contributed Drupal module. Visit
   https://www.drupal.org/node/1897420 for further information.
 * Installing the module without Composer is not recommended and is unsupported.


CONFIGURATION
-------------

Layout Builder is not turned on by default. To turn it on for the Basic Page
content type:

    1. Go to Structure > Content Types > Basic Page > Manage Display.
    2. Click "Use Layout Builder:"
    3. Click Save. The page will redraw and the Manage Layout button will
       appear.
    4. Click "Manage layout."
    5. You are now editing the layout of all Basic Pages. Click Add Block to add
       Layout Builder Components. To make layouts unique to each page, go back
       to Step 3 and click "Allow each content item to have its layout
       customized."
