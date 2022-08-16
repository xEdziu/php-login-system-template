<?php 
    require_once("config.php");
    require_once dirname(__FILE__) . "/mail/Mailer_API.php";

    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === "POST") {

        $name = $conn->real_escape_string(htmlspecialchars($_POST['username']));
        $email = $conn->real_escape_string(htmlspecialchars($_POST['email']));
        $password = $conn->real_escape_string(htmlspecialchars($_POST['password']));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response["code"] = 1;
            $response["msg"] = "Invalid email.";
            $response["title"] = "Error!";
            $response["icon"] = "error";
            $response["btn_text"] = "OK";
            ob_end_clean();
            echo json_encode($response);
            die();
        }

        $result_e = $conn->query("SELECT email FROM Users WHERE email='$email'");
        $row_cnt_e = $result_e->num_rows;

        if ($row_cnt_e > 0) {
            $response["code"] = 2;
            $response["msg"] = "There is already account associated with that email.";
            $response["title"] = "Error!";
            $response["icon"] = "error";
            $response["btn_text"] = "OK";
            ob_end_clean();
            echo json_encode($response);
            die();
        }

        $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);
        $hash = genUUID();

        if (password_verify($password, $hashedPassword)) {

            $queryReg = $conn->prepare("INSERT INTO Users (email, pwd, hash) VALUES (?,?,?)");
            $queryReg->bind_param('sss', $email, $hashedPassword, $hash);

            if ($queryReg->execute()) {

                $queryReg->close();
                $mail = new Mailer_API();
                $response = $mail->sendActivationMail($regEmail, $hashActivate);

                $conn->close();
            } else {
                $response["code"] = 4;
                $response["msg"] = "Error creating account.";
                $response["title"] = "Error!";
                $response["icon"] = "error";
                $response["btn_text"] = "OK";
                ob_end_clean();
                echo json_encode($response);
                die();
            }
        } else {
            $response["code"] = 5;
            $response["msg"] = "Error creating account.";
            $response["title"] = "Error!";
            $response["icon"] = "error";
            $response["btn_text"] = "OK";
            ob_end_clean();
            echo json_encode($response);
            die();
        }

        ob_end_clean();
        echo json_encode($response);
        die();

    } else {
        header("Location: ../errors/404.html");
    }
?>