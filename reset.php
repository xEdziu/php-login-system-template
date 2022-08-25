<?php 
    require_once("config.php");
    session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change your password</title>
</head>
<body>
    <div>
        <?php
            if(isset($_GET['hash']) && !empty($_GET['hash'])){
                $hash = $conn->real_escape_string($_GET['hash']); 
                $query = $conn->prepare("SELECT hash FROM Users WHERE hash = ?");
                $query->bind_param("s", $hash);
                if($query->execute()){
                    $result = $query->get_result();
                    $checkNum = $result->num_rows;
                    if($checkNum == 1){
                        echo '<form method="post">
                                <input type="password" name="password" placeholder="New password">
                                <input type="password" name="password2" placeholder="Repeat new password">
                                <input type="submit" value="Change your password">
                            </form>';
                    } else {
                        echo "Invalid url.";
                    }
                } else {
                    echo "Database error.";
                }
            } else {
                header("Location: errors/404.html");
            }
        ?>
    </div>
</body>
<script>
    let form = document.querySelector("form");
    form.addEventListener("submit", function(e){
        e.preventDefault();
        let password = document.querySelector("input[name='password']").value;
        let password2 = document.querySelector("input[name='password2']").value;
        if(password != password2){
            document.write("Password are not identical.");
        } else if (password.length < 8) {
            document.write("Password has to have at least 8 characters.");
        } else {
            let formData = new FormData();
            formData.append("password", password);
            formData.append("hash", "<?php echo $hash; ?>");
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(xhr.readyState == 4 && xhr.status == 200){
                    let response = JSON.parse(xhr.responseText);
                    if (response.code === 0) {
                        window.location.href = "loginForm.html";
                    } else {
                        console.log(response);
                    }
                }
            }
            xhr.open("POST", "resetPassword.php", true);
            xhr.send(formData);
        }
    });
</script>
</html>