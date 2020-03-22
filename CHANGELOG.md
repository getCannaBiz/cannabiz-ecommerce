# Changelog

### 1.7
* Added `wpd_ecommerce_archive_items_product_image_size` filter in `templates/archive-items.php`
* Bugfix added the product details filter's missing arg in `templates/archive-items.php`
* Updated CSS to remove unnecessary styles for menu items in `assets/css/wpd-ecommerce-public.css`
* Updated styles for account page patients, orders and earnings boxes on mobile in `assets/css/wpd-ecommerce-public.css`
* Updated the single product template to use the `wpd_product_image` helper function in `templates/single-item.php`
* Updated archive template to use flexbox in `templates/archive-items.php`
* Updated flexbox styles for archive template in `assets/css/wpd-ecommerce-public.css`

### 1.6
* Added `wpd_ecommerce_payment_types` helper function in `includes/wpd-ecommerce-core-functions.php`
* Added `wpd_ecommerce_checkout_return_to_menu_url` and `wpd_ecommerce_checkout_return_to_menu_text` filters in `checkout/checkout-shortcode.php`
* Added JavaScript file with codes to update the total price when a payment type is selected in `assets/js/wpd-ecommerce-public.js`
* Added styles for the payment types in `Your Orders` table on checkout in `assets/css/wpd-ecommerce-public.css`
* Bugfix total price in cart when a coupon is applied in `cart/cart-shortcode.php`
* Bugfix total price in checkout when a coupon is applied in `checkout/checkout-shortcode.php`
* Bugfix critical error in the `wpd_ecommerce_menu_url` helper function in `inclused/wpd-ecommerce-core-functions.php`
* Updated excise and sales tax checks to hide them if they're NULL or 0 in `cart/cart-shortcode.php`
* Updated payment name check to only display if `payment_type_name` is set in session in `cart/cart-shortcode.php`
* Updated checkout page to only show instructions text if Ground Shipping is activated in `includes/wpd-ecommerce-core-functions.php`
* Updated checkout to include all active payment types as selectable options that update total price in `checkout/checkout-shortcode.php`
* Updated javascript/AJAX to update the total price when a payment type is selected in `wpd-ecommerce.php`
* Updated `post->ID` to use `get_the_ID()` instead in `includes/wpd-ecommerce-core-functions.php`
* Updated `.pot` file with text strings for localization in `languages/wpd-ecommerce.pot`
* General code cleanup throughout various files in the plugin

### 1.5
* Added Font Awesome v4.7 to the admin pages in `wpd-ecommerce.php`
* Added default settings for ground shipping in `wpd-ecommerce.php`
* Added order `date` and `total` columns to All Orders page in `orders/orders-post-type.php`
* Added `thc_topical` to the list of details to show for products in `templates/archive-items.php`
* Added 2 filters for archive items template in `templates/archive-items.php`
* Added `wpd_ecommerce_payment_type_name_ground` filter in `wpd-ecommerce.php`
* Added `wpd_ecommerce_checkout_billing_details_form_countries` filter in `checkout/checkout-shortcode.php`
* Added `wpd_ecommerce_patient_account_details_form_countries` filter in `patients/patient-account-shortcode.php`
* Added `wpd_ecommerce_checkout_success_your_order_table_before` action hook in `checkout/checkout-shortcode.php`
* Added `wpd_ecommerce_checkout_success_your_order_table_before` action hook in `checkout/checkout-shortcode.php`
* Added `wpd_ecommerce_patients_registration_redirect` filter in `includes/wpd-ecommerce-patients-functions.php`
* Added `wpd_ecommerce_template_archive_items_product_details` filter in `templates/archive-items.php`
* Added compounds to the single item template in `templates/single-item.php`
* Update buttons in shortcode with out of stock message if no inventory is available in `includes/wpd-ecommerce-archive-items-functions.php`
* Updated add to cart form to replace it with an out of stock message if no inventory is available in `includes/wpd-ecommerce-core-functions.php`
* Updated `.pot` file with text strings for localization in `languages/wpd-ecommerce.pot`
* Updated the `$_POST` data to use `sanitize_text_field` function in multiple files
* Updated redirects to use `wpd_ecommerce_account_url` helper function in multiple files
* General code cleanup throughout various files in the plugin

