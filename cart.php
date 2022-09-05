<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
</head>
<body>
    <img src="img/cart.png" width=32>
    <?php
    $_REQUEST["load_main"] == "yes" || die('Direct access not permitted.');
        if (!isset($_SESSION)){
            session_start();
        }
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        echo '<div style="font-size:30px; display:inline-block">'.count($_SESSION['cart']).'</div>';
            $totalsum = 0;
            foreach($_SESSION['cart'] as &$item){
                $totalsum += intval($item['count'])*intval($item['price']);
            }
            echo ' Всего: '.$totalsum.' ₽<br>';
    ?>
    <button id="btn" onclick="showList()">Показать</button>
    <select name="city" id="city">
        <option value="Москва">Москва</option>
        <option value="Питербург">Питербург</option>
        <option value="Екатиренбург">Екатиренбург</option>
        <option value="Казань">Казань</option>
    </select>
    <button onclick="order()">Заказать</button>
    <div id="list" style="display: none; border-style: solid; line-height: 1.6;">
        <?php
            foreach($_SESSION['cart'] as &$item){
                $sum = intval($item['count'])*intval($item['price']);
                echo $item['name'].' : '.$item['count'].'ш : '.$sum.' ₽';
                echo '<button style="float:right;" id="itemid_'.$item['id'].'" onclick="remove('.$item['id'].')">Убрать</button>';
                echo '<br>';
            }
        ?>
    </div>
    <div id="AjaxCall"></div>
    <script>
        var oldhash = "";
        $.get("functions.php?load_sub=yes&action=gethash", function(data){oldhash = data;});
        setInterval(function() {
            var newhash;
            $.get("functions.php?load_sub=yes&action=gethash", function(data){
                newhash = data;
                if (oldhash != newhash){location.reload();}
            });
        }, 500);
        function remove(id){
            $(document).ready(function(){
                $("#AjaxCall").load('functions.php?load_sub=yes', {action : "remove", id : id});
            });
        }
        function showList(){
            var x = document.getElementById("list");
            if (x.style.display === "none") {
                x.style.display = "block";
                document.getElementById("btn").innerText = "Скрыть";
            } else {
                x.style.display = "none";
                document.getElementById("btn").innerText = "Показать";
            }
        }
        function order(){
            var city = document.getElementById("city").value;
            $(document).ready(function(){
                $("#AjaxCall").load('functions.php?load_sub=yes', {action : "order", city: city});
            });
        }
    </script>
</body>
</html>