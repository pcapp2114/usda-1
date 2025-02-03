Changelog
=========

All notable changes to the Condition Path module.

## 3.0.1 (2024-10-21):

* Issue #3481762: Remove typing of constants as this is not PHP 8.1 compatible.

## 3.0.0 (2024-08-27):

* Add Drupal 11 support
* Drop Drupal 9 support
* Drop PHP ^7.1 support
* Improve condition summary text to take in account negation
* Improve PHP code with PHP ^8.1 syntax

## 2.0.4 (2024-01-30):

* Allow paths to start with an exclamation mark followed by wildcard asterisk.

## 2.0.3 (2024-01-29):

* Allow paths to start with a wildcard asterisk.

## 2.0.2 (2023-11-14):

* Issue #3401635: Fix "leading slash is required".

## 2.0.1 (2023-11-08:

* Change the plugin title in the block visibility section to be in line with 
  Drupal core: "Pages (Include and exclude)". Group it with core's "Pages".
* Add a clarifying placeholder text to the Pages textarea.
* Issue #3399820: Fix mb_strtolower() passing null is deprecated.

## 2.0.0 (2022-08-25):

* Add Drupal 10 support.
* Remove Drupal 8 support (use version 1.x.x for Drupal 8).
* Add PHP 8.0 and higher support (next to PHP 7.1 and higher).
* Add native negate option to condition form. This was previously hidden.

## 1.0.0 (2021-06-20):

First stable release with security advisory coverage.
See https://www.drupal.org/security-advisory-policy.
Added more examples to the README.
No code changes.

## 1.0.0-beta2 (2021-06-07):

Improvements:
* Move the pages-to-array conversion to separate method.
* Remove obsolete repositories from composer.json.
* Changed summary to avoid it to become too long.
* Hide the default negation option by setting access to false instead of
  unsetting it.

## 1.0.0-beta1 (2021-06-05):

First beta release.
