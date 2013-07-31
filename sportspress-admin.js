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
	$('.sp-stats-table').on('updateTotals', function() {
		$self = $(this);
		$self.find('.sp-total input').each(function(i) {
			var sum = 0;
			$self.find('.sp-post').each(function() {
				$el = $($(this).find('input')[i]);
				if($el.val() != '')
					if($.isNumeric($el.val())) sum += parseInt($el.val(), 10);
				else
					sum += parseInt($el.attr('placeholder'), 10);
			});
			$(this).attr('placeholder', sum);
		});
	});

	// Activate total stats calculator
	$('.sp-stats-table .sp-post input').on('keyup', function() {
		$(this).closest('.sp-stats-table').trigger('updateTotals');
	});

	// Trigger total stats calculator
	$('.sp-stats-table').trigger('updateTotals');

});