<?php

/**
 * @link              http://nazmulahsan.me
 * @since             1.0.0
 * @package           Product_Review
 *
 * Plugin Name:       Product Review
 * Plugin URI:        https://codebanyan.com/product/product-review/?
 * Description:       Product Review Plugin for WordPress
 * Version:           1.2.2
 * Author:            Nazmul Ahsan
 * Author URI:        http://nazmulahsan.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product-review
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// disable E_NOTICE from reporting
error_reporting( error_reporting() & ~E_NOTICE );

define( 'CB_PRODUCT_REVIEW', __FILE__ );

// add required file
require_once plugin_dir_path( CB_PRODUCT_REVIEW ) . 'includes/helper-functions.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-product-review-activator.php
 */
function activate_product_review() {
	require_once plugin_dir_path( CB_PRODUCT_REVIEW ) . 'includes/class-product-review-activator.php';
	Product_Review_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-product-review-deactivator.php
 */
function deactivate_product_review() {
	require_once plugin_dir_path( CB_PRODUCT_REVIEW ) . 'includes/class-product-review-deactivator.php';
	Product_Review_Deactivator::deactivate();
}

register_activation_hook( CB_PRODUCT_REVIEW, 'activate_product_review' );
register_deactivation_hook( CB_PRODUCT_REVIEW, 'deactivate_product_review' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( CB_PRODUCT_REVIEW ) . 'includes/class-product-review.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_cbpr() {
	do_action( 'before_run_cbpr' );
	$plugin_name = 'product-review';
	$version = '1.2.2';
	$plugin = new Product_Review( $plugin_name, $version );
	$plugin->run();
	do_action( 'after_run_cbpr' );
}
run_cbpr();