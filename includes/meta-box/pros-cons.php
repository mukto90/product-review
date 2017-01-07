<?php
/**
 * Creates pros-cons related meta box
 */
add_action( 'add_meta_boxes', 'cbpr_procon_add_metabox', 4 );

add_action( 'save_post', 'cbpr_procon_save_postdata' );

function cbpr_procon_add_metabox() {
    $meta_box_title = apply_filters( 'cbpr_pros_cons_title', __( 'Pros &amp; Cons', 'product-review' ) . cbpr_pro_message( 'pros-cons' ) );
    add_meta_box( 'cbpr_procon_settings', $meta_box_title, 'cbpr_procon_metabox_callback', cbpr_post_types() );
}

function cbpr_procon_metabox_callback() {
    global $post;
    wp_nonce_field( plugin_basename( __FILE__ ), 'cbpr_procon_meta_nonce' );
    $disabled = apply_filters( 'cbpr_pros_cons_disabled', 'disabled' );
    ?>
    <div id="cbpr-review-procons">
        <div class="cbpr-pros-div">
    <?php

    echo '<label class="mdc-label">' . __( 'Pros', 'product-review' ) . '</label>';

    do_action( 'cbpr_pros_metabox_start' );

    echo '<ul class="sortable">';
    $cbpr_rating_pros = cbpr_meta( 'cbpr_rating_pros', $post->ID );
    $pros = 0;
    if ( count( $cbpr_rating_pros ) > 0 && $cbpr_rating_pros != '' ) {
        foreach( $cbpr_rating_pros as $cbpr_rating_pro ) {
            if ( isset( $cbpr_rating_pro['pros_name'] ) ) {
                printf( '
                    <li>
                        <span class="cbpr-pros-drag dashicons dashicons-menu"></span>
                        <span class="cbpr-pros-name"><input type="text" name="cbpr_rating_pros[%1$s][pros_name]" value="%2$s" %3$s /></span>
                        <span class="cbpr-pros-delete"><span class="pros-remove button">%4$s</span></span>
                    </li>', $pros, $cbpr_rating_pro['pros_name'], $disabled, __( 'X' ) );
                $pros = $pros +1;
            }
        }
    }
    echo '</ul>';

    do_action( 'cbpr_pros_metabox_end' );

    ?>
<span class="pros-add button"><?php _e( 'Add New', 'product-review' ); ?></span>
<script>
    var $ =jQuery.noConflict();
    $(document).ready(function() {
        var pros = <?php echo $pros; ?>;
        $(".pros-add").click(function() {
            pros = pros + 1;

            $('.cbpr-pros-div > ul').append('<li><span class="cbpr-pros-drag dashicons dashicons-menu"></span> <span class="cbpr-pros-name"><input type="text" name="cbpr_rating_pros['+pros+'][pros_name]" value="" <?php echo $disabled; ?> /></span> <span class="cbpr-pros-delete"><span class="pros-remove button">X</span></span></li>' );
            return false;
        });
        $(".pros-remove").live('click', function() {
            $(this).parent().parent().remove();
        });
    });
    </script>
        </div>
        <div class="cbpr-cons-div">
    <?php

    echo '<label class="mdc-label">' . __( 'Cons', 'product-review' ) . '</label>';

    do_action( 'cbpr_cons_metabox_start' );

    echo '<ul class="sortable">';
    $cbpr_rating_cons = cbpr_meta( 'cbpr_rating_cons', $post->ID );
    $cons = 0;
    if ( count( $cbpr_rating_cons ) > 0 && $cbpr_rating_cons != '' ) {
        foreach( $cbpr_rating_cons as $cbpr_rating_con ) {
            if ( isset( $cbpr_rating_con['cons_name'] ) ) {
                printf( '
                    <li>
                        <span class="cbpr-cons-drag dashicons dashicons-menu"></span>
                        <span class="cbpr-cons-name"><input type="text" name="cbpr_rating_cons[%1$s][cons_name]" value="%2$s" %3$s /></span>
                        <span class="cbpr-cons-delete"><span class="cons-remove button">%4$s</span></span>
                    </li>', $cons, $cbpr_rating_con['cons_name'], $disabled, __( 'X' ) );
                $cons = $cons +1;
            }
        }
    }
    echo '</ul>';

    do_action( 'cbpr_cons_metabox_end' );

    ?>
    <span class="cons-add button"><?php _e( 'Add New', 'product-review' ); ?></span>
    <script>
        var $ =jQuery.noConflict();
        $(document).ready(function() {
            var cons = <?php echo $cons; ?>;
            $(".cons-add").click(function() {
                cons = cons + 1;

                $('.cbpr-cons-div > ul').append('<li><span class="cbpr-cons-drag dashicons dashicons-menu"></span> <span class="cbpr-cons-name"><input type="text" name="cbpr_rating_cons['+cons+'][cons_name]" value="" <?php echo $disabled; ?> /></span> <span class="cbpr-cons-delete"><span class="cons-remove button">X</span></span></li>' );
                return false;
            });
            $(".cons-remove").live('click', function() {
                $(this).parent().parent().remove();
            });
        });
        </script>
        </div>
    </div><?php

}

function cbpr_procon_save_postdata( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        return;

    if ( ! isset( $_POST['cbpr_procon_meta_nonce'] ) )
        return;

    if ( ! wp_verify_nonce( $_POST['cbpr_procon_meta_nonce'], plugin_basename( __FILE__ ) ) )
        return;
    $cbpr_rating_pros = $_POST['cbpr_rating_pros'];
    update_post_meta( $post_id, 'cbpr_rating_pros', $cbpr_rating_pros );
    $cbpr_rating_cons = $_POST['cbpr_rating_cons'];
    update_post_meta( $post_id, 'cbpr_rating_cons', $cbpr_rating_cons );

    do_action( 'cbpr_procon_meta_save' );
}