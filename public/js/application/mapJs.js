/**
 * Created by Nguyen on 7/19/14.
 */
var map;
var ActivitiesMarkers = [];
var PlaceMarkers = [];
function initialize() {
    var options = {
        center: new google.maps.LatLng(10.81779956817627, 106.6179962158203),
        zoom: 13,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.LARGE
        }
    };
    map = new google.maps.Map(document.getElementById("map"),
        options);

    return map;
}
google.maps.event.addDomListener(window, 'load', initialize());
google.maps.event.addDomListener(window, 'load', getActivityByType(0));
google.maps.event.addDomListener(window, 'load', getLocation(0));

function getLocation(id) {
    $.post('map/getLocationById/',
        {
            id: id
        }).done(function (data) {
            data = JSON.parse(data);
            var n = data.length;
            var infoContents = [];
            for (var i = 0; i < n; i += 1) {
                var marker =
                    new google.maps.Marker(
                        {
                            position: new google.maps.LatLng(data[i].lat, data[i].long),
                            map: map,
                            icon: 'http://tuoitrebachkhoa.edu.vn/images/markers/location.png',
                            title: data[i].lat + ' | ' + data[i].long
                        }
                    );
                PlaceMarkers.push(marker);
                infoContents[i] = '<a href = "places/detail/' + data[i].id + '\?tab=images"><img class = "infoImages" src="http://localhost/MapPro/public/img/' + data[i].imageUrl + '"' + '></a><div class = "infoWindow"><span class = "title">' + data[i].name + '</span></br>'
                    + '<i class= "icon-location infoIcon"></i>  ' + data[i].no + ' ' + data[i].street + ', phường ' + data[i].ward + ', Quận '
                    + data[i].dist + ', ' + data[i].city + '</br>'
                    + '<i class= "icon-phone infoIcon"></i>  ' + data[i].phone + '</br>'
                    + '<i class= "icon-mail infoIcon"></i>  ' + data[i].email + '</br>'
                    + '<i class= "icon-home infoIcon"></i>  <a href="http://' + data[i].website + '"' + '>' + data[i].website + '</a></br>'
                    + '<b>Đánh giá:</b> ' + '</br>'
                    + '<div style="text-align: justify"><a href = "places/detail/' + data[i].id + '" style = "text-align: left">Xem thêm&nbsp;&nbsp;|&nbsp;&nbsp;</a><a href = "places/detail/' + data[i].id + '\?tab=articles">Bài viết liên quan&nbsp;&nbsp;|&nbsp;&nbsp;</a>' + '<a href = "places/detail/' + data[i].id + '\?tab=activities">Lịch sử hoạt động&nbsp;&nbsp;|&nbsp;&nbsp;</a></div></div>';
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infoWindow.setContent(infoContents[i]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));
            }
        })
}
function getActivityByType(type) {
    $.post('activities/getActivityShortDesByType/',
        {
            type: type
        }).done(function (data) {
            data = JSON.parse(data)
            var n = data.length;
            var infoContents = [];
            for (var i = 0; i < n; i += 1) {
                var marker =
                    new google.maps.Marker(
                        {
                            position: new google.maps.LatLng(data[i].lat, data[i].long),
                            map: map,
                            icon: 'http://tuoitrebachkhoa.edu.vn/images/markers/event.png',
                            title: data[i].lat + ' | ' + data[i].long
                        }
                    );
                ActivitiesMarkers.push(marker);
                infoContents[i] = '<div class = "infoImages"><a href = "#"><img class = "infoImages" src="http://placehold.it/100x100"></a></div><div class = "infoWindow""><span class = "title">' + data[i].activityName + '</span></br>'
                    + '<b>Ngày bắt đầu:&nbsp;</b>' + data[i].startday + '</br>' + '<b>Ngày kết thúc:&nbsp;&nbsp; </b> ' + data[i].endday + '</br>'
                    + '<b>Đơn vị tổ chức:&nbsp; </b> ' + '<a href = "places/detail/' + data[i].placeId + '">'
                    + data[i].placeName + '</a></br>'
                    + '<b>Đánh giá:&nbsp;</b>' + '</br>'
                    + '<a href = "activities/detail/' + data[i].activityId + '">Chi tiết...</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href = "#" style="align-self: flex-end">Bài viết liên quan</a></div>';
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infoWindow.setContent(infoContents[i]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));
            }
        })
}
function clearMarker(markers) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
}

$(".activity li label  input:checkbox").each(function () {
    $(this).change(function () {
        if ($(this).is(":checked")) {
            getActivityByType($(this).attr("id").substring(2));
        } else if (!($(this).is(":checked"))) {
            clearMarker(ActivitiesMarkers);
            for (var i = 1; i <= 7; i++) {
                if ($("#at" + i).is(":checked")) {
                    getActivityByType(i);
                }
            }
        }
    })
});
/**
 *
 */
$(".place li label  input:checkbox").each(function () {
    $(this).change(function () {
        if ($(this).is(":checked")) {
            getLocation($(this).attr("id").substring(2));
        } else if (!($(this).is(":checked"))) {
            clearMarker(PlaceMarkers);
            for (var i = 1; i <= 7; i++) {
                if ($("#pl" + i).is(":checked")) {
                    getLocation(i);
                }
            }
        }
    })
});
/**
 *
 */
$("#pl0").change(function () {
    if ($(this).is(":checked")) {
        clearMarker(PlaceMarkers);
        for (var i = 1; i <= 7; i++) {
            $("#pl" + i).attr("disabled", true);
        }
        getLocation(0);
    }
    else {
        clearMarker(PlaceMarkers);
        for (var i = 1; i <= 7; i++) {
            if ($("#pl" + i).is(":checked")) {
                getLocation(i);
            }
            $("#pl" + i).removeAttr("disabled");
        }
    }
})
/**
 *
 */
$("#at0").change(function () {
    if ($(this).is(":checked")) {
        clearMarker(ActivitiesMarkers);
        for (var i = 1; i <= 7; i++) {
            $("#at" + i).attr("disabled", true);
        }
        getActivityByType(0);
    }
    else {
        clearMarker(ActivitiesMarkers);
        for (var i = 1; i <= 7; i++) {
            if ($("#at" + i).is(":checked")) {
                getActivityByType(i);
            }
            $("#at" + i).removeAttr("disabled");
        }
    }
})
