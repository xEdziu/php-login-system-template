<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password renewal</title>
</head>
<body>
    <form>
        <input type="email" name="email" placeholder="Email">
        <input type="submit" value="Reset your password">
    </form>
</body>
<script>
    let form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let email = document.querySelector('input[name="email"]').value;
        let xhr = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('email', email);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                console.log(response);
            }
        }
        xhr.open('POST', 'sendRenewalLink.php', true);
        xhr.send(formData);
    });
</script>
</html>