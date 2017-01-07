$ = new jQuery.noConflict()
$(document).ready(function(){

	/**
	 * Tooltip for range slider
	 * @link http://codepen.io/chriscoyier/pen/lokyH
	 */
	var el, newPoint, newPlace, offset;
	$("input[type='range'].cbpr-input").bind('change mousemove', function() {
		el = $(this);
		width = el.width();
		newPoint = (el.val() - el.attr("min")) / (el.attr("max") - el.attr("min"));
		offset = -1;
		if (newPoint < 0) { newPlace = 0; }
		else if (newPoint > 1) { newPlace = width; }
		else { newPlace = width * newPoint + offset; offset -= newPoint; }
		el.next("output").text(el.val());
	})
	.trigger('change');


	// click star icons in comment form
	$('.cbpr-input.cbpr-single-star').click(function(){
		var par = $(this).parent()
		var chosen = $(this).data('star')
		var c = 1;
		$('.cbpr-single-star', par).each(function(){
			if( c <= chosen ){
				$(this).removeClass('fa-star-o').addClass('fa-star')
				c++;
			}
			else{
				$(this).removeClass('fa-star').addClass('fa-star-o')
			}
		})
		$('.cbpr-star', par).val(chosen)
	})

	// make some elements square
	$('.cbpr-square').each(function(){
		$(this).height($(this).width())
	})

	// fix percentage line height
	$('.cbpr-progress-radial .cbpr-overlay').css('line-height',$('.cbpr-progress-radial .cbpr-overlay').height()+'px')

	// vote a rating
	$('.cbpr-votable').click(function(){
		var dis = $(this);
		var comment_id = $(this).parent().data('comment-id');
		var vote_type = $(this).hasClass('cbpr-upvote') ? 'upvote' : 'downvote';
		$.ajax({
			data: { 'action' : 'cbpr-vote', 'comment_id' : comment_id, 'vote_type' : vote_type },
			type: 'POST',
			url: ajaxurl,
			success: function(ret){
				$('span', dis).text( '(' + ret + ')' )
			}
		})
	})

	// fancybox for product image
	$('.cbpr-fancybox').fancybox()
})