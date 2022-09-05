<html>
<!DOCTYPE html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
    <?php
    $_REQUEST["load_main"] == "yes" || die('Direct access not permitted.');
    define('access', TRUE);
    require_once "connect.php";
    $username = $password = $confirm_password = "";
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if($_POST["regpass"] == $_POST["regconf"]){
            if ($stmt = $link->prepare("SELECT id FROM userss WHERE username = ?")){
                $stmt->bind_param("s", $_POST["reguser"]);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows == 0){
                    if ($stmt = $link->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)")){
                        $hash = password_hash($_POST["regpass"], PASSWORD_DEFAULT);
                        $stmt->bind_param("sss", $_POST["reguser"], $hash, $_POST["email"]);
                        $stmt->execute();
                        echo '<style type="text/css">#form {display: none;}</style>';
                        echo("Пользователь ".$_POST["reguser"]." создан.");
                    }
                } else {
                    echo('<script>alert("Это имя уже используется.")</script>');
                }
                mysqli_stmt_close($stmt);
            } else {
                echo $stmt->error;
                //echo('<script>alert("Ошибка соединения.")</script>'); 
            }
        } else {
            echo('<script>alert("Пароли не совпадают!")</script>'); 
        }
    }
    ?>
    <form id="form" method="post">
            <label>Логин</label>
            <input type="text" name="reguser"><br>
            <label>Почта</label>
            <input type="text" name="email"><br>
            <label>Пароль</label>
            <input type="password" class="passform" name="regpass"><input type="checkbox" onclick="showPass()">Показать пароль<br>
            <label>Подтвердить пароль</label>
            <input type="password" class="passform" name="regconf"><br>
            <input type="submit" value="Подтвердить">
            <input type="reset" value="Сбросить">
    </form>
    <script>
        function showPass(){
        var x = document.getElementsByClassName("passform");
        if (x[0].type === "password") {
            x[0].type = "text";
        } else {
            x[0].type = "password";
        }
        if (x[1].type === "password") {
            x[1].type = "text";
        } else {
            x[1].type = "password";
        }
    }
    </script>
</body>
</html>