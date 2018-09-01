jQuery(document).ready(function($){
    var custom_uploader;
    $('#sp_upload_scoresheet_button').click(function(e) {
        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
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
            $('#sp_upload_scoresheet').val(attachment.url);
        });
        //Open the uploader dialog
        custom_uploader.open();
    });
	});