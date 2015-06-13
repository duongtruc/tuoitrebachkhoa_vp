/**
 * Created by DUONG_TRUC on 8/3/14.
 */
function takePart(userId, activityId) {
    if (userId == 0)
    {
        $.Notify({style: {background: 'red', color: 'white'}, content: "Bạn phải đăng nhập để thực hiện chức năng này!"});
    }
    else {
        $.post('../takePart',
            {
                'userId': userId,
                'activityId': activityId
            }).done(function(data) {
                if (data == 0) {
                    $.Notify({style: {background: 'red', color: 'white'}, content: "Bạn đã đăng ký hoạt động này"});
                }
                else {
                    $.Notify({style: {background: 'green', color: 'white'}, content: "Đăng ký hoạt động thành công!"});
                }
            });
    }
}
