<?php
    $_REQUEST["load_sub"] == "yes" || die('Direct access not permitted.');
    define('access', TRUE);
    require_once "connect.php";
    if (!isset($_SESSION)){
        session_start();
    }
    if($_REQUEST["action"] == "add"){
        $push["name"] = $_REQUEST["name"];
        $push["id"] = $_REQUEST["id"];
        $push["count"] = $_REQUEST["count"];
        $push["price"] = $_REQUEST["price"];
        array_push($_SESSION['cart'], $push);
    } else if($_REQUEST["action"] == "remove"){
        $id = $_REQUEST["id"];
        $temp = $_SESSION['cart'];
        foreach($_SESSION['cart'] as $key => $item){
            if ($item['id'] == $id){
                unset($temp[$key]);
            }
        }
        $_SESSION['cart'] = $temp;
    } else if($_REQUEST["action"] == "gethash"){
        echo md5(serialize($_SESSION));
    } else if($_REQUEST["action"] == "order"){
        $order = '';
        foreach($_SESSION['cart'] as $key => $item){
            $order .= $item['name'].' '.$item['id'].' ';
        }
        $totalsum = 0;
        foreach($_SESSION['cart'] as &$item){
            $totalsum += intval($item['count'])*intval($item['price']);
        }
        if ($stmt = $link->prepare("INSERT INTO orders (user, content, price, address) VALUES (?, ?, ?, ?)")){
            $stmt->bind_param("ssis", $_SESSION['user']['name'], $order, $totalsum, $_REQUEST["city"]);
            $stmt->execute();
            $stmt->close();
            unset($_SESSION['cart']);
            echo 'Заказ отправлен.';
        }
    } else {
        echo "Can't see request.";
    }
?>