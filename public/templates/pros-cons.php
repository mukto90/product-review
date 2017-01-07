<?php
/**
 * If you want to modify this template, please create a copy of this
 * file in "cbpr-templates" directory of you active theme
 */

if( 'on' != cbpr_meta( 'cbpr_enable_pros_cons', $post_id ) ) return;

$html ='
<div class="cbpr-proscons">
	<div class="cbpr-pros">
		<h3 class="cbpr-title">' . apply_filters( 'cbpr_pros_heading', __( 'Pros', 'product-review' ) ) . '</h3>
		<ul>';
	$pros = array( array( 'pros_name' => 'Sample Pros 1' ), array( 'pros_name' => 'Sample Pros 2' ), array( 'pros_name' => 'Sample Pros 3' ) );
	$pros = apply_filters( 'cbpr_rating_pros', $pros, $post_id );
	
	if( count( $pros ) ) :
	foreach ( $pros as $pro ) {
		$html .= '<li><i class="fa fa-check"></i> ' . $pro['pros_name'] . '</li>';
	}
	endif;

	$html .='
		</ul>
	</div>
	<div class="cbpr-cons">
		<h3 class="cbpr-title">' . apply_filters( 'cbpr_cons_heading', __( 'Cons', 'product-review' ) ) . '</h3>
		<ul>';
	
	$cons = array( array( 'cons_name' => 'Sample Cons 1' ), array( 'cons_name' => 'Sample Cons 2' ), array( 'cons_name' => 'Sample Cons 3' ) );
	$cons = apply_filters( 'cbpr_rating_cons', $cons, $post_id );
	
	if( count( $cons ) ) :
	foreach ( $cons as $con ) {
		$html .= '<li><i class="fa fa-times"></i> ' . $con['cons_name'] . '</li>';
	}
	endif;

	$html .='
		</ul>
	</div>
</div>';
echo $html;