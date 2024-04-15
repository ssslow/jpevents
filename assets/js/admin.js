jQuery(document).ready(function($) {
    $('#jpevents_image_button').click(function(e) {
        e.preventDefault();
        var imageUploader = wp.media({
            title: 'Upload Image for Event',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = imageUploader.state().get('selection').first().toJSON();
            $('#jpevents_image').val(attachment.url);
        }).open();
    });
});
