<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
    <?php
        $_REQUEST["load_main"] == "yes" || die('Direct access not permitted.');
        define('access', TRUE);
        require_once "connect.php";
        if (!isset($_SESSION)){
            session_start();
            session_regenerate_id();
        }
        //echo '<pre>'.var_dump($_SESSION).'</pre>';
        if (empty($_SESSION['user'])){
            $_SESSION['user']['loggedin'] = False;
            $_SESSION['user']['name'] = "Guest";
            $_SESSION['user']['level'] = "Guest";
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if ($stmt = $link->prepare("SELECT id, username, password, level FROM users WHERE username = ?")){
                $stmt->bind_param('s', $_POST['loguser']);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0){
                    $stmt->bind_result($id, $username, $password, $level);
                    $stmt->fetch();
                    if (password_verify($_POST['logpass'], $password)){
                        $_SESSION['user']['loggedin'] = TRUE;
                        $_SESSION['user']['name'] = $username;
                        $_SESSION['user']['level'] = $level;
                        $_SESSION['user']['id'] = $id;
                    } else {
                        echo ('Неверный пароль.');
                    }
                } else {
                    echo ('Пользователь не найден.');
                }
            }
        }
        mysqli_close($link);
        if ($_SESSION['user']['name'] == "Guest"){
            echo ('<form method="post">');
            echo ('Логин: <input type="text" size="20" placeholder="Введите логин" name="loguser" required><br>');
            echo ('Пароль: <input type="password" size="10" class="passform" placeholder="Введите пароль" name="logpass" required>');
            echo ('<input type="checkbox" onclick="showPass()">Показать<br>');
            echo ('<input type="submit" value="Войти"><input type="checkbox"> Запомнить меня<br>');
            echo ('</form>');
            echo ('<button id="modalButton" onclick="openModal()">Регистрация</button> <a href="forgot.php">Забыли пароль?</a> ');
        } else {
            echo '<div id="Welcome">';
            echo ('Здравствуйте '.$_SESSION['user']['name'].' с уровнем '.$_SESSION['user']['level'].'!');
            echo '</div>';
            echo '<br><button onclick=profile() value="Профиль">Профиль</button>';
            echo '<br><form action="logout.php"><button value="Выйти">Выйти</button></form>';
        }
    ?>
    <div id="AjaxCall"></div>
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
        function openModal(){
            var modal = window.parent.document.getElementById("modalContainer");
            modal.style.display = "block";
        }
        function profile(){
            var modal = window.parent.document.getElementById("modalProfile");
            modal.style.display = "block";
        }
    </script>
</body>
</html>