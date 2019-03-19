### 1.2
* Added support for the Heavyweights add-on's weights in `includes/wpd-ecommerce-core-functions.php`
* Added 2 filters `wpd_ecommerce_single_item_image_size` and `wpd_ecommerce_single_item_no_image` in `templates/single-item.php`
* Added `get_wpd_ecommerce_product_buttons` helper function in `includes/wpd-ecommerce-archive-items-functions.php`
* Updated code to remove empty variable notices in `includes/wpd-ecommerce-core-functions.php`
* Updated empty variables for the orders list in patient account shortcode in `patients/patient-account-shortcode.php`
* Updated taxes variables to account for commas when adding up the total price in `cart/cart-shortcode.php`
* Updated taxes variables to account for commas when adding up the total price in `checkout/checkout-shortcode.php`
* Updated widget styles in `assets/css/wpd-ecommerce-public.css`
* Updated single-item template to remove comma between Shelf types, Strain types and Categories in `templates/single-item.php`
* Updated empty $concentrate_prices variables to get rid of warning in `classes/class-item.php`
* Updated widget hook for Buy buttons so buttons don't show on text-only widget lists in `includes/wpd-ecommerce-archive-items-functions.php`
* Updated cart widget to remove notice of empty variable when cart is empty in `cart/cart-widget.php`
* Updated code to remove empty variable notice for remove_coupon in `includes/wpd-ecommerce-core-functions.php`
* Updated text strings for localization in `includes/wpd-ecommerce-core-functions.php`
* Updated text strings for localization in `patients/patient-account-shortcode.php`
* Updated total earnings in admin account page to check variable and if empty, make total earnings zero in `patients/patient-account-shortcode.php`
* Updated default menu page slug for WPD v2.6+ new shortcode & page in `wpd-ecommerce.php`
* Updated helper functions to pass the product ID in `includes/wpd-ecommerce-core-functions.php`
* Updated helper functions to pass the product ID in `includes/wpd-ecommerce-archive-items-functions.php`
* Updated CSS to hide entry footer on eCommerce product pages in `assets/wpd-ecommerce-public.css`
* Updated `.pot` file with new text strings for translation in `languages/wpd-ecommerce.pot`
* General code cleanup throughout various files in the plugin

### 1.1.1

* Added empty variables to remove Notices with WP_DEBUG in `classes/class-item.php`
* Added empty variables to remove Notices with WP_DEBUG in `includes/wpd-ecommerce-core-functions.php`
* Added empty variables to remove Notices with WP_DEBUG in `orders/orders-metaboxes.php`
* Bugfix error notices by switching `$post_ID` to `$post->ID` and including `global $post` in `includes/wpd-ecommerce-orders-functions.php`
* Bugfix code that disabled `view`, `edit`, `quick edit` links, and `Add New` button in `orders/orders-post-type.php`
* Bugfix codes that set pay on pickup price in `wpd-ecommerce.php`
* Bugfix removed custom capabilites for Orders in `orders/orders-post-type.php`
* Hide the payment method row from order details table if set to `0.00` in `orders/orders-metaboxes.php`
* Updated recent order tables variable in `patients/patient-account-shortcode.php`
* Updated Drivers misspelling on Account page in `patients/patient-account-shortcode.php`
* Updated with general code cleanup

### 1.1

* Added minimum amount checkout setting check in `checkout/checkout-shortcode.php`
* Added 4 Patient Verification fields to Account Details tab in `patients/patient-account-shortcode.php`
* Added 4 Patient Verification fields to edit user screen in `includes/wpd-ecommerce-patients-functions.php`
* Added notifications function to patient account page in `patients/patient-account-shortcode.php`
* Updated CSS with styles for Patient Verification fields in `assets/css/wpd-ecommerce-admin.css`
* Updated CSS with styles for Patient Verification fields in `assets/css/wpd-ecommerce-public.css`
* Updated Account login and registration form title's top margin in `assets/css/wpd-ecommerce-public.css`
* Updated `Flowers` and `Concentrates` weights with new helper function in `cart/cart-shortcode.php`
* Updated `Flowers` and `Concentrates` weights with new helper function in `cart/cart-widget.php`
* Updated `Flowers` and `Concentrates` weights with new helper function in `checkout/checkout-shortcode.php`
* Updated `Flowers` and `Concentrates` weights with new helper function in `includes/wpd-ecommerce-core-functions.php`
* Updated text strings for localization in `cart/cart-shortcode.php`
* Updated text strings for localization in `cart/cart-widget.php`
* Updated `.pot` file with new text strings for localization in `languages/wpd-ecommerce.pot`

### 1.0

* Initial release