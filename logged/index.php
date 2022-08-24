<?php 
        session_start();
        if (!isset($_SESSION['logged']) || !$_SESSION['logged']){
            session_destroy();
            header("Location: ../loginForm.html");
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged</title>
</head>
<body>
    <?php 
        echo "Hello, " . $_SESSION['mail'];
    ?>
</body>
</html>