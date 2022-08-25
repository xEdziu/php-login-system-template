<?php 
    require_once("config.php");
    session_start();
    session_unset();
    session_destroy();
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hash']) && isset($_POST['password'])) {
        
        $hash = $conn->real_escape_string(htmlspecialchars($_POST['hash']));
        $password = $conn->real_escape_string(htmlspecialchars($_POST['password']));
        
        if (strlen($password) < 8) {
            $response["code"] = 21;
            $response["msg"] = "Password need to be at least 8 characters long.";
            $response["title"] = "Błąd";
            $response["icon"] = "warning";
            $response["btn_text"] = "OK";
            ob_end_clean();
            echo json_encode($response);
            die();
        }

        $query = $conn->prepare("SELECT hash FROM Users WHERE hash=?");
        $query->bind_param('s', $hash);
        
        if ($query->execute()) {
            $result = $query->get_result();
            
            if ($result->num_rows != 1) {
                $response["code"] = 23;
                $response["msg"] = "Invalid data.";
                $response["title"] = "Error";
                $response["icon"] = "warning";
                $response["btn_text"] = "OK";
            } else {

                $hashedPassword = password_hash($password, PASSWORD_ARGON2ID);

                if (password_verify($password, $hashedPassword)) {

                    $query2 = $conn->prepare("UPDATE Users SET pwd=? WHERE hash=?");
                    $query2->bind_param('ss', $hashedPassword, $hash);

                    if ($query2->execute()) {
                        $query2->close();
                        $response["code"] = 0;
                        $response["msg"] = "Password has been changed.";
                        $response["title"] = "Done!";
                        $response["icon"] = "success";
                        $response["btn_text"] = "OK";
    
                    } else {
    
                        $response["code"] = 22;
                        $response["msg"] = "Error occured. Error code: 22";
                        $response["title"] = "Error";
                        $response["icon"] = "warning";
                        $response["btn_text"] = "OK";
    
                    }

                } else {
                    $response["code"] = 24;
                    $response["msg"] = "Error occured. Error code: 24";
                    $response["title"] = "Error";
                    $response["icon"] = "warning";
                    $response["btn_text"] = "OK";
                }
            }
        }

        ob_end_clean();
        echo json_encode($response);
        die();

    } else {
        header("Location: errors/404.html");
    }
?>