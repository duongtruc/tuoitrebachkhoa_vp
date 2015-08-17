<?php
/**
 * Created by PhpStorm.
 * User: Nguyen
 * Date: 7/25/14
 * Time: 12:28 AM
 */
require 'phpMailer/class.phpmailer.php';

class mailermodel
{


    public function confirmMailer($mailAdd, $lname, $activationCode)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        $mail->Host = MAILER_HOST;
        $mail->Port =MAILER_PORT;
        $mail->Username = MAILER_USER;
        $mail->Password = MAILER_PASS;
        $mail->IsHTML(true);
        $mail->SingleTo = true;
        $mail->AddReplyTo("duongtruc.92@gmail.com", "Ban quản trị hệ thống");
        $mail->CharSet = "utf-8";
        $mail->From = MAILER_FROM;
        $mail->FromName = MAILER_FROM_NAME;
        $mail->addAddress($mailAdd);
        $mail->Subject = "Kích hoạt tài khoản tại cổng thông tin tình nguyện";
        $mail->Body = "<p><h4>Chào bạn <b>".$lname."</b><br></h4></p>"
            . "<p><h3>Cảm ơn bạn đã đăng kí thành viên tại cổng thông tin tình nguyện, "
            . "để hoàn tất quá trình đăng kí, bạn vui lòng nhấp vào đường dẫn bên dưới:<h3></p></br>"
            . '<h4 style="color: #00356a">' . URL . "users/userConfirm/?email=" . $mailAdd . "&activationCode=" .$activationCode. '<h4></br>'
            . '<i>Lưu ý: </i><b><i>Nếu sau 30 ngày đăng kí mà chưa xác nhận, tài khoản của bạn sẽ tự động bị xóa khỏi hệ thống</i></b>'
            . '<h3><b>Ban quản trị</b></h3>';

        if(!$mail->Send())
            return 0;
        else
            return 1;
    }
    
    
    public function recoveryMailer($mailAdd, $Code)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        $mail->Host = MAILER_HOST;
        $mail->Port =MAILER_PORT;
        $mail->Username = MAILER_USER;
        $mail->Password = MAILER_PASS;
        $mail->IsHTML(true);
        $mail->SingleTo = true;
        $mail->AddReplyTo("duongtruc.92@gmail.com", "Ban quản trị hệ thống");
        $mail->CharSet = "utf-8";
        $mail->From = MAILER_FROM;
        $mail->FromName = MAILER_FROM_NAME;
        $mail->addAddress($mailAdd);
        $mail->Subject = "Khôi phục thông tin đăng nhập tại cổng thông tin tình nguyện";
        $mail->Body = "<p><h4>Chào bạn <b></b><br></h4></p>"
            . "<p><h3>Chúng tôi nhận được yêu cầu khôi phục mật khẩu tại cổng thông tin tình nguyện TP.HCM, "
            . "để khôi phục tài khoản đăng nhập, bạn vui lòng click vào đường dẫn bên dưới:<h3></p></br>"
            . '<h4 style="color: #00356a">' . URL . "users/passwordRecovery/?email=" . $mailAdd . "&recoveryCode=" .$Code. '<h4></br>'
            . '<p><i>Lưu ý: </i><b><i>Nếu bạn không phải là người gửi yêu cầu khôi phục tài khoản, vui lòng bỏ qua email này.</i></b></p>'
            . '<p><h3><b>Ban quản trị</b></h3></p>';;

        if(!$mail->Send())
            return 0;
        else
            return 1;


    }

    public function announceMailer($emailList,$nameList)
    {
        $length = count($emailList);
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->SMTPAuth = true;
        $mail->Host = MAILER_HOST;
        $mail->Port =MAILER_PORT;
        $mail->Username = MAILER_USER;
        $mail->Password = MAILER_PASS;
        $mail->IsHTML(true);
        $mail->SingleTo = true;
        $mail->AddReplyTo("duongtruc.92@gmail.com", "Ban quản trị hệ thống");
        $mail->CharSet = "utf-8";
        $mail->From = MAILER_FROM;
        $mail->FromName = MAILER_FROM_NAME;
        $mail->Subject = "Thông báo có báo cáo mới.";
        
        $result = '';
        for ($i = 0; $i < $length; $i++ ){
            $mail->addAddress($emailList[$i]);
            $mail->Body = "Hello ". $nameList[$i];
            if ($mail->Send())
            $result = $result. 'done';
            else
            $result = $result. $mail->ErrorInfo;
            
        }
        $file =fopen('mail.log','w');
        fwrite($file,$result);
        fclose($file);
    }
}