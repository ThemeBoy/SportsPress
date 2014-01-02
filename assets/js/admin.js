jQuery(document).ready(function($){

	// Tab switcher
	$('.sp-tab-panel').siblings('.sp-tab-bar').find('a').click(function() {
		$(this).closest('li').removeClass('wp-tab').addClass('wp-tab-active').siblings().removeClass('wp-tab-active').addClass('wp-tab').closest('.wp-tab-bar').siblings($(this).attr('href')).show().siblings('.wp-tab-panel').hide();
		return false;
	});

	// Tab filter
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change(function() {
		var val = $(this).val();
		var filter = '.sp-filter-'+val;
		var $filters = $(this).closest('.sp-tab-select').siblings('.sp-tab-select');
		if($filters.length) {
			$filters.each(function() {
				filter += '.sp-filter-'+$(this).find('select').val();
			});
		}
		$(this).closest('.sp-tab-select').siblings('.sp-tab-panel').find('.sp-post').hide(0, function() {
			$(this).find('input').prop('disabled', true);
			$(this).filter(filter).show(0, function() {
				$(this).find('input').prop('disabled', false);
			});
		});
	});

	// Trigger tab filter
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change();

	// Self-cloning
	$('.sp-clone:last').find('select').change(function() {
		$(this).closest('.sp-clone').siblings().find('select').change(function() {
			if($(this).val() == '0') $(this).closest('.sp-clone').remove();
		}).find('option:first').text($(this).closest('.sp-clone').attr('data-remove-text'));
		if($(this).val() != '0') {
			$original = $(this).closest('.sp-clone');
			$original.before($original.clone().find('select').attr('name', $original.attr('data-clone-name') + '[]').val($(this).val()).closest('.sp-clone')).attr('data-clone-num', parseInt($original.attr('data-clone-num')) + 1).find('select').val('0').change();
		}
	});

	// Activate self-cloning
	$('.sp-clone:last').find('select').change();

	// Total stats calculator
	$('.sp-data-table .sp-total input').on('updateTotal', function() {
		index = $(this).parent().index();
		var sum = 0;
		$(this).closest('.sp-data-table').find('.sp-post').each(function() {
			val = $(this).find('td').eq(index).find('input').val();
			if(val == '') {
				val = $(this).find('td').eq(index).find('input').attr('placeholder');
			}
			if($.isNumeric(val)) {
				sum += parseInt(val, 10);
			}
		});
		$(this).val(sum);
	});

	// Activate total stats calculator
	if($('.sp-data-table .sp-total').size()) {
		$('.sp-data-table .sp-post td input').on('keyup', function() {
			$(this).closest('.sp-data-table').find('.sp-total td').eq($(this).parent().index()).find('input').trigger('updateTotal');
		});
	}

	// Equation selector
	$('.sp-equation-selector select:last').change(function() {
		$(this).siblings().change(function() {
			if($(this).val() == '') $(this).remove();
		}).find('option:first').text($(this).attr('data-remove-text'));
		if($(this).val() != '') {
			$(this).before($(this).clone().val($(this).val())).val('').change();
		}
	});

	// Equation selector
	$('.sp-order-selector select:first').change(function() {
		if($(this).val() == '0') {
			$(this).siblings().prop( 'disabled', true );
		} else {
			$(this).siblings().prop( 'disabled', false )
		}
	});

	// Trigger equation selector
	$('.sp-equation-selector select:last').change().siblings().change();

	// Remove slug editor in quick edit for slug-sensitive post types
	$('.inline-edit-sp_result, .inline-edit-sp_outcome, .inline-edit-sp_column, .inline-edit-sp_statistic').find('input[name=post_name]').closest('label').remove();

});