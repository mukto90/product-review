<?php

/**
 * Fired during plugin deactivation.
 *
 * @link       http://nazmulahsan.me
 * @since      1.0.0
 * @package    Product_Review
 * @subpackage Product_Review/includes
 * @author     Nazmul Ahsan <mail@nazmulahsan.me>
 */
class Product_Review_Deactivator {

	/**
	 * plugin is just deactivated
	 */
	public static function deactivate() {
		do_action( 'cbpr_deactivated' );
	}

}
