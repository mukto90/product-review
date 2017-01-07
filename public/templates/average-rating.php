<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */

// $args

if( 'on' != cbpr_meta( 'cbpr_enable_rating', $post_id ) ) return;

// let's prepare and format values for display
$rating_score = round( cbpr_average_rating( $post_id ), 2 );

// we need to create a "percentage" value to use in circular rating bar
if( cbpr_is_percent( $post_id ) ) {
	$rating_score .= '%';
}

$degree = $rating_score;
if( cbpr_is_point( $post_id ) ) { $degree = $rating_score * 10; }
if( cbpr_is_star( $post_id ) ) { $degree = $rating_score * 20; }
$degree = round( $degree / 5 ) * 5; // with '5' interval

$html = '';

do_action( 'cbpr_before_average_rating', $post_id, $args );

if( 'circular' == cbpr_meta( 'cbpr_avg_rating_style', $post_id ) ) {

	$html .= '
	<div class="cbpr-average-rating">
		<div class="cbpr-progress-radial cbpr-square" data-degree="' . $degree  . '" style="' . cbpr_circular_chart_css( $degree ) . '">
		  	<div class="cbpr-overlay cbpr-square">
		  		<span>' . $rating_score . '</span>
		  	</div>
		</div>
	</div>';

}
elseif( 'boxed' == cbpr_meta( 'cbpr_avg_rating_style', $post_id ) ) {

	$html .= '
	<div class="cbpr-gp-summary">
		<div class="cbpr-gp-average">' . $rating_score . '</div>
		<div class="cbpr-gp-stars">' . cbpr_show_star( $rating_score ) . '</div>
		<div class="cbpr-gp-users"><i class="fa fa-user"></i> ' . ( $args['rating_count'] + 1 ) . '</div>
	</div>';

}

echo $html;