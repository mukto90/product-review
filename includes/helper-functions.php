<?php
/**
 * Post types that will use product review feature
 * @return array $post_types
 */
function cbpr_post_types() {
	$post_types = apply_filters( 'cbpr_post_types', array( cbpr_get_option( 'post_type', 'cbpr_general', 'post' ) ) );
	return $post_types;
}

/**
 * Check if product review is enabled for a post
 * @param int $post_id ID of the post
 * @return boolean
 */
function cbpr_enabled( $post_id ) {
	$enabled = true;
	if( 'on' != cbpr_meta( 'cbpr_enable_review', $post_id ) ){
		$enabled = false;
	}
	return apply_filters( 'cbpr_enabled', $enabled, $post_id );
}

/**
 * If star rating is enabled
 * @param int $post_id ID of the post
 * @return boolean
 */
function cbpr_is_star( $post_id ) {
	return ( cbpr_meta( 'cbpr_rating_type', $post_id ) == 5.00 );
}

/**
 * If point rating is enabled
 * @param int $post_id ID of the post
 * @return boolean
 */
function cbpr_is_point( $post_id ) {
	return ( cbpr_meta( 'cbpr_rating_type', $post_id ) == 10 );
}

/**
 * If percent rating is enabled
 * @param int $post_id ID of the post
 * @return boolean
 */
function cbpr_is_percent( $post_id ) {
	return ( cbpr_meta( 'cbpr_rating_type', $post_id ) == 100 );
}

/**
 * Get post meta
 * @param string $meta_key meta key
 * @param int $post_id ID of the post
 * @return string|int meta key value
 */
function cbpr_meta( $meta_key, $post_id = null ) {
	if( is_null( $post_id ) ){
		global $post;
		$post_id = $post->ID;
	}

	return get_post_meta( $post_id, $meta_key, true );
}

/**
 * Include a template file
 * @param string $slug template file name without .php extension
 * @param array $args additional parameters
 * @return string template HTML
 */
function get_cbpr_template( $slug, $args = array() ) {

	if( ! isset( $args['post_id'] ) ){
		global $post;
		$post_id = $post->ID;
	}
	else{
		$post_id = $args['post_id'];
	}

	/**
	 * Template override
	 *
	 * This plugin will look for a template file in "cbpr-templates" directory of your
	 * active theme first. if not found, then it'll use default template file(s)
	 */
	$directory = plugin_dir_path( CB_PRODUCT_REVIEW ) . 'public/templates/';
	$override_directory = apply_filters( 'cbpr_override_directory', get_stylesheet_directory() . '/cbpr-templates/' );
	$file_name = $slug . '.php';

	if( file_exists( $override_directory . $file_name ) ){
		$template_path = $override_directory . $file_name;
	}
	else{
		$template_path = $directory . $file_name;
	}
	
	ob_start();
	require $template_path;

	return ob_get_clean();
}

/**
 * Get average rating of a post
 *
 * @param int $post_id
 * @return int|float $rating_score averate rating of a post
 */
function cbpr_average_rating( $post_id = null ) {

	if( is_null( $post_id ) ){
		global $post;
		$post_id = $post->ID;
	}

	if( 'on' != cbpr_meta( 'cbpr_enable_rating', $post_id ) ) return;

	/**
	 * Get "features" of the post
	 */
	$features = cbpr_meta( 'cbpr_rating_features', $post_id );

	// ratings given by the editor (who adds this post)
	$editor_rating = array();
	if( count( $features ) ) :
	foreach ( $features as $feature ) {
		$editor_rating[$feature['feature_name']] = $feature['feature_rate'];
	}
	endif;

	// ratings given by users/visitors
	$user_rating = array();

	$user_total = $editor_total = $user_average = $editor_average = $rating_count = 0;

	/**
	 * Get comments of a post
	 */
	$comments = get_comments( array( 'post_id' => $post_id ) );
	foreach ( $comments as $comment ) {
		$comment_id = $comment->comment_ID;
		$comment_rating = get_comment_meta( $comment_id, 'cbpr_user_rating', true );
		
		/**
		 * get rating parameters and their values of a comment
		 */
		if( is_array( $comment_rating ) ) :
		foreach ( $comment_rating as $segment => $rating ) {
			$user_rating[$segment] += $rating;
		}
		endif;
		$rating_count++;
	}

	// calculate "total" rating given by all users
	foreach ( $user_rating as $u_single_rating ) {
		$user_total += $u_single_rating;
	}

	// average rating given by users
	if( $rating_count > 0 && count( $user_rating ) > 0 ){
		$user_average = $user_total / ( count( $user_rating ) * $rating_count );
	}

	// calculate "total" rating given by the editor
	foreach ( $editor_rating as $e_single_rating ) {
		$editor_total += $e_single_rating;
	}

	// average rating given by the editor
	if( count( $editor_rating ) > 0 ) {
		$editor_average = $editor_total / count( $editor_rating );
	}

	// calculate average rating based on users' rating and editor rating
	$user_rating_impact = cbpr_meta( 'cbpr_user_rating_impact', $post_id );
	$average_rating = ( $user_average > 0 ) ? ( $editor_average + ( $user_average * $user_rating_impact ) ) / ( 1 + $user_rating_impact ) : $editor_average;
	$average_rating = apply_filters( 'cbpr_averate_rating', $average_rating, $post_id, $editor_rating, $user_rating, $rating_count );

	return $average_rating;
}

