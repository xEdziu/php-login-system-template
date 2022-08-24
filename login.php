<?php

    require_once("config.php");
    session_start();
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $response = ["code"=> 0,
        "msg"=>"Logged in",
        "title"=>"Done!",
        "icon"=>"success",
        "btn_text"=>"OK"];

        $email = $conn->real_escape_string($_POST['email']);
        $pwd = $conn->real_escape_string($_POST['pwd']);

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

        $check = $conn->prepare("SELECT * FROM Users WHERE email=?");    
        $check->bind_param('s', $email);
        
        if ($check->execute()){
            $result = $check->get_result();
            $checkNum = $result->num_rows;
            if ($checkNum != 1){
                if ($checkNum == 0)
                    $response["code"] = 4;
                else
                    $response["code"] = 5;
                $response["msg"] = "Wrong credentials!";
                $response["title"] = "Something went wrong.";
                $response["icon"] = "warning";
                $response["btn_text"] = "OK";
            } else {
    
                $output = $result->fetch_assoc(); // fetch data   

                if(password_verify($pwd, $output['pwd'])) {

                    $_SESSION['logged'] = true;
                    $_SESSION['mail'] = $email;
                    $response['href'] = "../logged/index.php";

                } else {
                    $response["code"] = 7; 
                    $response["msg"]= "Couldn't log in.";
                    $response["title"]="Invalid data.";
                    $response["icon"]="error";
                    $response["btn_text"]="OK";
                }
            }

        } else {
            $response["code"] = 6; 
            $response["msg"]= "Couldn't log in.";
            $response["title"]="Contact with admin.\nError code: 6";
            $response["icon"]="error";
            $response["btn_text"]="OK";
        }

        ob_end_clean();
        echo json_encode($response);
        die();

    } else {
        header("Location: ../errors/404.html");
    }
?>