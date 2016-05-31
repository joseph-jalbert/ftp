var tag = document.createElement('script');
tag.src = "//www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


jQuery(document).ready(function ($) {
    var setupVideo = function () {

        var videoId = $(this).attr('data-video-id');

        $('html, body').animate({
            scrollTop: $(".entry-content").offset().top
        }, 900, function () {

            $('#player').show();
            playerObject.setVideo(videoId);
            playerObject.playVideo();
        });


    };

    $('.video-outer-wrap').on('click', '.video-wrapper', function () {

        setupVideo.bind(this)();

    });


});

var playerObject = {
    videosPlayer: null,
    ready: false,
    onYouTubeIframeAPIReady: function () {
        this.videosPlayer = new YT.Player('yt-player', {
            width: '682',
            height: '383',
            videoId: jQuery('#yt-player').attr('data-video-id'),
            events: {
                'onReady': this.onPlayerReady,
                'onStateChange': this.onPlayerStateChange
            }
        })
    },
    onPlayerReady: function (event) {
        this.ready = true;
    },
    onPlayerStateChange: function (event) {

    },

    stopVideo: function () {
        this.videosPlayer.stopVideo();
    },
    playVideo: function () {
        if (this.ready) this.videosPlayer.playVideo();
        else setTimeout(function () {
            this.playVideo()
        }.bind(this), 1000);
    },
    setVideo: function (id) {
        this.videosPlayer.loadVideoById(id);
    },


    getVars: function () {
        return {
            videosPlayer: this.videosPlayer
        }
    }
};

function onYouTubeIframeAPIReady() {
    playerObject.onYouTubeIframeAPIReady();
}



