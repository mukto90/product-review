<?php

/**
 * Registers a Widget
 *
 * @link       http://nazmulahsan.me
 * @since      1.0.0
 * @package    Product_Review
 * @subpackage Product_Review/includes
 * @author     Nazmul Ahsan <mail@nazmulahsan.me>
 */
class Product_Review_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'cbpr-widget',
			__( 'Product Reviews', 'product-review' ),
			array( 'description' => __( 'Shows product reviews', 'product-review' ) ) );
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {
		// output
		$title 			= ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Recent Reviews', 'product-review'  );
		$post_type 		= $instance['post_type'];
		$rating_type 	= $instance['rating_type'];
		$number 		= $instance['number'];
		$show_photo 	= $instance['show_photo'];

		$cbpr = new WP_Query( apply_filters( 'cbpr_widget_query', array(
			'posts_per_page'    => $number,
			'post_type'       	=> $post_type,
		) ) );

		if ( $cbpr->have_posts() ) :
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $cbpr->have_posts() ) : $cbpr->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>">

				<?php if ( $show_photo ) : ?>
				<img src="<?php echo cbpr_meta( 'cbpr_review_photo', get_the_id() ); ?>" alt="<?php get_the_title() ? the_title() : the_ID(); ?>" />
				<?php endif; ?>

				<?php get_the_title() ? the_title() : the_ID(); ?>

				<?php if( 'Star Icon' != $rating_type ) : ?>
					 - <?php echo round( cbpr_average_rating( get_the_id() ), 2 ) . '/' . cbpr_meta( 'cbpr_rating_type', get_the_id() ); ?>
				<?php else: ?>
					<span class="cbpr-widget-stars"><?php echo cbpr_show_star( cbpr_average_rating( get_the_id() ) ); ?></span>
				<?php endif; ?>

				</a>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php
		echo $args['after_widget'];
		wp_reset_postdata();
		endif;
	}

	// Widget Backend 
	public function form( $instance ) {
		$title		= isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Product Review';
		$post_type	= isset( $instance['post_type'] ) ? esc_attr( $instance['post_type'] ) : 'post';
		$rating_type= isset( $instance['rating_type'] ) ? esc_attr( $instance['rating_type'] ) : 'Star Icon';
		$number		= isset( $instance['number'] ) ? esc_attr( $instance['number'] ) : '5';
		$show_photo = isset( $instance['show_photo'] ) ? (bool) $instance['show_photo'] : false;
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'product-review' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts', 'product-review' ); ?></label>
			<input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php _e( 'Post Type:', 'product-review' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'post_type' ); ?>" id="<?php echo $this->get_field_id( 'post_type' ); ?>">
				<option value=""><?php _e( '— Select —', 'product-review' ); ?></option>
				<?php
				$cpts = cbpr_post_types();
				foreach ( $cpts as $cpt ) {
					echo '<option value="' . $cpt . '" ' . selected( $post_type, $cpt, false ) . '>' . $cpt . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'rating_type' ); ?>"><?php _e( 'Rating Type:', 'product-review' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'rating_type' ); ?>" id="<?php echo $this->get_field_id( 'rating_type' ); ?>">
				<option value=""><?php _e( '— Select —', 'product-review' ); ?></option>
				<?php
				$types = array( 'Star Icon', 'Numeric Point' );
				foreach ( $types as $type ) {
					echo '<option value="' . $type . '" ' . selected( $rating_type, $type, false ) . '>' . $type . '</option>';
				}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_photo' ); ?>"><?php _e( 'Show thumbnail?', 'product-review' ); ?></label>
			<input class="checkbox" type="checkbox"<?php checked( $show_photo ); ?> id="<?php echo $this->get_field_id( 'show_photo' ); ?>" name="<?php echo $this->get_field_name( 'show_photo' ); ?>" />
		</p>

		<?php 
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
		$instance['rating_type'] = ( ! empty( $new_instance['rating_type'] ) ) ? strip_tags( $new_instance['rating_type'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : '';
		$instance['show_photo'] = ( ! empty( $new_instance['show_photo'] ) ) ? strip_tags( $new_instance['show_photo'] ) : '';
		return $instance;
	}
}