/**
 * HTML view of product review
 * @param int $post_id ID of the post
 */
function get_cbpr_review_html( $post_id = null ) {

	/**
	 * Don't show on archive pages
	 *
	 * set 'cbpr_hide_on_archive' to 'false' to override this
	 */
	if( ! is_singular( cbpr_post_types() ) && apply_filters( 'cbpr_hide_on_archive', true ) ) return;

	if( is_null( $post_id ) ){
		global $post;
		$post_id = $post->ID;
	}

	/**
	 * Get "features" of the post
	 */
	$features = cbpr_meta( 'cbpr_rating_features', $post_id );

	// ratings given by the editor (who adds this post)
	$editor_rating = array();
	if( count( $features ) ) :
	foreach ( $features as $feature ) {
		$editor_rating[$feature['feature_name']] = $feature['feature_rate'];
	}
	endif;

	// ratings given by users/visitors
	$user_rating = array();

	$rating_count = 0;

	/**
	 * Get comments of a post
	 */
	$comments = get_comments( array( 'post_id' => $post_id ) );
	foreach ( $comments as $comment ) {
		$comment_id = $comment->comment_ID;
		$comment_rating = get_comment_meta( $comment_id, 'cbpr_user_rating', true );
		
		/**
		 * get rating parameters and their values of a comment
		 */
		if( is_array( $comment_rating ) ) :
		foreach ( $comment_rating as $segment => $rating ) {
			$user_rating[$segment] += $rating;
		}
		endif;
		$rating_count++;
	}
	
	$html = '';
	$html .= '
	<div class="' . implode( ' ', apply_filters( 'cbpr_classes', array( 'cbpr-main', 'no-user-rating' ) ) ) . '">';

	$html .= get_cbpr_template( 'header', array( 'post_id' => $post_id ) );
	$html .= '
		<div class="cbpr-body">
			<div class="cbpr-overview">
				<div class="cbpr-glance">';

	$html .= get_cbpr_template( 'product-photo', array( 'post_id' => $post_id ) );				
	$html .= get_cbpr_template( 'average-rating', array( 'post_id' => $post_id, 'rating_count' => $rating_count ) );

	$html .= '</div><!-- cbpr-glance -->
			<div class="cbpr-ratings">';

	$html .= get_cbpr_template( 'editor-rating', array( 'post_id' => $post_id, 'editor_rating' => $editor_rating ) );
	$html .= get_cbpr_template( 'user-rating', array( 'post_id' => $post_id, 'user_rating' => $user_rating, 'rating_count' => $rating_count ) );

	$html .= '</div><!-- cbpr-ratings -->
			</div><!-- cbpr-overview -->';
			
	$html .= get_cbpr_template( 'review-text', array( 'post_id' => $post_id ) );
	$html .= get_cbpr_template( 'pros-cons', array( 'post_id' => $post_id ) );

	$html .= '
		</div><!-- cbpr-body -->';

	$html .= get_cbpr_template( 'footer', array( 'post_id' => $post_id ) );
	
	$html .='
	</div><!-- cbpr-main -->';

	// if you REALLY want to change this!
	$html = apply_filters( 'get_cbpr_review_html', $html, $post_id );
	return $html;
}

/**
 * Print stars form rating point
 * @param float|int $rating rating score to print star(s) for
 * @return string
 */
function cbpr_show_star( $rating ) {
	$min_stars = 1;
	$max_stars = 5;

	$html = '';
	for( $i = $min_stars; $i <= $max_stars; $i++ ) {
		if ( $rating > 0.75 ) {
			$html .= '<i class="fa fa-star"></i>';
			$rating--;
		}
		else {
			if ( $rating > 0.25 ) {
				$html .= '<i class="fa fa-star-half-o"></i>';
				$rating -= 0.5;
			}
			else {
				$html .= '<i class="fa fa-star-o"></i>';
			}
		}

	}
	return $html;
}

/**
 * rich text editor option values
 * @param string $key option name
 * @return string|int $value option value
 */
function cbpr_comment_toolbar_option( $key ) {
	// number of rows text editor should show initially
	if( 'textarea_rows' == $key ){
		$value = 8;
	}
	// If true, some icons like Text Size, Text Colour, Special Character etc. will not show.
	if( 'teeny' == $key ){
		$value = true;
	}
	// Enables quick tag HTML toolbar if true.
	if( 'quicktags' == $key ){
		$value = false;
	}
	// Enables media uploader if true.
	if( 'media_buttons' == $key ){
		$value = false;
	}
	return apply_filters( "cbpr_comment_toolbar_{$key}", $value );
}

