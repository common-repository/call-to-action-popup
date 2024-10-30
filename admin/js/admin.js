(function($){
	jQuery(document).ready(function($){
		$('#cactus-upload-popup-background').click(function(e) {
			var custom_uploader;
			e.preventDefault();

			//If the uploader object has already been created, reopen the dialog
			if (custom_uploader) {
				custom_uploader.open();
				return;
			}

			//Extend the wp.media object
			custom_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
					text: 'Choose Image'
				},
				multiple: false
			});

			//When a file is selected, grab the URL and set it as the text field's value
			custom_uploader.on('select', function() {
				attachment = custom_uploader.state().get('selection').first().toJSON();
				if($('#cactus-popup-background').length > 0){
					$('#cactus-popup-background').val(attachment.url);
				}
			});
			//Open the uploader dialog
			custom_uploader.open();

		});
	});
}(jQuery));