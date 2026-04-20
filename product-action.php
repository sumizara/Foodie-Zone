<?php
if(!empty($_GET["action"])) {
    $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    switch($_GET["action"]) {
        case "add":
            if($quantity > 0 && $productId > 0) {
                $stmt = $db->prepare("SELECT d_id, title, price FROM dishes WHERE d_id = ?");
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                if($productDetails = $result->fetch_object()) {
                    $itemArray = array($productDetails->d_id => array(
                        'title' => htmlspecialchars($productDetails->title),
                        'd_id' => $productDetails->d_id,
                        'quantity' => $quantity,
                        'price' => $productDetails->price
                    ));
                    if(!empty($_SESSION["cart_item"])) {
                        if(array_key_exists($productId, $_SESSION["cart_item"])) {
                            $_SESSION["cart_item"][$productId]["quantity"] += $quantity;
                        } else {
                            $_SESSION["cart_item"] += $itemArray;
                        }
                    } else {
                        $_SESSION["cart_item"] = $itemArray;
                    }
                }
                $stmt->close();
            }
            break;
        case "remove":
            if(isset($_SESSION["cart_item"][$productId])) {
                unset($_SESSION["cart_item"][$productId]);
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
        case "check":
            header("location:checkout.php");
            break;
    }
}
?>