### 1.4
* Added `wpd_ecommerce_checkout_after_order_details` filter in `checkout/checkout-shortcode.php`
* Added Ground shipping instructions text on checkout after order details in `includes/wpd-ecommerce-cart-functions.php`
* Added `PAYMENT_TYPE_AMOUNT` defined for Ground shipping option in `wpd-ecommerce.php`
* Added `wpd_ecommerce_payment_type_name_delivery` filter in `wpd-ecommerce.php`
* Added `wpd_ecommerce_payment_type_name_pop` filter in `wpd-ecommerce.php`
* Added default weights for Heavyweights weights in `wpd-ecommerce.php`
* Added default settings for ground shipping in `wpd-ecommerce.php`
* Added 4 filters for the order success emails sent to admin and patient in `checkout/checkout-shortcode.php`
* Added checks for available inventory of product and display error message if patient is trying to add too much to the cart in `includes/wpd-ecommerce-core-functions.php`
* Updated to use 13-50 pounds Heavyweights prices in `includes/wpd-ecommerce-core-functions.php`
* Updated "Return to menu" text in cart functions in `cart/cart-shortcode.php`
* Updated `select a weight` message for concentrates to be hidden if product is only using price_each in `includes/wpd-ecommerce-core-functions.php`
* Updated `.pot` file with text strings for localization in `languages/wpd-ecommerce.pot`
* General code cleanup

### 1.3
* Added 8 action hooks in the patient shortcode in `patient/patient-account-shortcode.php`
* Added `wpd_ecommerce_account_admin_patients_text` filter in `patient/patient-accont-shortcodes.php`
* Added `wpd_ecommerce_single_item_price` filter in `includes/wpd-ecommerce-core-functions.php`
* Added `wpd_ecommerce_cookie_lifetime` filter in `wpd-ecommerce.php`
* Added the ID arguement to `wpd_compounds_simple` helper function in `templates/single-item.php`
* Added price text to items in the product archive template in `templates/archive-items.php`
* Added notifications when no weight/quantity are selected when adding to cart in `includes/wpd-ecommerce-core-functions.php`
* Bugfix the misspelled `_threepounds` meta key in `includes/wpd-ecommerce-core-functions.php`
* Updated filters for concentrates prices to include heavyweights in `includes/wpd-ecommerce-core-functions.php`
* Updated notifications for Flowers and Concentrates to use `isset` instead of `!= NULL` in `includes/wpd-ecommerce-core-functions.php`
* Updated single product images with a link to the full size image in `templates/single-item.php`
* Updated order archive redirects to use the Pages selected in WPD Settings in `templates/archive-orders.php`
* Updated patient account "Country" from input field to select box in `patients/patient-account-shortcode.php`
* Updated checkout "Country" from input field to select box in `checkout/checkout-shortcode.php`
* Updated `buy now` buttons to conditionally display with new `required to shop` setting in `includes/wpd-ecommerce-archive-item-functions.php`
* Updated `wpd_ecommerce_box_notifications_array` filter to use `wpd_menu_types_simple` for array in `includes/wpd-ecommerce-core-functions.php`
* Updated various links to `account`, `cart` and `menu` to user selection options in `includes/wpd-ecommerce-core-functions.php`
* Updated `$new_price` and `$concentrate_prices` variables with default values in `includes/wpd-ecommerce-core-functions.php`
* Updated notifications checks for Flowers and Concentrates to use `isset` instead of `!= NULL` in `includes/wpd-ecommerce-core-functions.php`
* Updated `is_singular` check to use `wpd_menu_types_simple` helper function in `includes/wpd-ecommerce-core-functions.php`
* Updated the `add_to_cart` form to check if `require login to shop` setting is `on` in `includes/wpd-ecommerce-core-functions.php`
* Updated checkout page to hide sales tax and excise tax if they return `0.00` in `checkout/checkout-shortcode.php`
* Updated archive page content width for shelf and strain types in `assets/css/wpd-ecommerce-public.css`
* Updated cart subtotal mobile width in `assets/css/wpd-ecommerce-public.css`
* Updated `.pot` file with new text strings for translation in `languages/wpd-ecommerce.pot`
* General code cleanup throughout various files in the plugin

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
* Updated prices helper function to pass the product ID in `templates/archive-items.php`
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