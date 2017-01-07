<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */

if( 'on' != cbpr_meta( 'cbpr_enable_review_text', $post_id ) ) return;

$html ='
<div class="cbpr-review">
	<h3 class="cbpr-title">' . cbpr_meta( 'cbpr_review_title', $post_id ) . '</h3>
	' . wpautop( cbpr_meta( 'cbpr_review_text', $post_id ) ) . '
</div>';
echo $html;