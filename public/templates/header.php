<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */
$html = '
<div class="cbpr-header">
	<h3 class="cbpr-title">
		<span class="cbpr-product-name">' . apply_filters( 'cbpr_header_left', get_the_title( $post_id ), $post_id ) . '</span>
		<span class="cbpr-product-price">' . do_action( 'cbpr_header_right', $post_id ) . '</span>
	</h3>
</div>';

echo $html;