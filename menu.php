<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <style>
        p {
            margin: 0;
            margin-left: 5px;
        }
        .shopitem {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.5);
            vertical-align: top;
            border-style: solid;
            width: 150px;
            height: 350px;
        }
    </style>
</head>
<body style="margin: 2%;">
    <?php
    $_REQUEST["load_main"] == "yes" || die('Direct access not permitted.');
    define('access', TRUE);
    require_once "connect.php";
    if (!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    };    
    if ($stmt = $link->prepare("SELECT * FROM `menu` ORDER BY (CASE type WHEN 'seasonal' THEN 1 WHEN 'basic' THEN 2 WHEN 'garnir' THEN 3 WHEN 'drink' THEN 4 END) ASC")){
        $stmt->execute();
        $stmt->bind_result($id, $name, $price, $url, $type, $ingredients);
        $stmt->store_result();
        if ($stmt->num_rows > 0){
            while($stmt->fetch()){
                echo '<div class="shopitem" id="item_'.$id.'">'.chr(0x0D).chr(0x0A);
                echo '<img src='.$url." width=150>".chr(0x0D).chr(0x0A);
                echo '<p style="font-weight: bold;">'.$name.'</p>'.chr(0x0D).chr(0x0A);
                echo '<p style="font-style: italic; font-size: small;">('.$ingredients.')</p>'.chr(0x0D).chr(0x0A);
                echo '<p style="font-weight: bold;">'.$price.'₽</p>'.chr(0x0D).chr(0x0A);
                echo '<input type="button" onclick=ReviewLink() value="Отзывы" /><br>'.chr(0x0D).chr(0x0A);
                echo '<input id="count_'.$id.'" type="number" value="0" style="width: 3em;" />'.chr(0x0D).chr(0x0A);
                echo '<input type="button" onclick="AddItem('.$id.','.chr(0x27).$name.chr(0x27).','.$price.')" value="Добавить"/>'.chr(0x0D).chr(0x0A);
                echo '</div>'.chr(0x0D).chr(0x0A);
            }
        } else {
        echo "Table empty.";
        }
    } else {
        echo "Connection error.";
    }
    ?>
    <div id="AjaxCall"></div>
    <script>
        function AddItem(id, name, price){
            var count = document.getElementById("count_"+id).value;
            $(document).ready(function(){
                $("#AjaxCall").load('functions.php?load_sub=yes', {action: "add", id : id, name : name, count : count, price : price});
            });
        }
        function ReviewLink(){
            window.location.href='articles/article5.html';
        }
    </script>
</body>
</html>