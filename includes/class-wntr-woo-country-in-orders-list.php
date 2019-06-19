<?php
/**
 * Core class for Winter's Country in Orders List plugin
 *
 * @since      1.0.0
 * @package    Wntr_Woo_Country_In_Orders_List
 * @subpackage Wntr_Woo_Country_In_Orders_List/includes
 * @author     Winter Agency <info@winteragency.se>
 */
class Wntr_Woo_Country_In_Orders_List {
	const SHIPPING_COUNTRY_FILTER_KEY = '_shipping_country';

    /**
     * Used to store current instance of this class.
     *  
	 * @var Wntr_Woo_Country_In_Orders_List
     * @see Wntr_Woo_Country_In_Orders_List::instance() For static method used to get the current instance (or create one if none exists)
	*/
    private static $instance = false;

    /**
     * Instantiate the plugin and register all actions.
     */
	public function __construct() {
        $this->init_hooks();
    }

    /**
     * Hook into WordPress and WooCommerce
     *
     * @return void
     */
    private function init_hooks() {
        // Load translations
		add_action('plugins_loaded', array($this, 'load_text_domain'));
		
		// Render custom column heading
		add_filter('manage_edit-shop_order_columns', array($this, 'shipping_country_column'));

		// Make custom column sortable
        add_filter('manage_edit-shop_order_sortable_columns', array($this, 'shipping_country_column_sortable'));

		// Render custom column content
		add_filter('manage_shop_order_posts_custom_column', array($this, 'shipping_country_column_content'), 10, 2);
		
		// Add filter for shipping country
		add_filter('restrict_manage_posts', array($this, 'shipping_country_filter'));
		
		// Add filter for shipping country
		add_filter('request', array($this, 'filter_orders_by_shipping_country'));
    }

    /**
     * Load plugin translations
     *
     * @return void
     */
    public function load_text_domain() {
        load_plugin_textdomain('wntr-woo-country-in-orders-list', false, basename(dirname(Wntr_Woo_Country_In_Orders_List_PLUGIN_FILE)) . '/languages'); 
	}

	/**
     * Create custom column in orders table in wp-admin.
     *
     * @param array $column
     * @return array
     */
    public function shipping_country_column(array $column): array {
        $column['shipping_country'] = _x('Country', 'admin-table-heading', 'wntr-woo-country-in-orders-list');
        return $column;
	}

	/**
	 * Mark custom column as sortable
	 *
	 * @param array $columns
	 * @return array
	 */
	public function shipping_country_column_sortable(array $columns): array {
		$columns['shipping_country'] = 'shipping_country';
		return $columns;
	}
	
	/**
	 * Render content for the custom column.
	 * If no shipping country exists for an order, revert to showing the billing country.
	 *
	 * @param string $column
	 * @param integer $order_id
	 * @return void
	 */
    public function shipping_country_column_content(string $column, int $order_id) {
        if($column === 'shipping_country') {
			$order = wc_get_order($order_id); 

            if($order->has_shipping_address()) {
                echo WC()->countries->countries[$order->get_shipping_country()];
            } else {
				echo WC()->countries->countries[$order->get_billing_country()];
			}
        }
    }

	/**
	 * Render filter for shipping country
	 *
	 * @return void
	 */
	public function shipping_country_filter() {
		global $post_type;

		if($post_type === 'shop_order') {
			$countries = WC()->countries->countries; ?>

			<select name="_shipping_country" id="dropdown_shipping_country">
				<option value="">
					<?php esc_html_e('All Shipping Countries', 'wntr-woo-country-in-orders-list'); ?>
				</option><?php
				
				foreach ($countries as $key => $country) { ?>
					<option value="<?= esc_attr($key); ?>" <?= esc_attr(isset($_GET[self::SHIPPING_COUNTRY_FILTER_KEY]) ? selected($key, $_GET[self::SHIPPING_COUNTRY_FILTER_KEY], false) : ''); ?>>
						<?= esc_html($country); ?>
					</option><?php
				}?>
			</select><?php
		}
	}

	/**
	 * Filter orders query by selected shipping country
	 *
	 * @param array $vars
	 * @return array
	 */
	public function filter_orders_by_shipping_country(array $vars): array {
		global $typenow;

		if ($typenow === 'shop_order' && isset($_GET[self::SHIPPING_COUNTRY_FILTER_KEY])) {
			$vars['meta_key']   = '_shipping_country';
			$vars['meta_value'] = wc_clean($_GET[self::SHIPPING_COUNTRY_FILTER_KEY]);
		}

		return $vars;
	}

    /**
     * Return current instance of Wntr_Woo_Country_In_Orders_List
     *
     * @return Wntr_Woo_Country_In_Orders_List
     */
    static function &instance(): Wntr_Woo_Country_In_Orders_List {
        if (false === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}