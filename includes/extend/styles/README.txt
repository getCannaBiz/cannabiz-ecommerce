=== WP Dispensary's Styles add-on ===
Contributors: wpdispensary, deviodigital
Tags: dispensary, cannabis, weed, marijuana, styles, customizer
Requires at least: 3.0.1
Tested up to: 5.3.2
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Take complete creative control over your WP Dispensary powered cannabis menu.

== Description ==

Take complete creative control over your WP Dispensary powered cannabis menu.

== Changelog ==

= 1.5 =
* Added array_key_exists check to list styles shortcode options in `admin/class-wpd-styles-filters.php`
* Updated product title width on smaller devices in `public/css/wpd-styles-public.css`
* Updated main wrapper with flexbox in `public/css/wpd-styles-public.css`
* Updated CSS to display thumbnails for list displays on mobile screens in `public/css/wpd-styles-public.css`
* Updated title styles for the list display in `public/css/wpd-styles-public.css`
* Updated style of the inventory out of stock warning in the list display in `public/css/wpd-styles-public.css`
* General code cleanup throughout multiple files

= 1.4 =
* Added display="list" support for the wpd_menu shortcode in `admin/class-wpd-styles-filters.php`
* Added category display for Tinctures menu type in `admin/class-wpd-styles-filters.php`
* Updated Styles buttons, removing them from bottom of shortcode output in `admin/class-wpd-styles-filters.php`
* Updated eCommerce buttons, removing them from shortcode "list" display in `admin/class-wpd-styles-filters.php`
* Updated the product images to use the `get_wpd_product_image` filter in `admin/class-wpd-styles-filters.php`
* Updated styles for the `wpd_menu` shortcode when using display="list" in `public/css/wpd-styles-public.css`
* Updated text strings for localization in `languages/wpd-styles.pot`
* General code cleanup throughout multiple files

= 1.3 =
* Added "Buy Now" buttons to Tinctures and Gear menu type shortcodes in `admin/class-wpd-styles-filters.php`
* Added support Tinctures shortcode 'list' display in `admin/class-wpd-styles-filters.php`
* Updated the product pricing for Gear menu type to include price per pack in `admin/class-wpd-styles-filters.php`
* Updated the product details order for `list` display, making `THCA` display after `CBD` in `admin/class-wpd-styles-filters.php`
* Updated the product details to include `CBG` in `admin/class-wpd-styles-filters.php`
* Updated `wpd_wooconnect_buttons` function name in `remove_action` in `admin/class-wpd-styles-filters.php`
* Updated currency codes to use the new function added in WP Dispensary v2.0 in `admin/class-wpd-styles-filters.php`
* Updated CSS to include button styles (needed if Connect for WooCommerce isn't installed) in `public/css/wpd-styles-public.css`
* Updated the translatable text in `languages/wpd-styles.pot`

= 1.2 =
* Added `add_wpd_styles_list_images` function to replace the 'list' display category name in `admin/class-wpd-styles-filters.php`
* Added display list style codes for the Gear menu type in `admin/class-wpd-styles-filters.php`
* Changed `add_wpd_top_stuff` filter name to `add_wpd_styles_list_categories` for easier code reading in `admin/class-wpd-styles-filters.php`
* Fixed bug in WP Dispensary's carousel that wouldn't let the "buy now" button pop up the modal in `public/js/main.js`
* Fixed bug in the 'list' display type so it hides the Top Sellers icons in `admin/class-wpd-styles-filters.php`
* Fixed bug in the 'list' display type so it hides the default Connect for WooCommerce buttons in `admin/class-wpd-styles-filters.php`
* Updated code to remove the NOTICE responses when `WP_DEBUG` is turned on in `admin/class-wpd-styles-filters.php`
* Updated code to change "Buy Now" button to "Out of Stock" in shortcodes if connected WooCommerce product is out of stock in `admin/class-wpd-styles-filters.php`
* Updated the menu item title in 'list' display to have more margin between it and the menu item details below it in `public/css/wpd-styles-public.css`
* Updated mobile styles for the 'list' display in `public/css/wpd-styles-public.css`

= 1.1 =
* Missing release - this version was uploaded prematurely to the website, so we're bypassing it's official release to make sure everyone's plugins update properly.

= 1.0 =
* Initial release
