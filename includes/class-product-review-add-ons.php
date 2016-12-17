<?php

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main class for the plugin
 * @package Product_Review
 * @subpackage Product_Review_Add_Ons
 * @author Nazmul Ahsan
 */
if( ! class_exists( 'Product_Review_Add_Ons' ) ) :

class Product_Review_Add_Ons {
	
	public static $_instance;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'submenu_page' ) );
	}

	public function submenu_page() {
		add_submenu_page( 'product-review', 'Product Review add-ons', 'Add-ons', 'manage_options', 'product-review-add-ons', array( $this, 'page' ) );
	}

	public function page() {
		echo '<div class="wrap cbpr-wrap">';
        echo '<h2>' . __( 'Power up \'Product Review\' with these add-ons', 'product-review' ) . '</h2>';

        $add_ons = array(
        	'product-review-rich-snippet'	=>	array(
        		'name'	=>	'Rich Snippet',
        		'desc'	=> 'Did you notice those yellow starts in Google search result?<br /><img src="' . plugins_url( 'admin/assets/img/rich-snippets.png', CB_PRODUCT_REVIEW ) . '" /><br /><strong>Rich snippets</strong> are a type of on page mark-up. They are the extra bits of text that appear under search results.  Think of them like bacon bits for search engines. <br /><strong>But, why?</strong> Well, which result was your eye drawn toward?  The one with the pretty yellow stars?  Precisely.  That is why you should care about Rich Snippets. A statistic shows that, <strong>rich snippets can increase your organic visitors by upto 300%!</strong> <a href="https://blog.dashburst.com/google-search-what-are-rich-snippets/" target="_blank">(See here)</a>',
        		'price'	=>	0
        	),
        	'product-review-user-rating'=>	array(
        		'name'	=>	'User Rating',
        		'desc'	=> 'What do your visitors think about the product you are writing review of? <strong>Do they agree with you?</strong> Use this add-on and allow visitors to submit their review as well as segmented ratings. It comes with features including- user rating, rich editor for user reviews, upvote/downvote on existing user reviews and more.',
        		'price'	=>	0
        	),
        	'product-review-style-editor'	=>	array(
        		'name'	=>	'Style Editor',
        		'desc'	=> 'Not satisfied with the color scheme that comes with this plugin? Or maybe it\'s not matching with site\'s appearances? Install this add-on and <strong>customize the style in the way you think</strong>; with color picker and even your own CSS codes!',
        		'price'	=>	0
        	),
        	'product-review-pros-cons'	=>	array(
        		'name'	=>	'Pros &amp; Cons',
        		'desc'	=> 'Writting <strong>Pros &amp; Cons</strong> in a formatted look may seem difficult to you. This add-on will help you add Pros and Cons in a structured way. Just click and write an entry. Different lists for Pros and Cons. Of course!',
        		'price'	=>	0
        	),
        );

        echo '
        <table class="cbpr-add-ons">
        	<!--thead>
        		<tr>
        			<th>Name</th>
        			<th>Description</th>
        			<th>Status</th>
        		</tr>
        	</thead-->
        	<tbody>';

        	foreach ( $add_ons as $name => $data ) {
        		$module = $name . '/' . $name . '.php';
        		echo '
        		<tr>
        			<td>' . $data['name'] . '</td>
        			<td>' . $data['desc'] . '</td>
        			<td>' . self::action_button( $module ) . '</td>
        		</tr>';
        	}

        echo '</tbody>
        </table>';

        if( ! function_exists( 'cb_product_review_pro' ) ) {
        	echo '<p>Liked all add-ons? <a href="http://codebanyan.com/product/product-review-pro" target="_blank"><button class="button button-primary">Get them as a bundle and <strong>save 23%</strong></button></a></p>';
        }

        echo '</div><!-- wrap cbpr-wrap -->';
	}

	public function action_button( $module ) {
		
        $plugins = get_plugins();
		$bundle = 'product-review-pro/product-review-pro.php';
		
		// if bundle activated
		if( function_exists( 'cb_product_review_pro' ) ) {
			return '<a href="' . self::action_link( $bundle, 'deactivate' ) . '"><button class="button">Deactivate</button></a>';
		}

		// bunlde installed, but not activated
		if( is_array( $plugins[$bundle] ) ) {
			return '<a href="' . self::action_link( $bundle, 'activate' ) . '"><button class="button button-success">Activate</button></a>';
		}

		// if add-on installed
		if( is_array( $plugins[$module] ) ) {
			// if add-on activated
			if( in_array( $module, get_option( 'active_plugins' ) ) ) {
				return '<a href="' . self::action_link( $module, 'deactivate' ) . '"><button class="button">Deactivate</button></a>';
			}
			// if add-on deactivated
			else {
				return '<a href="' . self::action_link( $module, 'activate' ) . '"><button class="button button-success">Activate</button></a>';
			}
		}

		// bundle or 'this' add-on not installed
		return '<a href="http://codebanyan.com/product/' . explode( '/', $module )[0] . '" target="_blank"><button class="button button-primary">Purchase</button></a>';
	}

	/**
	* Generate an activation or deactivation URL for a plugin
	*
	* @param string $plugin plugin base file name Example: product-review/product-review.php
	* @param string $action either activate or deactivate
	*
	* @return string $url activation/deactivation URL
	*/
	function action_link( $plugin, $action = 'activate' ) {
		if ( strpos( $plugin, '/' ) ) {
			$plugin = str_replace( '\/', '%2F', $plugin );
		}
		$url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
		$_REQUEST['plugin'] = $plugin;
		$url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
		return $url;
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

endif;

Product_Review_Add_Ons::instance();