<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */

if( 'on' != cbpr_meta( 'cbpr_enable_rating', $post_id ) ) return;

$html = '
<div class="cbpr-editor-rating">
	<h4 class="cbpr-title">' . apply_filters( 'cbpr_editor_rating_heading', __( 'Editor Rating', 'product-review' ) ) . '</h4>';

	// if rating type is set to "star"
	if( cbpr_is_star( $post_id ) ){
		$html .= '
		<div class="cbpr-rating-stars">';

		foreach ( $args['editor_rating'] as $key => $value ) {
			$html .= '
			<div class="cbpr-rating-single">
				<span class="cbpr-rating-feature">' . $key . ': </span>
			<span class="cbpr-rating-star" title="' . $value . '">';

			$html .= cbpr_show_star( $value );

			$html .= '
				<span><!-- cbpr-rating-star -->
			</div><!-- cbpr-rating-single -->';
		}

		$html .= '</div><!-- cbpr-rating-stars -->';
	}

	// if rating type is set to "point"
	elseif( cbpr_is_point( $post_id ) ){
		$html .= '
		<div class="cbpr-progress-bars">';
		foreach ( $args['editor_rating'] as $key => $value ) {
			$percent = $value * 10;
			$html .= '
			<label for="">' . $key . '<span>' . $value . '</span></label>
			<div class="cbpr-progress-single">
				<div class="cbpr-progress-fill" style="width: ' . $percent . '%">&nbsp;</div>
			</div>';
		}
		$html .= '</div><!-- cbpr-progress-bars -->';
	}

	// if rating type is set to "percent"
	elseif( cbpr_is_percent( $post_id ) ){
		$html .= '
		<div class="cbpr-progress-bars">';
		foreach ( $args['editor_rating'] as $key => $value ) {
			$percent = $value;
			$html .= '
			<label for="">' . $key . '<span>' . $percent . '%</span></label>
			<div class="cbpr-progress-single">
				<div class="cbpr-progress-fill" style="width: ' . $percent . '%">&nbsp;</div>
			</div>';
		}
		$html .= '</div><!-- cbpr-progress-bars -->';

	}

$html .='
</div>';

echo $html;