function rating_average() {
	var count = total = average = 0;
	$('.cbpr-feature-rate > input').each(function(){
		count++;
		total += Number($(this).val())
	})
	average = total / count;
    // alert(count)
	$('#feature-average-rating').val(average.toFixed(2))
}

$ = new jQuery.noConflict()
$( document ).ready(function() {
	// make items sortable
	$('.sortable').sortable()

	// main switch
	$('#mdc_cmb_cbpr_enable_review').change(function(){
		var selectors = '#mdc_cmb_fieldsetcbpr_enable_rich_snippet, #mdc_cmb_fieldsetcbpr_review_photo, #mdc_cmb_fieldsetcbpr_enable_review_text, #mdc_cmb_fieldsetcbpr_enable_rating, #mdc_cmb_fieldsetcbpr_rating_type, #mdc_cmb_fieldsetcbpr_enable_user_rating, #mdc_cmb_fieldsetcbpr_rating_color, #mdc_cmb_fieldsetcbpr_enable_pros_cons, #mdc_cmb_fieldsetcbpr_enable_affiliate, #mdc_cmb_fieldsetcbpr_review_location';
        if($(this).val() == 'on') {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
        $('#mdc_cmb_cbpr_enable_review_text, #mdc_cmb_cbpr_enable_rating, #mdc_cmb_cbpr_enable_pros_cons, #mdc_cmb_cbpr_enable_affiliate').trigger('change');
    });
    $('#mdc_cmb_cbpr_enable_review').trigger('change');

    // review text switch
	$('#mdc_cmb_cbpr_enable_review_text').change(function(){
       	selectors = '#cbpr_review';
        if($(this).val() == 'on' && $('#mdc_cmb_cbpr_enable_review').val() == 'on' ) {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
    });
    $('#mdc_cmb_cbpr_enable_review_text').trigger('change');
    
    // rating switch
    $('#mdc_cmb_cbpr_enable_rating').change(function(){
        selectors = '#mdc_cmb_fieldsetcbpr_rating_type, #mdc_cmb_fieldsetcbpr_enable_user_rating, #mdc_cmb_fieldsetcbpr_rating_color, #cbpr_feature_settings, #mdc_cmb_fieldsetcbpr_user_rating_impact, #mdc_cmb_fieldsetcbpr_avg_rating_style';
        if($(this).val() == 'on' && $('#mdc_cmb_cbpr_enable_review').val() == 'on' ) {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
        $('#mdc_cmb_cbpr_enable_user_rating').trigger('change');
    });
    $('#mdc_cmb_cbpr_enable_rating').trigger('change');

    // user rating switch
	$('#mdc_cmb_cbpr_enable_user_rating').change(function(){
		selectors = '#mdc_cmb_fieldsetcbpr_user_rating_impact, #mdc_cmb_fieldsetcbpr_enable_rating_wysiwyg, #mdc_cmb_fieldsetcbpr_enable_review_vote, #mdc_cmb_fieldsetcbpr_member_only';
        if($(this).val() == 'on' && $('#mdc_cmb_cbpr_enable_rating').val() == 'on' && $('#mdc_cmb_cbpr_enable_review').val() == 'on' ) {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
    });
    $('#mdc_cmb_cbpr_enable_user_rating').trigger('change');

    // proscons switch
    $('#mdc_cmb_cbpr_enable_pros_cons').change(function(){
        selectors = '#cbpr_procon_settings';
        if($(this).val() == 'on' && $('#mdc_cmb_cbpr_enable_review').val() == 'on' ) {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
    });
    $('#mdc_cmb_cbpr_enable_pros_cons').trigger('change');

    // proscons switch
	$('#mdc_cmb_cbpr_enable_affiliate').change(function(){
		selectors = '#cbpr_affiliate';
        if($(this).val() == 'on' && $('#mdc_cmb_cbpr_enable_review').val() == 'on' ) {
            $(selectors).show()
        }
        else {
            $(selectors).hide()
        }
    });
    $('#mdc_cmb_cbpr_enable_affiliate').trigger('change');

    // change rating standard
    $('#mdc_cmb_cbpr_rating_type').change(function(){
        var scale = $(this).val()
        $('.rate-scale').text(scale)
        $('.cbpr-feature-rate > input').attr('max', scale)
    })
    $('#mdc_cmb_cbpr_rating_type').trigger('change');

    // calculate average
    $('.cbpr-feature-rate > input').live('change paste keyup', function(){
        rating_average()
    })
    $('.cbpr-feature-rate > input').load('change paste keyup', function(){
        rating_average()
    })
});