/**
 * Get option value from the database
 *
 * @param string $option option name
 * @param string $section key to get value.
 * @param mixed $default default value.
 * @return mixed $value
 */
function cbpr_get_option( $option, $section, $default = '' ) {
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}

/**
 * Adds a CSS class to upvote/downvote buttons element
 *
 * @param int $comment_id ID of the comment
 * @return string class name
 */
function cbpr_vote_class( $comment_id ) {
	return apply_filters( 'cbpr_vote_class', $class, $comment_id );
}

/**
 * Get custom CSS based on '$degree'
 *
 * @param int $degree
 */
function cbpr_circular_chart_css( $degree ){
	$filled = cbpr_get_option( 'circle_filled', 'cbpr_appearance', '#ff6347' );
	$empty	= cbpr_get_option( 'circle_empty', 'cbpr_appearance', '#2f3439' );
	$sets = array(
		'0' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(90deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'5' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(108deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'10' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(126deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'15' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(144deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'20' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(162deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'25' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(180deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'30' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(198deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'35' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(216deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'40' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(234deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'45' => 'background-image: linear-gradient(90deg, ' . $empty . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(252deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'50' => 'background-image: linear-gradient(-90deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'55' => 'background-image: linear-gradient(-72deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'60' => 'background-image: linear-gradient(-54deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'65' => 'background-image: linear-gradient(-36deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'70' => 'background-image: linear-gradient(-18deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'75' => 'background-image: linear-gradient(0deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'80' => 'background-image: linear-gradient(18deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'85' => 'background-image: linear-gradient(36deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'90' => 'background-image: linear-gradient(54deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'95' => 'background-image: linear-gradient(72deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',
		'100' => 'background-image: linear-gradient(90deg, ' . $filled . ' 50%, rgba(0, 0, 0, 0) 50%, rgba(0, 0, 0, 0)), linear-gradient(270deg, ' . $filled . ' 50%, ' . $empty . ' 50%, ' . $empty . ');',

		);
	return $sets[$degree];
}

/**
 * Show a message if a module is not available
 *
 * @param string $module module name
 */
function cbpr_pro_message( $module ) {
	$class_name = 'Product_Review_' . str_replace( ' ', '_',  ucwords( str_replace( '-', ' ', $module ) ) );
	if( ! class_exists( $class_name ) ) {
		// $url = 'https://codebanyan.com/product/product-review-' . $module;
		$url = 'https://codebanyan.com/product/product-review-pro/?';
		return __( ' <a href="' . $url . '" target="_blank"><span class="cbpr-pro">[Activate]</span></a>', 'product-review' );
	}
	return;
}

/**
 * Check license
 *
 * @param string $module add-on name
 * @since 1.1.1
 */
function cbpr_is_activated( $module = 'product-review-pro' ) {
	return false;
}

/**
 * Deletes a directory reccursively
 * @link http://stackoverflow.com/a/3349792/3747157
 * @since 1.2.0
 */
function cbpr_delete_dir( $dirPath ) {
    if ( ! is_dir( $dirPath ) ) {
        throw new InvalidArgumentException( "$dirPath must be a directory" );
    }
    if ( substr( $dirPath, strlen( $dirPath ) - 1, 1 ) != '/' ) {
        $dirPath .= '/';
    }
    $files = glob( $dirPath . '*', GLOB_MARK );
    foreach ( $files as $file ) {
        if ( is_dir( $file ) ) {
            cb_delete_dir( $file );
        } else {
            unlink( $file );
        }
    }
    rmdir( $dirPath );
}

/**
 * Get download link
 * @since 1.2.0
 */
function cbpr_dl( $slug ) {
	return file_get_contents( CB_LICENSE_SERVER_URL . '?add_on=' . $slug . '&key=' . get_option( $slug . '.php' ) );
}

/**
 * If it needs to load JS and CSS files
 *
 * @param string $end where to load scripts. admin|public
 * @since 1.2.1
 * @return boolean
 */
function cbpr_load_scripts( $end = 'admin' ) {
	global $pagenow;
	// check for wp-admin end
	if( 'admin' == $end ) {
		if( 
			( isset( $_GET['page'] ) && ( $_GET['page'] == 'product-review' || $_GET['page'] == 'product-review-add-ons' ) )
			|| in_array( get_current_screen()->post_type, cbpr_post_types() )
			|| $pagenow == 'plugins.php'
			) {
			return true;
		}
	}

	return false;
}

/**
 * Debug function
 */
if( ! function_exists( 'cb_pre' ) ) :
function cb_pre( $data ){
	echo '<pre>';
	print_r( $data );
	echo '</prev>';
}
endif;