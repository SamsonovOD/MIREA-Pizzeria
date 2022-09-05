<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php
    define('access', TRUE);
    require_once "connect.php";
    if (!isset($_SESSION)){
        session_start();
    }
    if (!isset($_GET['id'])){
        echo "<script>";
        echo 'var page_id = window.parent.document.URL.split("/articles/article")[1].split(".html")[0];';
        echo 'location.replace(location.href+"?id="+page_id);';
        echo "</script>";
    } else {
        if ($stmt = $link->prepare("SELECT id, user, text FROM comments WHERE page = ?")){
            $stmt->bind_param('s', $_GET['id']);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $user, $text);
            if ($stmt->num_rows > 0){
                while($stmt->fetch()){
                    echo '<div style="border-style:solid;">';
                    echo '<p>'.$text."</p>";
                    echo "От: ".$user."<br></div><br>";
                }
            }
        }
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            //var_dump($_POST);
            if ($stmt = $link->prepare("INSERT INTO comments (page, user, text) VALUES (?, ?, ?)")){
                $stmt->bind_param('sss', $_GET['id'], $_SESSION['user']['name'], $_POST['comment']);
                $stmt->execute();
                header('Location: '.$_SERVER['REQUEST_URI']);
            } else {
                echo "Errror.";
            }
        }
    }
?>
<form method="post">
    <hr>
    <input type="text" name="comment" style="width: 98%; line-height: 5em;">
    <input type="hidden" id="sub" name="page" >
    <input type="submit">
</form>
<script>
    var pageid = window.parent.document.URL.split("/articles/article")[1].split(".html")[0];
    var elem = document.getElementById("sub")
    elem.setAttribute("value", pageid);
</script>
</body>
</html>