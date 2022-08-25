<?php 
    require_once("config.php");
    require_once dirname(__FILE__) . "/mail/Mailer_API.php";
    session_start();
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
        
        $email = $conn->real_escape_string(htmlspecialchars($_POST['email']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["code"] = 17;
            $response["msg"] = "Given address is not an email.";
            $response["title"] = "Error";
            $response["icon"] = "warning";
            $response["btn_text"] = "OK";
            ob_end_clean();
            echo json_encode($response);
            die();
        }

        $query = $conn->prepare("SELECT email FROM Users WHERE email=?");
        $query->bind_param('s', $email);

        if ($query->execute()) {
            $result = $query->get_result();

            if ($result->num_rows != 1) {
                $response["code"] = 18;
                $response["msg"] = "Given email is not in registered.";
                $response["title"] = "Error";
                $response["icon"] = "warning";
                $response["btn_text"] = "OK";
            } else {
                $hash = genUUID();
                $query = $conn->prepare("UPDATE Users SET hash=? WHERE email=?");
                $query->bind_param('ss', $hash, $email);

                if ($query->execute()) {
                    $query->close();

                    $mail = new Mailer_API();
                    $response = $mail->sendRenewalLink($email, $hash);       
                }
            }

        } else {
            $response["code"] = 19;
            $response["msg"] = "Error while connecting to database.";
            $response["title"] = "Error\nContact with admin. Error code: 19";
            $response["icon"] = "warning";
            $response["btn_text"] = "OK";
        }
        
        ob_end_clean();
        echo json_encode($response);
        die();

    } else {
        header("Location: errors/404.html");
    }
?>