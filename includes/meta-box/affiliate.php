<?php
/**
 * Creates affilate link related meta box
 */
require_once dirname( CB_PRODUCT_REVIEW ) . '/vendor/mdc-meta-box/class.mdc-meta-box.php';

$args = array(
    'meta_box_id'   =>  'cbpr_affiliate',
    'label'         =>  __( 'Affiliate Link Button', 'product-review' ),
    'post_type'     =>  cbpr_post_types(),
    'context'       =>  'normal',
    'priority'      =>  'high',
    'hook_priority' =>  5,
    'fields'        =>  array(
        array(
            'name'      =>  'cbpr_affiliate_button_text',
            'label'     =>  __( 'Button Text', 'product-review' ),
            'type'      =>  'text',
            'desc'      => __( ' This will be shown in affiliate button', 'product-review' ),
            'class'     =>  'regular-text',
        ),
        array(
            'name'      =>  'cbpr_affiliate_price',
            'label'     =>  __( 'Price', 'product-review' ),
            'type'      =>  'number',
            'desc'      => __( ' For SEO purpose. No currency symbol.', 'product-review' ),
            'class'     =>  'regular-text',
        ),
        array(
            'name'      =>  'cbpr_affiliate_button_url',
            'label'     =>  __( 'Affiliate Link', 'product-review' ),
            'type'      =>  'url',
            'class'     =>  'mdc-meta-field',
        ),
        array(
            'name'      =>  'cbpr_affiliate_link_open',
            'label'     =>  __( 'Open Link in-', 'product-review' ),
            'type'      =>  'radio',
            'options'   =>  array(
                '_self'      =>  __( 'Current Tab', 'product-review' ),
                '_blank'     =>  __( 'New Tab', 'product-review' ),
            ),
            'class'     =>  'mdc-meta-field',
        ),
    )
);

mdc_meta_box( apply_filters( 'cbpr_affiliate_metabox', $args ) );
