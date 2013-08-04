jQuery(document).ready(function($){

	// Tab switcher
	$('.sp-tab-panel').siblings('.sp-tab-bar').find('a').click(function() {
		$(this).closest('li').removeClass('wp-tab').addClass('wp-tab-active').siblings().removeClass('wp-tab-active').addClass('wp-tab').closest('.wp-tab-bar').siblings($(this).attr('href')).show().siblings('.wp-tab-panel').hide();
		return false;
	});

	// Tab filter
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change(function() {
		var val = $(this).val();
		$(this).closest('.sp-tab-select').siblings('.sp-tab-panel').find('.sp-post').hide(0, function() {
			$(this).find('input').prop('disabled', true);
			$(this).filter('.sp-filter-'+val).show(0, function() {
				$(this).find('input').prop('disabled', false);
			});
		});
	});

	// Trigger tab filter
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change();

	// Title changer
	$('input[name=post_title]').on('updateTitle', function() {
		title = $('.sp-title-generator select[value!=0]').map(function(){
			return $(this).find(':selected').html().replace(/&[^;]+;/g, '');
		}).get().join(' vs ');
		$(this).val(title);
	});

	// Activate title changer
	$('.sp-title-generator select').change(function() {
		$('input[name=post_title]').trigger('updateTitle');
	});

	// Total stats calculator
	$('.sp-stats-table .sp-total input').on('updateTotal', function() {
		index = $(this).parent().index();
		var sum = 0;
		$(this).closest('.sp-stats-table').find('.sp-post').each(function() {
			val = $(this).find('td').eq(index).find('input').val();
			if($.isNumeric(val)) {
				sum += parseInt(val, 10);
			}
		});
		$(this).val(sum);
	});

	// Activate total stats calculator
	if($('.sp-stats-table .sp-total').size()) {
		$('.sp-stats-table .sp-post td input').on('keyup', function() {
			$(this).closest('.sp-stats-table').find('.sp-total td').eq($(this).parent().index()).find('input').trigger('updateTotal');
		});
	}

});