jQuery(document).ready(function($){

	// Switch tabs
	$('.sp-tab-panel').siblings('.sp-tab-bar').find('a').click(function() {
		$(this).closest('li').removeClass('wp-tab').addClass('wp-tab-active').siblings().removeClass('wp-tab-active').addClass('wp-tab').closest('.wp-tab-bar').siblings($(this).attr('href')).show().siblings('.wp-tab-panel').hide();
		return false;
	});

	// Filter tabs
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change(function() {
		var val = $(this).val();
		$(this).closest('.sp-tab-select').siblings('.sp-tab-panel').find('.sp-post').hide(0, function() {
			$(this).find('input').prop('disabled', true);
			$(this).filter('.sp-filter-'+val).show(0, function() {
				$(this).find('input').prop('disabled', false);
			});
		});
	});

	// Activate tab filters
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change();

	// Stats table checkboxes
	$('.sp-stats-table tr input[type=checkbox]').change(function() {
		$(this).closest('tr').find('input[type=text], input[type=number]').prop('readonly', $(this).prop('checked'));
	})

	// Change title
	$('.sp-title-generator select').change(function() {
		title = $('.sp-title-generator select[value!=0]').map(function(){
			return $(this).find(':selected').html().replace(/&[^;]+;/g, '');
		}).get().join(' vs ');
		$('input[name=post_title]').val(title);
	});
});