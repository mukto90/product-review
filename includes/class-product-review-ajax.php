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
		// 
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