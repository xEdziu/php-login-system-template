<?php
    require_once("config.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	    <title>Account activation</title>
	    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style>
            #info{
                font-size: 1.5em;
                margin: 0;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
        </style>
    </head>
    <body>
        <div id="info">
            <?php
                if(isset($_GET['hash']) && !empty($_GET['hash'])){
                    $hash = $conn->real_escape_string($_GET['hash']); 
                    $query = $conn->prepare("SELECT hash, active FROM Users WHERE hash = ? AND active = 0");
                    $query->bind_param("s", $hash);
                    if($query->execute()){
                        $result = $query->get_result();
                        $checkNum = $result->num_rows;
                        if($checkNum == 1){
                            $query = $conn->prepare("UPDATE Users SET active = 1 WHERE hash = ?");
                            $query->bind_param("s", $hash);
                            if($query->execute()){
                                echo "Your account has been activated.";
                            } else {
                                echo "Couldn't activate your account.";
                            }
                        } else {
                            echo "Invalid activation link.";
                        }
                    } else {
                        echo "Invalid activation link.";
                    }
                } else {
                    header("Location: ../errors/404.html");
                }
            ?>
        </div>  

    </body>
</html>