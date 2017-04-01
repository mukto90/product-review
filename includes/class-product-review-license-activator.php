<?php
/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'CB_SECRET_KEY', '580cc082161006.41870101' ); // "Secret Key for License Verification Requests" value from license server
define( 'CB_LICENSE_SERVER_URL', 'https://codebanyan.com' ); // this will be the base URL of license server
define( 'CB_ITEM_REFERENCE', 'Product Review Add-on' ); // plugin name
/**
 * Main class for the plugin
 * @package WordPress
 * @subpackage Product_Review_License_Activator
 * @author Nazmul Ahsan
 * @since 1.1.1
 */
if( ! class_exists( 'Product_Review_License_Activator' ) ) :

class Product_Review_License_Activator {
	
	public $plugin;

	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$plugin_base = $this->plugin . '/' . $this->plugin . '.php';
		add_filter( 'plugin_action_links_' . plugin_basename( $plugin_base ), array( $this, 'form' ) );
	}

	function form( $links ) {
		$key = $this->plugin . '.php';
		$links[] = '<a href="#" id="plugin_' . $key . '" class="cb-updater" data-plugin="' . $key . '">Update</a><span class="show-update-msg"></span>';
		$links[] = '
			<div id="div_' . $this->plugin . '" class="cbpr-activation-div">
			    <input type="password" id="' . $key . '" name="' . $key . '" value="' . get_option( $key ) . '" class="key-field" placeholder="Input your license key" >
		        <input type="hidden" name="plugin_key" value="' . $key . '" />
		        <input type="button" name="activate_license" value="Activate" class="button-primary" />
		        <input type="button" name="deactivate_license" value="Deactivate" class="button" />
		        <span class="cbpr-message"></span>
			</div>
			';
		return $links;
	}

}

endif;

function cbpr_license_activator( $plugin ) {
	return ( new Product_Review_License_Activator( $plugin ) );
}

$add_ons = array( 'product-review-pro', 'product-review-rich-snippet', 'product-review-style-editor', 'product-review-pros-cons', 'product-review-user-rating' );
foreach ( $add_ons as $add_on ) {
	cbpr_license_activator( $add_on );
}