jQuery(document).ready(function($){
	// Switch tabs
	$('.sp-tab-panel').siblings('.sp-tab-bar').find('a').click(function() {
		$(this).closest('li').removeClass('wp-tab').addClass('wp-tab-active').siblings().removeClass('wp-tab-active').addClass('wp-tab').closest('.wp-tab-bar').siblings($(this).attr('href')).show().siblings('.wp-tab-panel').hide();
		return false;
	});
	// Filter tabs
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change(function() {
		$(this).closest('.sp-tab-select').siblings('.sp-tab-panel').find('.sp-post').hide().filter('.sp-filter-'+$(this).val()).show();
		return;
	});
	// Activate tab filters
	$('.sp-tab-panel').siblings('.sp-tab-select').find('select').change();
});