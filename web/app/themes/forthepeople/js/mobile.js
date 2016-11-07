( function( $ ) {
    $(document).ready(function () {
        doResize();
    });
    function doResize() {
        // dynamically change footer link font size on mobile
        var ww = $('body').width();
        var maxW = 767;
        ww = Math.min(ww, maxW);
        var fw = ww*(10/maxW);
        var fpc = fw*100/21;
        var fpc = Math.round(fpc*100)/100;
        $('.footer-links').css('font-size',fpc+'%');
    }
}) ( jQuery );