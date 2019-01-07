### 1.1.1

* Bugfix error notices by switching `$post_ID` to `$post->ID` and including `global $post` in `includes/wpd-ecommerce-orders-functions.php`
* Bugfix code that disabled `view`, `edit`, `quick edit` links, and `Add New` button in `orders/orders-post-type.php`
* Bugfix codes that set pay on pickup price in `wpd-ecommerce.php`
* Hide the payment method row from order details table if set to `0.00` in `orders/orders-metaboxes.php`
* General code cleanup

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