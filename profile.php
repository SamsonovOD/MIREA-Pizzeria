 <?php
    $_REQUEST["load_main"] == "yes" || die('Direct access not permitted.');
    define('access', TRUE);
    require_once "connect.php";
    if (!isset($_SESSION)){
        session_start();
    }
    if ($stmt = $link->prepare("SELECT id, username, password, email, level FROM users WHERE username = ?")){
        $stmt->bind_param('s', $_SESSION['user']['name']);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0){
            $stmt->bind_result($id, $username, $password, $email, $level);
            $stmt->fetch();
            echo $id.'<br>'.$username.'<br>'.$password.'<br>'.$email.'<br>'.$level;
        }
    }    
?>