/**
 * Created by Nguyen on 7/19/14.
 */
var map;
function initialize() {
    var options = {
        center: new google.maps.LatLng(10.81779956817627, 106.6179962158203),
        zoom: 13,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        }
    };
    map = new google.maps.Map(document.getElementById("map"),
        options);

    return map;
}
google.maps.event.addDomListener(window, 'load', initialize());

function getLocation(id) {
    if (window.XMLHttpRequest) {
        ajax = new XMLHttpRequest();
    }
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var text = ajax.responseText;
            var obj = JSON.parse(text);
            var n = Object.keys(obj).length;
            var textContent = "";
            //var markers = [];
            var infoContents = [];
            //var infoWindows = [];
            for (var i = 0; i < n; i += 1) {
                var marker =
                    new google.maps.Marker(
                        {
                            position: new google.maps.LatLng(obj[i].lat, obj[i].long),
                            map: map,
                            icon: 'http://tuoitrebachkhoa.edu.vn/images/markers/place.png',
                            title: obj[i].lat + ' | ' + obj[i].long
                        }
                    );
                infoContents[i] = '<div style="width: 200px"><b>Cơ sở:</b> ' + obj[i].name + '</br>'
                    + '<b>Địa chỉ:</b> số ' + obj[i].no + ' ' + obj[i].street + ', phường ' + obj[i].ward + ', Quận '
                    + obj[i].dist + ', ' + obj[i].city + '</br>'
                    + '<b>Điện thoại:</b> ' + obj[i].phone + '</br>'
                    + '<b>Email:</b> ' + obj[i].email + '</br>'
                    + '<a href = "#" style="text-align: left">Xem thêm...</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href = "#" style="align-self: flex-end">Bài viết liên quan</a></div>';
                var infoWindow = new google.maps.InfoWindow(), marker, i;
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infoWindow.setContent(infoContents[i]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));
                textContent = textContent + (obj[i].lat + " - " + obj[i].long + "</br>");
            }
            document.getElementById('locationList').innerHTML = textContent;
        }
    }
    ajax.open("POST", "map/getLocationById/", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("id=" + id);
}
function getActivityByType(type) {
    if (window.XMLHttpRequest) {
        ajax = new XMLHttpRequest();
    }
    ajax.onreadystatechange = function () {
        if (ajax.readyState == 4 && ajax.status == 200) {
            var text = ajax.responseText;
            if (text != '0') {
                var obj = JSON.parse(text)
                var n = Object.keys(obj).length;
                var markers = [];
                var textContent = "";
                var infoContents = [];
                for (var i = 0; i < n; i += 1) {
                    var marker =
                        new google.maps.Marker(
                            {
                                position: new google.maps.LatLng(obj[i].lat, obj[i].long),
                                map: map,
                                icon: 'http://tuoitrebachkhoa.edu.vn/images/markers/activity.png',
                                title: obj[i].lat + ' | ' + obj[i].long
                            }
                        );
                    markers.push(marker);
                    infoContents[i] = '<div style="width: 300px"><b>Hoạt động:</b>' + obj[i].activityName + '</br>'
                        + '<b>Ngày bắt đầu:</b>' + obj[i].startday + '</br>' + '<b>Ngày kết thúc: </b> ' + obj[i].endday + '</br>'
                        + '<b>Đơn vị tổ chức: </b> ' + '<a href = "vplaces/detail/' + obj[i].placeId + '">'
                        + obj[i].placeName + '</a></br>'
                        + '<a href = "activities/detail/' + obj[i].activityId + '">Chi tiết...</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href = "#" style="align-self: flex-end">Bài viết liên quan</a></div>';
                    var infoWindow = new google.maps.InfoWindow(), marker, i;
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            infoWindow.setContent(infoContents[i]);
                            infoWindow.open(map, marker);
                        }
                    })(marker, i));
                    textContent = textContent + (obj[i].lat + " - " + obj[i].long + "</br>");
                }
            }
        }
    }
    ajax.open("POST", "activities/getActivityShortDesByType/", true);
    ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax.send("type=" + type);
}
