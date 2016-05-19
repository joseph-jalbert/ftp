jQuery(document).ready(function ($) {
    $('body').on('click', '.youtube-importer', function () {
        var $this = $(this),
            $youtubeId = $this.siblings('.acf-input-wrap').find('input[type=text]'),
            youtubeId = $youtubeId.val(),
            $parent = $this.closest('.acf-row'),
            $error = $parent.find('.video-error'),
            setError = function (message) {
                var msg = typeof message !== 'undefined' ? message : null;
                $error.html(msg);
            };

        $parent.css('opacity', '0.5');

        if (!youtubeId) {
            setError('Missing YouTube ID');
        }


        setError('');


        var data = {
            'action': 'get_youtube_data',
            'youtubeId': youtubeId,
            'postId': $('#post_ID').val()
        };
        $.post(ajaxurl, data, function (response) {
            console.log(response);
            if ( ! response.success ) {
                $parent.css('opacity', '1');
                setError(response.data.message);
                return;
            }
            var id = response.data.image_data.image_id,
                imageUrl = response.data.image_data.image_url,
                title = response.data.video.snippet.localized.title,
                description = response.data.video.snippet.description,
                publishedAt = response.data.video.snippet.publishedAt,
                $acfInput = $parent.find('.acf-field-youtube-thumbnail').find('.acf-input');

            
            $parent.find('textarea[name*="youtube_description"]').val(description);
            $parent.find('input[name*="youtube_title"]').val(title);
            $parent.find('textarea[name*="youtube_description"]').val(description);
            $parent.find('input[name*="upload_date"]').val(publishedAt);
            $acfInput.find('.acf-image-uploader').addClass('has-value');
            $acfInput.find('.acf-hidden input').val(id);
            $acfInput.find('.show-if-value img').attr('src', imageUrl);
            $parent.css('opacity', '1');


        });


    });

});