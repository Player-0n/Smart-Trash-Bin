<?php

    // PHP Mailer Configurations
    define("MAILER_HOST", "smtp.gmail.com");
    define("MAILER_NAME", "The Archons Support Team");
    define("MAILER_USERNAME", "stephmarvin30@gmail.com");
    define("MAILER_PASSWORD", "eizcjtfilmlwszny");
    define("MAILER_PORT", 587);

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "PHPMailer/src/Exception.php";
    require "PHPMailer/src/PHPMailer.php";
    require "PHPMailer/src/SMTP.php";

    function send_reset_password_link($email, $full_name, $link) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = MAILER_HOST;

            $mail->Username = MAILER_USERNAME;
            $mail->Password = MAILER_PASSWORD;
            $mail->SMTPSecure = "tls";
            $mail->Port = MAILER_PORT;

            $mail->setFrom(MAILER_USERNAME, MAILER_NAME);
            $mail->addAddress($email, $full_name);

            $mail->isHTML(true);
            $mail->Subject = "STB: Reset Password";
            $mail->Body = "
                Greetings, <b>$full_name</b>! <br><br>

                We received a request to reset the <b>password</b> of your account. Please use the reset password link below to reset your password. <br><br>

                Your reset password link is: <a href='$link' target='_blank'>Click here to reset password</a>
                
                <br><br>

                Steps in resetting your password: <br>
                1. Enter your <b>New Password</b> and <b>Confirm New Password</b>. <br>
                2. Click <b>Reset Password</b>. <br><br>

                Note: <br>
                1. This password reset link is valid only for the next <b>5 minutes</b>. <br>
                2. If you didn't request this, please ignore this email or contact our support team immediately. <br><br>

                Regards, <br>
                <b>STB Support Team</b>
            ";

            $mail->send();

            return ["success" => true, "message" => "Email sent successfully."];
        } 
        
        catch (Exception $e) {
            error_log("Error: " . $mail->ErrorInfo);
            return ["success" => false, "message" => "Error in sending email! Please try again."];
        }
    }
?>