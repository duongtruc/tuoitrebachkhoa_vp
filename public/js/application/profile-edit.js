/**
 * Created by DUONG_TRUC on 7/31/14.
 */
$(function () {
    var edit = $('#owner').val();
    if (edit == 0) {
        $("sup").remove();
    }
    $(".profile-detail, .profile-shortDes").hover(function () {
        $(this).children('sup').toggle('profile-edit');
    });
    $("sup").click(function () {
        $(this).parent().children(".profile-detail  .profile-content").each(function () {
            $(this).html('<input onkeypress="userUpdateKeyPress($(this).parent().attr(\'id\'))"  required="true" type="text" size = "'
                + 2 * $(this).text().length + '" autofocus="true" value = "'
                + $(this).text() + '">');
        });
        $(this).parent().children(".profile-detail  .profile-content-shortDes").each(function () {
            $(this).html('<textarea onkeypress="userUpdateKeyPress($(this).parent().attr(\'id\'))"'
                + 'required="true" rows = "'
                + 4 + '" cols = 55 autofocus="true">'
                + $(this).html().substr(1, $(this).html().length - 2)
                + '</textarea>');
        });
        $(this).parent().children(".profile-detail  .profile-content-gender").each(function () {
            var gender = $(".profile-content-gender").text();
            $(this).html('<select autofocus="1" onkeypress="userUpdateKeyPress($(this).parent().attr(\'id\'))" onchange="userUpdate($(this).parent().attr(\'id\'))">' +
                '<option value = 1>Nam</option>' +
                '<option value = -1>Nữ</option>' +
                '<option value = 0 selected>Chưa xác định</option>' +
                '</select>');
        });
        $(this).parent().children(".profile-detail  .profile-content-dob").each(function () {
            $(this).html('<input type="date"  required="true" >');
        });
        if (!$('#button').length) {
            $(".span9").append('<div id = "button">' +
                '<button class = "button primary" id="btn-update" onclick="profileUpdate()">Cập nhật hồ sơ</button>&nbsp;&nbsp;&nbsp;' +
                '<button class = "button" id = "btn-reset" onclick="reload()">Hủy</button>' +
                '</div>');
        }
    });
});
function userUpdateKeyPress(id) {
    var e = event.charCode;
    if (e == 13) {
        if ($("#" + id + " > input").length) {
            var value = $("#" + id + " > input").val();
            var returnValue = value;
        }
        else if ($("#" + id + " > textarea").length) {
            var value = '"' + $("#" + id + " > textarea").val() + '"';
            var returnValue = value;
        }
        else {
            var value = $("#" + id + " > select").val();
            var returnValue;
            if (value == 1) returnValue = 'Nam';
            else if (value == -1) returnValue = 'Nữ';
            else returnValue = 'Không xác định';
        }
        if ($("#" + id).parent().attr('id') == 'profile-address') {
            $.post("../../places/addressUpdate",
                {
                    field: id,
                    value: value
                }).done(function (data) {
                    if (data == 0)
                        alert("User upadate not successfull");
                    else
                        $("#" + id).html(returnValue);
                })
        }
        else {
            $.post("../userUpdate",
                {
                    field: id,
                    value: value
                }).done(function (data) {
                    if (data == 0)
                        alert("User upadate not successfull");
                    else
                        $("#" + id).html(returnValue);
                })
        }

    }

};
function userUpdate(id) {
    if ($("#" + id + " > input").length) {
        var value = $("#" + id + " > input").val();
        var returnValue = value;
    }
    else if ($("#" + id + " > textarea").length) {
        var value = '"' + $("#" + id + " > textarea").val() + '"';
        var returnValue = value;
    }
    else {
        var value = $("#" + id + " > select").val();
        var returnValue;
        if (value == 1) returnValue = 'Nam';
        else if (value == -1) returnValue = 'Nữ';
        else returnValue = 'Không xác định';
    }
    if ($("#" + id).parent().attr('id') == 'profile-address') {
        alert(id);
        $.post("../../places/addressUpdate",
            {
                field: id,
                value: value
            }).done(function (data) {
                if (data == 0)
                    alert("User upadate not successfull");
                else
                    $("#" + id).html(returnValue);
            })
    }
    else {
        $.post("../userUpdate",
            {
                field: id,
                value: value
            }).done(function (data) {
                if (data == 0)
                    alert("User upadate not successfull");
                else
                    $("#" + id).html(returnValue);
            })
    }
};
function reload() {
    location.reload();
};

