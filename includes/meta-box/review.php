<?php
/**
 * Creates review related meta box
 */
require_once dirname( CB_PRODUCT_REVIEW ) . '/vendor/mdc-meta-box/class.mdc-meta-box.php';

$args = array(
    'meta_box_id'   =>  'cbpr_review',
    'label'         =>  __( 'Review text', 'product-review' ),
    'post_type'     =>  cbpr_post_types(),
    'context'       =>  'normal',
    'priority'      =>  'high',
    'hook_priority' =>  2,
    'fields'        =>  array(
        array(
            'name'      =>  'cbpr_review_title',
            'label'     =>  __( 'Title', 'product-review' ),
            'type'      =>  'text',
            'placeholder'=> __( 'Type your description heading', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  '',
        ),
        array(
            'name'      =>  'cbpr_review_text',
            'label'     =>  __( 'Description', 'product-review' ),
            'type'      =>  'wysiwyg',
            'class'     =>  'mdc-meta-field',
            'width'     =>  '100%',
            'default'   =>  '',
        ),
    )
);

mdc_meta_box( apply_filters( 'cbpr_review_metabox', $args ) );
