<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <style>
    table, th, td {
        border: 1px solid black;
    }
    </style>
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
        if ($stmt = $link->prepare("SELECT id, user, cook, status FROM orders ORDER BY status ASC")){
            $stmt->execute();
            $stmt->bind_result($id, $user, $cook, $status);
            $stmt->store_result();
            if ($stmt->num_rows > 0){
                echo '<table><tr><th>Номер</th><th>Пользователь</th><th>Повар</th><th>Статус</th></tr>';
                while($stmt->fetch()){
                    echo '<tr><td>'.$id.'</td><td>'.$user.'</td><td>'.$cook.'</td><td>'.$status.'</td></td>';
                }
                echo '</table>';
            }
        } else {echo "BAD";}
    ?>
    </body>
</html>