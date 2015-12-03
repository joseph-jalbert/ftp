var map;
jQuery(document).ready(function () {
    map = new GMaps({
        div: '#map',
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        scrollwheel: false,
        zoom: 5,
        lat: 34.411218,
        lng: -78.837891
    });


});

function setMarkerWindowPOS(e) {
    map.setZoom(10);
    map.panTo(e.position);
    map.panBy(200, 0);
};

function findById(source, id) {
    return source.filter(function (obj) {
        return +obj.id === +id;
    })[0];
}

function mapOffice(officeID) {
    var markerID = findById(map.markers, officeID);
    google.maps.event.trigger(map.markers[markerID.index], "click");
    jQuery('html, body').animate({
        scrollTop: 0
    }, 1000);
};