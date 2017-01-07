<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */
$html ='
<div class="cbpr-image">
	<a class="cbpr-fancybox" href="' . cbpr_meta( 'cbpr_review_photo', $post_id ) . '"><img src="' . cbpr_meta( 'cbpr_review_photo', $post_id ) . '" alt="' . get_the_title( $post_id ) . '" /></a>
</div>';
echo $html;