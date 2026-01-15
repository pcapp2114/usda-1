# Views Field Select Filter

## Introduction
The **Views Field Select Filter** module provides a Views filter that gathers
all field values and presents them in a dropdown. This is particularly useful
when you want to create a filter on a single field in a View, but the field
contains simple text or integer values. The module adds a filter for every text
or integer field on your nodes, and these filters are postfixed with 
`(selector)` in the field list.

This module was created as a "scratch your own itch" project to address a
common need for more flexible filtering in Views.

---

## Installation
1. Install the module using [Composer](https://getcomposer.org/):
   ```bash
   composer require drupal/views_field_select_filter
