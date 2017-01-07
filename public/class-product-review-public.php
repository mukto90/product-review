<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://nazmulahsan.me
 * @package    Product_Review
 * @subpackage Product_Review/public
 * @author     Nazmul Ahsan <mail@nazmulahsan.me>
 */
class Product_Review_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->load_dependencies();

	}

	private function load_dependencies() {	}	

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( CB_PRODUCT_REVIEW ) . 'public/assets/css/product-review-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-responsive', plugin_dir_url( CB_PRODUCT_REVIEW ) . 'public/assets/css/responsive.css', array(), $this->version, 'all' );
		// vendor
		wp_enqueue_style( $this->plugin_name . '-font-awesome', plugin_dir_url( CB_PRODUCT_REVIEW ) . 'vendor/font-awesome/css/font-awesome.min.css', array(), '4.6.3', 'all' );
		wp_enqueue_style( $this->plugin_name . '-jquery-fancybox', plugin_dir_url( CB_PRODUCT_REVIEW ) . 'vendor/fancybox/css/jquery.fancybox-1.3.4.css', array(), '1.3.4', 'all' );

		$inline_style = '';

		wp_add_inline_style( $this->plugin_name, apply_filters( 'cbpr_inline_style', $inline_style ) );

	}

	/**
	 * add some JavaScript/CSS to wp_head
	 */
	public function head() {
		echo '<script>
				var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";
		</script>';
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( CB_PRODUCT_REVIEW ) . 'public/assets/js/product-review-public.js', array( 'jquery' ), $this->version, true );
		// vendor
		wp_enqueue_script( $this->plugin_name . '-fancybox', plugin_dir_url( CB_PRODUCT_REVIEW ) . 'vendor/fancybox/js/jquery.fancybox-1.3.4.js', array( 'jquery' ), '1.3.4', true );

	}

	/**
	 * Show product review along with post content
	 */
	public function show_product_review( $content )	{
		global $post;
		$post_id = $post->ID;
		if( ! cbpr_enabled( $post_id ) ){
			return $content;
		}

		$html = '';
		$html .= get_cbpr_review_html( $post_id );

		if( 'top' == cbpr_meta( 'cbpr_review_location', $post_id ) ){
			return $html . $content;
		}
		if( 'bottom' == cbpr_meta( 'cbpr_review_location', $post_id ) ){
			return $content . $html;
		}
		if( 'both' == cbpr_meta( 'cbpr_review_location', $post_id ) ){
			return $html . $content . $html;
		}
	}

	/**
	 * Register a widget that shows product reviews
	 *
	 * @uses register_widget()
	 */
	public function widget() {
		register_widget( 'Product_Review_Widget' );
	}
}
