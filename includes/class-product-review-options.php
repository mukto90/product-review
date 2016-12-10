<?php

/**
 * WordPress settings API demo class
 *
 * @author Tareq Hasan
 */

require_once dirname( CB_PRODUCT_REVIEW ) . '/vendor/wordpress-settings-api/class.settings-api.php';

if ( ! class_exists( 'CBPR_Settings_API' ) ):
class CBPR_Settings_API {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
        
        require_once dirname( CB_PRODUCT_REVIEW ) . '/includes/class-product-review-add-ons.php';
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_menu_page( 'Product Review', 'Product Review', 'manage_options', 'product-review', array( $this, 'plugin_page' ), 'dashicons-star-half', '25.5' );
    }

    function get_settings_sections() {
        $sections = array(
            'cbpr_general' => array(
                'id'    => 'cbpr_general',
                'title' => __( 'Default Settings', 'product-review' ),
                'desc'  => __( 'These are default settings and can be overriden by individual posts.', 'product-review' ),
            ),
            'cbpr_appearance' => array(
                'id'    => 'cbpr_appearance',
                'title' => __( 'Appearance Settings', 'product-review' ),
                'desc'  => __( '<span class="cbpr-pro">These features are available in Pro Version only</span>', 'product-review' ),
            )
        );
        return apply_filters( 'cbpr_settings_sections', $sections );
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'cbpr_general' => array(
                'enable_review' => array(
                    'name'  => 'enable_review',
                    'label' => __( 'Enable Review', 'product-review' ),
                    'desc'  => __( 'Check this to enable review for new posts.', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                ),
                'enable_rich_snippet' => array(
                    'name'  => 'enable_rich_snippet',
                    'label' => __( 'Enable Rich Snippet', 'product-review' ),
                    'desc'  => __( 'Check this to enable <a href="https://developers.google.com/search/docs/data-types/reviews" target="_blank">rich snippet</a> for new posts.', 'product-review' ) . cbpr_pro_message( 'rich-snippet' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'disabled' => true,
                ),
                'enable_review_text' => array(
                    'name'  => 'enable_review_text',
                    'label' => __( 'Enable Review Text', 'product-review' ),
                    'desc'  => __( 'Check this to enable review text for new posts.', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                ),
                'enable_affiliate' => array(
                    'name'  => 'enable_affiliate',
                    'label' => __( 'Enable Affiliate Link', 'product-review' ),
                    'desc'  => __( 'Check this to enable affiliate link for new posts.', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                ),
                'enable_rating' => array(
                    'name'  => 'enable_rating',
                    'label' => __( 'Enable Product Rating', 'product-review' ),
                    'desc'  => __( 'Check this to enable product rating for new posts.', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                ),
                'rating_type' => array(
                    'name'    => 'rating_type',
                    'label'   => __( 'Rating Type', 'product-review' ),
                    'desc'    => __( '', 'product-review' ),
                    'desc'    => __( 'Choose rating type to be used.', 'product-review' ),
                    'type'    => 'select',
                    'default' => '5.00',
                    'options' => array(
                        '5.00'   =>  __( 'Star (out of 5.00)', 'product-review'),
                        '10'     =>  __( 'Point (out of 10)', 'product-review'),
                        '100'    =>  __( 'Percent (out of 100)', 'product-review'),
                    ),
                ),
                'avg_rating_style' => array(
                    'name'    => 'avg_rating_style',
                    'label'   => __( 'Average Rating Style', 'product-review' ),
                    'desc'    => __( 'How the average rating will appear in product review?', 'product-review' ),
                    'type'    => 'select',
                    'default' => 'circular',
                    'options' => array(
                        'circular'   =>  __( 'Pie Chart', 'product-review'),
                        'boxed'      =>  __( 'Boxed', 'product-review'),
                    ),
                ),
                'enable_user_rating' => array(
                    'name'  => 'enable_user_rating',
                    'label' => __( 'Enable User Rating', 'product-review' ),
                    'desc'  => __( 'Check this to allow users to submit their rating.', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'disabled' => true,
                ),
                'member_only' => array(
                    'name'  => 'member_only',
                    'label' => __( 'Member Only?', 'product-review' ),
                    'desc'  => __( 'User needs to be logged in to submit rating', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'disabled' => true,
                ),
                'enable_wysiwyg' => array(
                    'name'  => 'enable_wysiwyg',
                    'label' => __( 'Rich editor for user', 'product-review' ),
                    'desc'  => __( 'Check this to enable rich text editor (WYSIWYG) in comment form for users.', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'disabled' => true,
                ),
                 'user_rating_impact' => array(
                    'name'      =>  'user_rating_impact',
                    'label'     =>  __( 'Imapact of user rating', 'product-review' ),
                    'type'      =>  'select',
                    'desc'      =>  __( 'How much impact of visitor ratings will be applied?', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
                    'options'   =>  array(
                        '1.0'     =>  __( '100%', 'product-review'),
                        '0.9'      =>  __( '90%', 'product-review'),
                        '0.8'      =>  __( '80%', 'product-review'),
                        '0.7'      =>  __( '70%', 'product-review'),
                        '0.6'      =>  __( '60%', 'product-review'),
                        '0.5'      =>  __( '50%', 'product-review'),
                        '0.4'      =>  __( '40%', 'product-review'),
                        '0.3'      =>  __( '30%', 'product-review'),
                        '0.2'      =>  __( '20%', 'product-review'),
                        '0.1'      =>  __( '10%', 'product-review'),
                        '0.0'       =>  __( '0%', 'product-review'),
                    ),
                    'disabled' => true,
                    'default'   =>  '1.0',
                ),
                 'review_vote' => array(
                    'name'      =>  'review_vote',
                    'label'     =>  __( 'Vote for user rating?', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'desc'      =>  __( 'Check this to allow logged in users to upvote/downvote for a user rating.', 'product-review' ) . cbpr_pro_message( 'user-rating' ),
                    'class'     =>  'mdc-meta-field',
                    'disabled' => true,
                    'default'   =>  'off',
                ),
                 'enable_pros_cons' => array(
                    'name'      =>  'enable_pros_cons',
                    'label'     =>  __( 'Add Pros &amp; Cons?', 'product-review' ),
                    'type'  => 'select',
                    'options'=> array(
                        'on'   =>  'Enable',
                        'off'    =>  'Disable',
                    ),
                    'desc'      =>  __( 'Check this to add \'Pros &amp; Cons\' with product reviews.', 'product-review' ) . cbpr_pro_message( 'pros-cons' ),
                    'class'     =>  'mdc-meta-field',
                    'disabled' => true,
                    'default'   =>  'off',
                ),
                 'review_location' => array(
                    'name'      =>  'review_location',
                    'label'     =>  __( 'Review Location', 'product-review' ),
                    'type'      =>  'select',
                    'class'     =>  'mdc-meta-field',
                    'options'    =>  array(
                        'top'       =>  __( 'Top of the content', 'product-review' ),
                        'bottom'    =>  __( 'Bottom of the content', 'product-review' ),
                        'both'      =>  __( 'Both top and bottom of the content', 'product-review' ),
                        ),
                    'default'   =>  'bottom',
                ),
            ),
            'cbpr_appearance' => array(
                'icon_icon' => array(
                    'name'    => 'icon_icon',
                    'label'   => __( 'Star Icon Color', 'product-review' ),
                    'desc'    => __( 'Color of star icons for ratings', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#FB9A1D',
                    'disabled'=> true,
                ),
                'prog_bar_fill' => array(
                    'name'    => 'prog_bar_fill',
                    'label'   => __( 'Progress Bar Fill Color', 'product-review' ),
                    'desc'    => __( 'Color of filled area of progress bar for ratings', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#44BB44',
                    'disabled'=> true,
                ),
                'prog_bar_empty' => array(
                    'name'    => 'prog_bar_empty',
                    'label'   => __( 'Progress Bar Empty Color', 'product-review' ),
                    'desc'    => __( 'Color of empty area of progress bar for ratings', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#EEEEEE',
                    'disabled'=> true,
                ),
                'circle_filled' => array(
                    'name'    => 'circle_filled',
                    'label'   => __( 'Pie Chart Average Fill Color', 'product-review' ),
                    'desc'    => __( 'Color of filled area of average rating Pie Chart', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#ff6347',
                    'disabled'=> true,
                ),
                'circle_empty' => array(
                    'name'    => 'circle_empty',
                    'label'   => __( 'Pie Chart Avg. Empty Color', 'product-review' ),
                    'desc'    => __( 'Color of empty area of average rating Pie Chart', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#2f3439',
                    'disabled'=> true,
                ),
                'circle_border' => array(
                    'name'    => 'circle_border',
                    'label'   => __( 'Pie Chart Avg. Border Color', 'product-review' ),
                    'desc'    => __( 'Color of average rating Pie Chart border', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#2f3439',
                    'disabled'=> true,
                ),
                'boxed_base' => array(
                    'name'    => 'boxed_base',
                    'label'   => __( 'Boxed Avg. Base Color', 'product-review' ),
                    'desc'    => __( 'Base color of average rating box', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#444',
                    'disabled'=> true,
                ),
                'affiliate_button' => array(
                    'name'    => 'affiliate_button',
                    'label'   => __( 'Affiliate Button Color', 'product-review' ),
                    'desc'    => __( 'Base color for affiliate button', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'color',
                    'default' => '#3baeda',
                    'disabled'=> true,
                ),
                'custom_css' => array(
                    'name'    => 'custom_css',
                    'label'   => __( 'Custom CSS', 'product-review' ),
                    'desc'    => __( 'Write your own CSS', 'product-review' ) . cbpr_pro_message( 'style-editor' ),
                    'type'    => 'textarea',
                    'disabled'=> true,
                ),
            )
        );

        return apply_filters( 'cbpr_settings_fields', $settings_fields );
    }

    function plugin_page() {
        echo '<div class="wrap cbpr-wrap">';
        echo '<h2>' . __( 'Product Review Settings', 'product-review' ) . '</h2>';
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div><!-- wrap cbpr-wrap -->';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
new CBPR_Settings_API;