<?php
/**
 * Plugin Name: Custom Product Stock Statuses for WooCommerce
 * Plugin URI: https://github.com/opr/custom-product-stock-statuses-for-woocommerce
 * Description: Lets you add custom stock statuses to your WooCommerce store.
 * Author: opr
 * Author URI: https://github.com/opr
 * Version: 1.0.1
 * Text Domain: custom-product-stock-statuses-for-woocommerce
 * Domain Path: /languages/
 * Tested up to: 5.8.0
 * WC tested up to: 5.7
 * WC requires at least: 2.6
 *
 * License: GNU General Public License v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 */

add_action( 'admin_init', function() {
	add_settings_section(
		'custom-product-stock-statuses-for-woocommerce',
		__( 'Stock statuses', 'custom-product-stock-statuses-for-woocommerce' ),
		'cpssfw_custom_stock_statuses_textarea_callback',
		'custom-product-stock-statuses-for-woocommerce'
	);
	add_settings_field(
		'custom-product-stock-statuses-for-woocommerce',
		__( 'Stock statuses', 'custom-product-stock-statuses-for-woocommerce' ),
		'cpssfw_custom_stock_statuses_textarea_callback',
		'custom-product-stock-statuses-for-woocommerce'
	);
	register_setting( 'custom-product-stock-statuses-for-woocommerce', 'custom-product-stock-statuses-for-woocommerce-stock-statuses' );
} );

add_action('admin_menu', 'cpssfw_register_custom_stock_status_submenu_page');

function cpssfw_custom_stock_statuses_textarea_callback() {
	?>
	<label for="custom-product-stock-statuses-for-woocommerce-stock-statuses">
		<?php echo __( 'Enter your stock statuses. Put each one on a new line.', 'custom-product-stock-statuses-for-woocommerce' ) ?>
	</label>
	<br />
	<textarea cols="40" rows="8" id="custom-product-stock-statuses-for-woocommerce-stock-statuses" name="custom-product-stock-statuses-for-woocommerce-stock-statuses">
<?php echo esc_textarea( get_option( 'custom-product-stock-statuses-for-woocommerce-stock-statuses' ) ) ?>
	</textarea>
	<?php
}

function cpssfw_register_custom_stock_status_submenu_page() {
	add_submenu_page(
		'woocommerce',
		'Custom stock statuses',
		'Custom stock statuses',
		'manage_options',
		'custom-stock-statuses',
		'cpssfw_custom_stock_status_page'
	);
}

function cpssfw_custom_stock_status_page() {
	?>
	<div class="wrap">
		<h3>Add custom stock statuses</h3>
		<form method="post" action="options.php">
			<?php
			settings_fields('custom-product-stock-statuses-for-woocommerce' );
			do_settings_sections( 'custom-product-stock-statuses-for-woocommerce' );
			submit_button();
			?>
		</form>
	</div>

	<?php
}

// Add new stock status options
function cpssfw_filter_custom_woocommerce_product_stock_status_options( $status ) {
	$custom_statuses = explode( "\n", get_option( 'custom-product-stock-statuses-for-woocommerce-stock-statuses' ) );
	foreach( $custom_statuses as $custom_status ) {
		$kebab_case_status = preg_replace( "/(\s+)/", '-', strtolower( $custom_status ) );
		$status[ $kebab_case_status ] = $custom_status;
	}
	return $status;
}
add_filter( 'woocommerce_product_stock_status_options', 'cpssfw_filter_custom_woocommerce_product_stock_status_options', 10, 1 );

