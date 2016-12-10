<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */

if( 'on' != cbpr_meta( 'cbpr_enable_affiliate', $post_id ) ) return;

$html = '
<div class="cbpr-footer">
	<a href="' . cbpr_meta( 'cbpr_affiliate_button_url', $post_id ) . '" target="' . cbpr_meta( 'cbpr_affiliate_link_open', $post_id ) . '"><button class="cbpr-price-button">' . cbpr_meta( 'cbpr_affiliate_button_text', $post_id ) . '</button></a>
</div>';

echo $html;