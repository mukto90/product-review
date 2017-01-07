<?php

/**
 * AJAX activities.
 *
 * @link       http://nazmulahsan.me
 * @since      1.0.0
 * @package    Product_Review
 * @subpackage Product_Review/includes
 * @author     Nazmul Ahsan <mail@nazmulahsan.me>
 */
class Product_Review_AJAX {

	/**
	 * The plugin instance
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static $_instance;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_license-activator', array( $this, 'verify' ) );
	}

	/**
	 * @since 1.1.1
	 */
	public function verify() {
		$license_key = $_POST['key'];
        if ( isset( $_REQUEST['operation'] ) ) {
            $api_params = array(
                'slm_action' => ( $_REQUEST['operation'] != 'deactivate_license' ) ? 'slm_activate' : 'slm_deactivate',
                'secret_key' => CB_SECRET_KEY,
                'license_key' => $license_key,
                'registered_domain' => $_SERVER['SERVER_NAME'],
                'item_reference' => urlencode( CB_ITEM_REFERENCE ),
            );

            $query = esc_url_raw( add_query_arg( $api_params, CB_LICENSE_SERVER_URL ) );
            $response = wp_remote_get( $query, array( 'timeout' => 20, 'sslverify' => false ) );

            if ( is_wp_error( $response ) ){
                echo "Unexpected Error! Please try again or contact us.";
            }

            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            if( $license_data->result == 'success' ) {
                echo '<strong style="color:#07811a">' . $license_data->message . '</strong>';
                update_option( $_REQUEST['plugin'], ( $_REQUEST['operation'] == 'deactivate_license' ) ? '' : $license_key ); 
            }
            else{
                echo '<strong style="color:#C8080E">' . $license_data->message . '</strong>';
            }

            wp_die();
        }
	}

	/**
	 * Cloning is forbidden.
	 */
	private function __clone() { }

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	private function __wakeup() { }

	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}

Product_Review_AJAX::instance();