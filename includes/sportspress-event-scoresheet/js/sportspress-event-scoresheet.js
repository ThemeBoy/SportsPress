jQuery(document).ready(function($){
    var custom_uploader;
    $('.sp-add-scoresheet').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
		$(this).closest("fieldset").hide().siblings(".sp-scoresheet-field").show();
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose ScoreSheet',
            button: {
                text: 'Choose ScoreSheet'
            },
            multiple: false
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#sp_upload_scoresheet').val(attachment.id);
            $('#sp_upload_scoresheet_url').val(attachment.url);
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
	
	// Removing video embed
	$(".sp-remove-scoresheet").click(function() {
		$(this).closest("fieldset").hide().siblings(".sp-scoresheet-adder").show().siblings(".sp-scoresheet-field").find("input").val(null);
		return false;
	});
	});