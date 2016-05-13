jQuery(document).ready(function ($) {
    var setupVideo = function () {

        var videoId = $(this).attr('data-video-id');

        $('html, body').animate({
            scrollTop: $(".entry-content").offset().top
        }, 900, function () {
            $('.main-video-wrapper').hide();
            $('#player').show();
            changeVideo(videoId);
            playVideo();
        });


    };

    $('.video-outer-wrap').on('click', '.video-wrapper', function () {

        setupVideo.bind(this)();

    });


});

var XT = XT || {};

window.onYouTubeIframeAPIReady = function () {
    setTimeout(XT.yt.onYouTubeIframeAPIReady, 500);
};

XT.yt = {

    player: null,
    /* load the YouTube API first */
    loadApi: function () {

        var j = document.createElement("script"),
            f = document.getElementsByTagName("script")[0];
        j.src = "//www.youtube.com/iframe_api";
        j.async = true;
        f.parentNode.insertBefore(j, f);
        this.onYouTubeIframeAPIReady();
    },

    /*default youtube api listener*/
    onYouTubeIframeAPIReady: function () {
        window.YT = window.YT || {};
        if (typeof window.YT.Player === 'function') {
            player = new window.YT.Player('player', {
                width: '640',
                height: '390',
                videoId: jQuery('.main-video-wrapper').attr('data-video-id'),
                events: {
                    onStateChange: XT.yt.onPlayerStateChange,
                    onError: XT.yt.onPlayerError,
                    onReady: setTimeout(XT.yt.onPlayerReady, 4000)
                }
            });
            this.player = player;
        }
    },


    getPlayer: function () {
        return this.player;
    },

    init: function () {
        this.loadApi();

    },

};

function changeVideo(id) {
    XT.yt.getPlayer().loadVideoById(id);
}

function playVideo() {
    XT.yt.getPlayer().playVideo();
}

XT.yt.init();


