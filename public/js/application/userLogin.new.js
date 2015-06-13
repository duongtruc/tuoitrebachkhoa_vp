/**
 * Created by Nguyen on 7/22/14.
 */
/**
 * Hien Login Form
 */

$(function () {
    $("#userLoginButton").on('click', function () {
        $.Dialog({
            overlay: true,
            shadow: true,
            flat: true,
            draggable: true,
            icon: '<span class = "icon-key-2">',
            title: 'Flat window',
            content: '',
            padding: 10,
            onShow: function (_dialog) {
                var content = '<form class="user-input" action = "http://192.168.0.113/MapPro/users/userLogin/" method="POST">' +
                    '<label for = "email">Email</label>' +
                    '<div class="input-control text"><input type="text" name="email" tabindex="1" autofocus="1"><button class="btn-clear"></button></div>' +
                    '<label for = "password">Mật khẩu</label>' +
                    '<div class="input-control password"><input type="password" name="password" tabindex="2"><button class="btn-reveal"></button></div>' +
                    //TODO absolute path?
                    '<div class="input-control checkbox"><label><input type="checkbox" name="c1" checked/><span class="check"></span>Ghi nhớ</label><span style="text-decoration: underline"><a href="http://localhost/MapPro/users/passwordRecovery/">Quên mật khẩu</a></span></div>' +
                    '<div class="form-actions">' +
                    '<button class="button primary" tabindex="3">Đăng nhập</button>&nbsp;&nbsp;' +
                    '<button class="button" type="button" onclick="$.Dialog.close()">Cancel</button>&nbsp;&nbsp;'+
                    '<a href="http://192.168.0.113/MapPro/users/register/" class="button success">Đăng kí</a>&nbsp;' +
                    '</div>' +
                    '</form>';

                $.Dialog.title("Đăng nhập");
                $.Dialog.content(content);
            }
        });
    });
});


/**
 * Register validator function
 * @param pass
 */
function checkPassword(pass) {
    if (pass.length < 6) {
        document.getElementById('pwStatus').innerHTML = "<p style='color: #ff0000'><b>" + "Mật khẩu phải lớn hơn 6 kí tự" + "</b></p>";
    }
    else {
        document.getElementById('pwStatus').innerHTML = "<p style='color: green'><b>" + "OK" + "</b></p>";
    }
}

function checkPasswordSame(pass, passCopy) {
    if (pass == passCopy) {
        document.getElementById('pwStatus').innerHTML = "<p style='color: green'><b>" + "Mật khẩu trùng khớp" + "</b></p>";
    }
    else {
        document.getElementById('pwStatus').innerHTML = "<p style='color: #ff0000'><b>" + "Mật khẩu chưa trùng khớp" + "</b></p>";
    }
}
