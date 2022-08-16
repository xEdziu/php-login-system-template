<?php 
    define('PORT_SMTP', 1111111);
    define('SERWER_SMTP', '');
    define('LOGIN_CONTAKT', '');
    define('PASSWORD_CONTAKT', '');
    define('LOGIN_NOREPLY', '');
    define('PASSWORD_NOREPLY', '');

    require_once dirname(__FILE__) . "/phpMailer/ManualLoader.php";
    use PHPMailer\PHPMailer\PHPMailer;

    class Mailer_API {

        private $mail = null;

        /**
         * PHP Mailer is a class that allows you to send emails using PHP. 
         * 
         * The below function is a constructor function. It is called when you create a new instance of
         * the class. 
         */
        function __construct() {
            $this->mail = new PHPMailer();
            $this->mail->SMTPDebug = 0;
            $this->mail->CharSet = "UTF-8";
            $this->mail->SMTPAuth = TRUE;
            $this->mail->SMTPAutoTLS = false;
            $this->mail->SMTPKeepAlive = true;
            $this->mail->Host = SERWER_SMTP;
            $this->mail->Port = PORT_SMTP;
            $this->mail->WordWrap = 50;
            $this->mail->Priority = 1;
            $this->mail->isSMTP();
        }

        /**
         * It sends an email from a contact form.
         * 
         * @param email the email address of the person who sent the message
         * @param name the name of the person who sent the message
         * @param surname the surname of the person who sent the messeage
         * @param subject the subject of the message
         * @param message the message that was sent
         * 
         * @return The response is an array with the following keys:
         * code - the code of the response
         * msg - the message of the response
         * title - the title of the response
         * icon - the icon of the response
         * btn_text - the text of the button of the response
         */
        public function sendContactMail($email, $name, $surname, $subject, $message) {
            $this->mail->Username = LOGIN_CONTAKT;
            $this->mail->Password = PASSWORD_CONTAKT;
            $this->mail->setFrom(LOGIN_CONTAKT, $name . " " . $surname);
            $this->mail->From = LOGIN_CONTAKT;
            $this->mail->FromName = $name . " " . $surname . " - " . $email;
            $this->mail->addAddress(LOGIN_CONTAKT);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject . " --- " .$name . " " . $surname . " - " . $email;
            $this->mail->Body = $message;
            $this->mail->AltBody = $message;
            if (!$this->mail->send()) {
                $response["code"] = 13; 
                $response["msg"]= $this->mail->ErrorInfo;
                $response["title"]="Message not sent";
                $response["icon"]="error";
                $response["btn_text"]="OK";
            } else {
                $response["code"] = 0;
                $response["msg"]= "Message sent";
                $response["title"]="Message sent successfully";
                $response["icon"]="success";
                $response["btn_text"]="OK";
            }
            return $response;
        }
        /**
         * It sends an email with a link to reset the password.
         * 
         * 
         * @param email the email address of the user
         * @param hash a random string of characters
         * 
         * @return The response is an array with the following keys:
         * code - the code of the response, 0 means success, other values mean error
         * msg - the message of the response
         * title - the title of the response
         * icon - the icon of the response
         * btn_text - the text of the button of the response
         */
        public function sendRenewalLink($email, $hash){
            $this->mail->Username = LOGIN_NOREPLY;
            $this->mail->Password = PASSWORD_NOREPLY;
            $this->mail->setFrom(LOGIN_NOREPLY, "from who?");
            $this->mail->From = LOGIN_NOREPLY;
            $this->mail->FromName = "from who?";
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = "Password renewal";
            $this->mail->Body = "Hello!<br>
            Click in below link to renew your password:<br>
            <a href='http://yoursite.com/reset.php?hash=".$hash."'>http://yoursite.com/reset.php?hash=".$hash."</a><br>
            If you don't want to change your password, ignore this email.<br>";
            $this->mail->AltBody = "Hello!\n
            Click in below link to renew your password:\n
            http://yoursite.com/reset.php?hash=".$hash."\n
            If you don't want to change your password, ignore this email.";
            if (!$this->mail->send()) {
                $response["code"] = 20;
                $response["msg"] = $this->mail->ErrorInfo;
                $response["title"] = "An error occurred while sending the email";
                $response["icon"] = "error";
                $response["btn_text"] = "OK";
            } else {
                $response["code"] = 0;
                $response["msg"] = "Password renewal link was sent to given email.";
                $response["title"] = "All done!";
                $response["icon"] = "success";
                $response["btn_text"] = "OK";

            }
            return $response;
        }

        /**
         * It sends an email to the user with a link to activate his account
         * 
         * @param email the email address of the user
         * @param hash  the hash of the user's email address
         * 
         * @return Array with the following keys:
         * code: 0 if the email was sent successfully, 13 if there was an error.
         * msg: The message to be displayed to the user.
         * title: The title of the message to be displayed to the user.
         * icon: The icon to be displayed to the user.
         * btn_text: The text of the button to be displayed to the user
         */
        public function sendActivationMail($email, $hash){
            $this->mail->Username = LOGIN_NOREPLY;
            $this->mail->Password = PASSWORD_NOREPLY;
            $this->mail->setFrom(LOGIN_NOREPLY, "from who?");
            $this->mail->From = LOGIN_NOREPLY;
            $this->mail->FromName = "from who?";
            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = "Account activation";
            $this->mail->Body = "Hello!<br>
                    In order to activate your account, click button below<br>
                    <a href='http://yoursite.com/activate.php?hash=$hash'>Activate account</a>";
            $this->mail->AltBody = "Hello!\n
                    In order to activate your account, click link below:\n
                    http://yoursite.com/activate.php?hash=$hash";
            if (!$this->mail->send()) {
                $response["code"] = 13; 
                $response["msg"]= $this->mail->ErrorInfo;
                $response["title"]="An error occurred while sending the email";
                $response["icon"]="error";
                $response["btn_text"]="OK";
            } else {
                $response = ["code"=> 0,
                "msg"=>"Registration complete. Check your email to activate your account.",
                "title"=>"All done!",
                "icon"=>"success",
                "btn_text"=>"OK"];
            }
            return $response;
        }

    }

?>