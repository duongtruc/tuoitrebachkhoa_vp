/**
 * Created by DUONG_TRUC on 8/4/14.
 */
function userFollow(userId, friendId) {
    if (userId == 0)
    {
        $.Notify({style: {background: 'red', color: 'white'}, content: "Bạn phải đăng nhập để thực hiện chức năng này!"});
    }
    else {
        $.post('../userFollow',
            {
                'userId': userId,
                'friendId': friendId
            }).done(function(data) {
                if (data == 0) {
                    $.Notify({style: {background: 'red', color: 'white'}, content: "Bạn đã theo dõi người này"});
                }
                else {
                    $.Notify({style: {background: 'green', color: 'white'}, content: "Đăng ký theo dõi thành công, bạn sẽ nhận thông báo về hoạt động của người này!"});
                }
            });
    }
}
