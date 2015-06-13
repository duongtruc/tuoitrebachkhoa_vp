/**
 * Created by Nguyen on 7/23/14.
 */
$(function () {
    $("#submit").on('click', function () {
        $("#loadImage").html('<img src="../../public/img/loading2.gif">');
        $.post("../userRegister/",
            {
                fname: $("#fname").val(),
                lname: $("#lname").val(),
                email: $("#email").val(),
                password: $("#password").val()
            }, function (data, status) {
                if (status == "success" && data == "1") {
                    $("#content").html('<h3>Đăng ký thành công với email: <b><span style="color: #0000ff"> '
                        + $("#email").val() + '</span></b>, vui lòng mở mail để kích hoạt</h3>'
                        + ' Nhấn <a href = "../../">vào đây</a> để về trang chủ</h3>');
                }
                    else {
                        $("#content").html('<h3>Đăng ký không thành công, email: '
                            + $("#email").val() + ' đã được đăng kí</h3>'
                            + '. Nhấn <a href = "../passwordRecovery/">vào đây</a> khôi phục mật khẩu</h3>');
                }

            }, "text");
    });
})
/**
 *
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
