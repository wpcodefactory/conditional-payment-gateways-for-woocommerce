=== Conditional Payment Gateways for WooCommerce ===
Contributors: wpcodefactory, anbinder, karzin, omardabbas
Tags: woocommerce, payment gateway, woo commerce
Requires at least: 4.4
Tested up to: 6.8
Stable tag: 2.5.1
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage payment gateways in WooCommerce. Beautifully.

== Description ==

**Conditional Payment Gateways for WooCommerce** plugin lets you hide payment gateways in WooCommerce based on various **conditions**, for example, minimum or maximum cart amount, current date and time, current customer IP or user ID.

### &#9989; Available Conditions ###

Plugin includes these **modules** (i.e., "conditions"):

* **Cart total** - Hides WooCommerce payment gateways based on minimum and maximum cart (i.e., order) amounts.
* **Date and time** - Hides WooCommerce payment gateways by current date and time.
* **Customer IP** - Hides WooCommerce payment gateways by current user IP.
* **User** - Hides WooCommerce payment gateways by current user ID.
* **User Role** - Hides WooCommerce payment gateways by current user role.
* **Language** - Hides WooCommerce payment gateways by the current WPML or Polylang language.
* **Currency** - Hides WooCommerce payment gateways by the current currency. For example, this is useful, if you are using some additional currency switcher plugin.
* **Country** - Hides WooCommerce payment gateways by the current user country (by IP, billing or shipping country).
* **Product** - Hides WooCommerce payment gateways by cart products.
* **Product Category** - Hides WooCommerce payment gateways by cart product categories.
* **Product Tag** - Hides WooCommerce payment gateways by cart product tags.
* **Product Shipping Class** - Hides WooCommerce payment gateways by cart product shipping classes.
* **Product Taxonomy** - Hides WooCommerce payment gateways by cart product custom taxonomy, e.g., brands.
* **Product Title** - Hides WooCommerce payment gateways by cart product titles (or descriptions).

### &#127942; Premium Version ###

Free version allows setting conditions for all standard WooCommerce payment gateways, i.e.:

* Direct bank transfer,
* Check payments,
* Cash on delivery (COD),
* PayPal.

With [Conditional Payment Gateways for WooCommerce Pro](https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/) you can set conditions for **any payment gateway**.

### &#128472; Feedback ###

* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* [Visit plugin site](https://wpfactory.com/item/conditional-payment-gateways-for-woocommerce/).

### &#8505; More ###

* The plugin is **"High-Performance Order Storage (HPOS)"** compatible.

== Installation ==

1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Start by visiting plugin settings at "WPFactory > Conditional Payment Gateways".

== Changelog ==

= 2.5.1 - 11/09/2025 =
* WC tested up to: 10.1.

= 2.5.0 - 28/06/2025 =
* Dev - Security - Output escaped.
* Dev - Plugin settings moved to the "WPFactory" menu.
* Dev - WPFactory "Recommendations" added.
* Dev - WPFactory "Key Manager" added.
* Dev - Code refactoring.
* Dev - Coding standards improved.
* WC tested up to: 9.9.
* Tested up to: 6.8.

= 2.4.0 - 16/08/2024 =
* Dev - "Product" module added.
* Dev - "Product Category" module added.
* Dev - "Product Tag" module added.
* Dev - "Product Shipping Class" module added.
* Dev - "Product Taxonomy" module added.
* Dev - Default module priorities updated.
* Dev - Admin settings restyled.
* Dev - Developers - `alg_wc_cpg_gateway_settings_{$module_id}` filter added.

= 2.3.0 - 13/08/2024 =
* Dev - "User Role" module added.
* Dev - "Country" module added.
* Dev - "Product Title" module added.
* Dev - Default module priorities updated.
* Dev - Admin settings descriptions updated.

= 2.2.2 - 11/08/2024 =
* Dev - Customer IP - Now accepts CIDR ranges.
* Tested up to: 6.6.

= 2.2.1 - 30/07/2024 =
* WC tested up to: 9.1.
* Tested up to: 6.5.
* WooCommerce added to the "Requires Plugins" (plugin header).

= 2.2.0 - 26/01/2024 =
* Dev – "High-Performance Order Storage (HPOS)" compatibility.
* Dev - PHP 8.2 compatibility - "Creation of dynamic property is deprecated" notice fixed.
* Dev - Admin settings descriptions updated.
* WC tested up to: 8.5.
* Tested up to: 6.4.

= 2.1.3 - 24/09/2023 =
* WC tested up to: 8.1.
* Tested up to: 6.3.

= 2.1.2 - 18/06/2023 =
* WC tested up to: 7.8.
* Tested up to: 6.2.

= 2.1.1 - 10/11/2022 =
* WC tested up to: 7.1.
* Tested up to: 6.1.
* Readme.txt updated.
* Deploy script added.

= 2.1.0 - 28/12/2021 =
* Fix - Notices - Adding notices only on AJAX now.
* Dev - Notices - `%current_raw%` placeholder added.
* Dev - "Currency" module added.
* Dev - "Language" module added.
* Dev - Admin settings restyled.
* Dev - Code refactoring.
* WC tested up to: 6.0.
* Tested up to: 5.8.

= 2.0.1 - 26/03/2021 =
* Dev - Shortcodes - `[alg_wc_cpg_cart_total]` - Removing and then re-adding the main filter when calling WooCommerce `calculate_totals()` function.
* Dev - Admin settings restyled; descriptions updated.
* Dev - Free plugin version released.

= 2.0.0 - 16/03/2021 =
* Dev - "Customer IP" module added.
* Dev - "User" module added.
* Dev - "Date/Time" module added.
* Dev - Cart Total - Cart total is calculated with shortcode now. Checkbox options (i.e., "Exclude taxes/shipping/discounts") removed - this is replaced by shortcode attributes now.
* Dev - Cart Total - Additional notice - Placeholders renamed: `%min_amount%` and `%max_amount%` are now replaced by a single `%value%` placeholder. `%result%` (i.e., current cart total) placeholder added.
* Dev - General - "Leave at least one gateway" option added.
* Dev - General - "Debug" option added.
* Dev - Shortcodes - `[alg_wc_cpg_if]` shortcode added.
* Dev - Shortcodes - `[alg_wc_cpg_cart_total]` shortcode added.
* Dev - Shortcodes - `[alg_wc_cpg_user_id]` shortcode added.
* Dev - Shortcodes - `[alg_wc_pgmma_translate]` renamed to `[alg_wc_cpg_translate]`.
* Dev - Major code refactoring.
* Plugin renamed (was "Payment Gateways Min Max Amounts for WooCommerce").
* WC tested up to: 5.1.
* Tested up to: 5.7.

= 1.1.0 - 07/11/2019 =
* Fix - Cart Total - Comparing float values with epsilon now.
* Dev - Cart Total - Option IDs for all settings updated. Min/max amounts are saved in array now.
* Dev - Cart Total - "Exclude taxes" option added.
* Dev - Cart Total - "Exclude discounts" option added.
* Dev - Cart Total - Shortcodes are now processed in plugin notices on checkout. Shortcode added for WPML/Polylang translations.
* Dev - Cart Total - Min and max notices can be displayed simultaneously now.
* Dev - Cart Total - Admin settings restyled.
* Dev - Cart Total - Code refactoring.
* Plugin URI updated.
* WC tested up to: 3.8.
* Tested up to: 5.2.

= 1.0.0 - 27/04/2018 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
