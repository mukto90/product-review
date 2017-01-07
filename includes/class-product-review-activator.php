<?php

/**
 * Fired during plugin activation.
 *
 * @link       http://nazmulahsan.me
 * @since      1.0.0
 * @package    Product_Review
 * @subpackage Product_Review/includes
 * @author     Nazmul Ahsan <mail@nazmulahsan.me>
 */
class Product_Review_Activator {

	/**
	 * plugin is just activated
	 * @since    1.0.0
	 */
	public static function activate() {
		do_action( 'cbpr_activated' );
		add_option( 'cbpr_activated', true );
	}

}
