<?php
session_start();
include("connection/connect.php");
if(empty($_SESSION["user_id"])) {
    header('location:login.php');
    exit();
}

$item_total = 0;
if(isset($_SESSION["cart_item"]) && !empty($_SESSION["cart_item"])) {
    foreach($_SESSION["cart_item"] as $item) {
        $item_total += ($item["price"] * $item["quantity"]);
    }
}

if(isset($_POST['submit'])) {
    $order_success = true;
    foreach($_SESSION["cart_item"] as $item) {
        $stmt = $db->prepare("INSERT INTO users_orders(u_id, title, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isid", $_SESSION["user_id"], $item["title"], $item["quantity"], $item["price"]);
        if(!$stmt->execute()) {
            $order_success = false;
            break;
        }
        $stmt->close();
    }
    if($order_success) {
        unset($_SESSION["cart_item"]);
        echo "<script>alert('Thank you. Your order has been placed!'); window.location='your_orders.php';</script>";
        exit();
    } else {
        $error = "Order failed. Please try again.";
    }
}
?>
<!-- HTML remains mostly same, but ensure form method="post" -->
