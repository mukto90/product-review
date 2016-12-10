<?php
/**
 * Creates rating related meta box
 */
add_action( 'add_meta_boxes', 'cbpr_feature_add_metabox', 3 );

add_action( 'save_post', 'cbpr_feature_save_postdata' );

function cbpr_feature_add_metabox() {
    add_meta_box( 'cbpr_feature_settings', __( 'Features', 'product-review' ), 'cbpr_feature_metabox_callback', cbpr_post_types() );
}

function cbpr_feature_metabox_callback() {
    global $post;
    wp_nonce_field( plugin_basename( __FILE__ ), 'cbpr_feature_meta_nonce' );
    ?>
    <div id="cbpr-rating-features">
    <?php

    do_action( 'cbpr_rating_metabox_start' );

    echo '<ul class="sortable">';
    $cbpr_rating_features = get_post_meta( $post->ID, 'cbpr_rating_features', true );
    $feature_count = 0;
    echo '
        <li class="li-header">
            <span class="cbpr-feature-drag"></span>
            <span class="cbpr-feature-name">' . __( 'Feature Name', 'product-review' ) . '</span>
            <span class="cbpr-feature-rate">' . __( 'Rate out of <span class="rate-scale">5.00</span>', 'product-review' ) . '</span>
            <span class="cbpr-feature-delete"></span>
        </li>';
    if ( count( $cbpr_rating_features ) > 0 && $cbpr_rating_features != '' ) {
        foreach( $cbpr_rating_features as $cbpr_rating_feature ) {
            if ( isset( $cbpr_rating_feature['feature_name'] ) || isset( $cbpr_rating_feature['feature_rate'] ) ) {
                printf( '
                    <li>
                        <span class="cbpr-feature-drag dashicons dashicons-menu"></span>
                        <span class="cbpr-feature-name"><input type="text" name="cbpr_rating_features[%1$s][feature_name]" value="%2$s" /></span>
                        <span class="cbpr-feature-rate"><input type="number" min="0" max="5" step="0.01" name="cbpr_rating_features[%1$s][feature_rate]" value="%3$s" /></span>
                        <span class="cbpr-feature-delete"><span class="feature-remove button">%4$s</span></span>
                    </li>', $feature_count, $cbpr_rating_feature['feature_name'], $cbpr_rating_feature['feature_rate'], __( 'X' ) );
                $feature_count = $feature_count +1;
            }
        }
    }
    echo '</ul>';

    do_action( 'cbpr_rating_metabox_end' );

    ?>
<span class="feature-add button"><?php _e( 'Add New', 'product-review' ); ?></span>
<span class="feature-average"><?php _e( 'Average Rating', 'product-review' ); ?>&nbsp; <input type="text" id="feature-average-rating" size="9" disabled="" /></span>
<script>
    var $ =jQuery.noConflict();
    $(document).ready(function() {
        var feature_count = <?php echo $feature_count; ?>;
        $(".feature-add").click(function() {
            feature_count = feature_count + 1;

            $('#cbpr-rating-features > ul').append('<li><span class="cbpr-feature-drag dashicons dashicons-menu"></span> <span class="cbpr-feature-name"><input type="text" name="cbpr_rating_features['+feature_count+'][feature_name]" value="" /></span> <span class="cbpr-feature-rate"><input type="number" min="0" max="5" step="0.01" name="cbpr_rating_features['+feature_count+'][feature_rate]" value="" /></span> <span class="cbpr-feature-delete"><span class="feature-remove button">X</span></span></li>' );
            rating_average()
            return false;
        });
        $(".feature-remove").live('click', function() {
            $(this).parent().parent().remove();
            rating_average()
        });
    });
    </script>
</div><?php

}

function cbpr_feature_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( ! isset( $_POST['cbpr_feature_meta_nonce'] ) )
        return;

    if ( ! wp_verify_nonce( $_POST['cbpr_feature_meta_nonce'], plugin_basename( __FILE__ ) ) )
        return;
    $cbpr_rating_features = $_POST['cbpr_rating_features'];

    update_post_meta( $post_id, 'cbpr_rating_features', $cbpr_rating_features );

    do_action( 'cbpr_rating_meta_save' );
}