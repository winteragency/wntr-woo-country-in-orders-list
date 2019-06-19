<?php
/**
 * WooCommerce Country in Orders List by Winter ❄
 *
 * @link              https://github.com/winteragency/wntr-woo-country-in-orders-list
 * @since             1.0.0
 * @package           Wntr_Woo_Country_In_Orders_List
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Country in Orders List
 * Plugin URI:        https://github.com/winteragency/wntr-woo-country-in-orders-list
 * Description:       Show shipping countries in a custom column in the orders list in wp-admin and allow sorting and filtering by country.
 * Version:           1.0.0
 * Author:            Winter Agency
 * Author URI:        http://winteragency.se
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wntr-woo-country-in-orders-list
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
	die;
}

define('WNTR_WOO_COUNTRY_IN_ORDERS_LIST_VERSION', '1.0.0');

if (!defined('WNTR_WOO_COUNTRY_IN_ORDERS_LIST_PLUGIN_FILE')) {
	define('WNTR_WOO_COUNTRY_IN_ORDERS_LIST_PLUGIN_FILE', __FILE__);
}

if (!class_exists('Wntr_Woo_Country_In_Orders_List')) {
	require plugin_dir_path(__FILE__) . 'includes/class-wntr-woo-country-in-orders-list.php';
}

function wntrWooCountryInOrdersList() {
  return Wntr_Woo_Country_In_Orders_List::instance();  
}

wntrWooCountryInOrdersList();