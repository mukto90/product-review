<?php
/**
 * Creates plugin settings related meta box
 */
require_once dirname( CB_PRODUCT_REVIEW ) . '/vendor/mdc-meta-box/class.mdc-meta-box.php';

$args = array(
    'meta_box_id'   =>  'cbpr_settings',
    'label'         =>  __( 'Product Review Settings', 'product-review' ),
    'post_type'     =>  cbpr_post_types(),
    'context'       =>  'normal', // side|normal|advanced
    'priority'      =>  'high', // high|low
    'hook_priority' =>  1,
    'fields'        =>  array(
        'cbpr_enable_review' => array(
            'name'      =>  'cbpr_enable_review',
            'label'     =>  __( 'Enable Review?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to enable review for this post', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_review', 'cbpr_general', 'off' ),
        ),
        'cbpr_enable_rich_snippet' => array(
            'name'      =>  'cbpr_enable_rich_snippet',
            'label'     =>  __( 'Enable Rich Snippet? Recommended.', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to enable <a href="https://developers.google.com/search/docs/data-types/reviews" target="_blank">rich snippet</a> for this post.', 'product-review' ) . cbpr_pro_message( 'rich-snippet' ),
            'class'     =>  'mdc-meta-field',
            'disabled'  => true,
            'default'   =>  cbpr_get_option( 'enable_rich_snippet', 'cbpr_general', 'on' ),
        ),
        'cbpr_review_photo' => array(
            'name'      =>  'cbpr_review_photo',
            'label'     =>  __( 'Product Photo', 'product-review' ),
            'type'      =>  'file',
            // 'desc'      =>  __( 'Check this if you want to enable review for this post', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  '',
        ),
        'cbpr_enable_review_text' => array(
            'name'      =>  'cbpr_enable_review_text',
            'label'     =>  __( 'Add Review Text?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to add review texts with this post.', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_review_text', 'cbpr_general', 'off' ),
        ),
        'cbpr_enable_affiliate' => array(
            'name'      =>  'cbpr_enable_affiliate',
            'label'     =>  __( 'Add Affiliate Link Button?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to add affiliate link button with review.', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_affiliate', 'cbpr_general', 'off' ),
        ),
        'cbpr_enable_rating' => array(
            'name'      =>  'cbpr_enable_rating',
            'label'     =>  __( 'Enable Product Rating?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to enable rating for this post.', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_rating', 'cbpr_general', 'off' ),
        ),
        'cbpr_rating_type' => array(
            'name'      =>  'cbpr_rating_type',
            'label'     =>  __( 'Choose Rating Type', 'product-review' ),
            'type'      =>  'select',
            'desc'      => __( 'Choose rating type to be used.', 'product-review' ),
            'class'     =>  'mdc-meta-field',
            'options'   =>  array(
                '5.00'   =>  __( 'Star (out of 5.00)', 'product-review' ),
                '10'     =>  __( 'Point (out of 10)', 'product-review' ),
                '100'    =>  __( 'Percent (out of 100)', 'product-review' ),
            ),
            'default'   =>  cbpr_get_option( 'rating_type', 'cbpr_general', '5.00' ),
        ),
        'cbpr_avg_rating_style' => array(
            'name'    => 'cbpr_avg_rating_style',
            'label'   => __( 'Average Rating Style', 'product-review' ),
            'desc'    => __( 'How the average rating will appear in product review?', 'product-review' ),
            'type'    => 'select',
            'class'     =>  'mdc-meta-field',
            'default' =>  cbpr_get_option( 'avg_rating_style', 'cbpr_general', 'circular' ),
            'options' => array(
                'circular'   =>  __( 'Pie Chart', 'product-review' ),
                'boxed'      =>  __( 'Boxed', 'product-review' ),
            ),
        ),
        'cbpr_enable_user_rating' => array(
            'name'      =>  'cbpr_enable_user_rating',
            'label'     =>  __( 'Enable User Rating?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to allow visitors to submit their own ratings', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_user_rating', 'cbpr_general', 'off' ),
        ),
        'cbpr_member_only' => array(
            'name'      =>  'cbpr_member_only',
            'label'     =>  __( 'Member Only?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'User needs to be logged in to submit rating', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
            'class'     =>  'mdc-meta-field',
            'disabled'  => true,
            'default'   =>  cbpr_get_option( 'member_only', 'cbpr_general', 'off' ),
        ),
        'cbpr_enable_rating_wysiwyg' => array(
            'name'      =>  'cbpr_enable_rating_wysiwyg',
            'label'     =>  __( 'Rich editor for user?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'disabled'  => true,
            'desc'      =>  __( 'Check this to enable rich text editor (WYSIWYG) in comment form for users', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  'no',
        ),
        'cbpr_user_rating_impact' => array(
            'name'      =>  'cbpr_user_rating_impact',
            'label'     =>  __( 'Impact of user rating', 'product-review' ),
            'type'      =>  'select',
            'disabled'  => true,
            'desc'      =>  __( 'How much impact of visitor ratings will be applied?', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
            'class'     =>  'mdc-meta-field',
            'options'   =>  array(
                '1.0'     =>  __( '100%', 'product-review' ),
                '0.9'      =>  __( '90%', 'product-review' ),
                '0.8'      =>  __( '80%', 'product-review' ),
                '0.7'      =>  __( '70%', 'product-review' ),
                '0.6'      =>  __( '60%', 'product-review' ),
                '0.5'      =>  __( '50%', 'product-review' ),
                '0.4'      =>  __( '40%', 'product-review' ),
                '0.3'      =>  __( '30%', 'product-review' ),
                '0.2'      =>  __( '20%', 'product-review' ),
                '0.1'      =>  __( '10%', 'product-review' ),
                '0.0'      =>  __( '0%', 'product-review' ),
            ),
            'default'   =>  cbpr_get_option( 'user_rating_impact', 'cbpr_general', '100' ),
        ),
        'cbpr_enable_review_vote' => array(
            'name'      =>  'cbpr_enable_review_vote',
            'label'     =>  __( 'Vote for user rating?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to allow logged in users to upvote/downvote for a user rating', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
            'class'     =>  'mdc-meta-field',
            'disabled'  => true,
            'default'   =>  cbpr_get_option( 'review_vote', 'cbpr_general', 'off' ),
        ),
        'cbpr_enable_pros_cons' => array(
            'name'      =>  'cbpr_enable_pros_cons',
            'label'     =>  __( 'Add Pros &amp; Cons?', 'product-review' ),
            'type'      =>  'select',
            'options'   => array(
                'on'    =>  'Enable',
                'off'   =>  'Disable',
            ),
            'desc'      =>  __( 'Check this to add \'Pros &amp; Cons\' with this review.', 'product-review' ) . cbpr_pro_message( 'pros-cons' ),
            'class'     =>  'mdc-meta-field',
            'default'   =>  cbpr_get_option( 'enable_pros_cons', 'cbpr_general', 'off' ),
        ),
        'cbpr_review_location' => array(
            'name'      =>  'cbpr_review_location',
            'label'     =>  __( 'Review Location', 'product-review' ),
            'type'      =>  'select',
            'class'     =>  'mdc-meta-field',
            'options'    =>  array(
                'top'       =>  __( 'Top of the content', 'product-review' ),
                'bottom'    =>  __( 'Bottom of the content', 'product-review' ),
                'both'      =>  __( 'Both top and bottom of the content', 'product-review' ),
                ),
            'default'   =>  cbpr_get_option( 'review_location', 'cbpr_general', 'bottom' ),
        ),
    )
);

mdc_meta_box( apply_filters( 'cbpr_settings_metabox', $args ) );
