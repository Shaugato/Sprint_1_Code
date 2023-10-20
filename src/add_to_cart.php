<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['productId']) && isset($_SESSION['user_id'])) {
        $productId = intval($_POST['productId']);
        $userId = $_SESSION['user_id'];
        
        if (!isset($_SESSION['cart'][$userId])) {
            $_SESSION['cart'][$userId] = [];
        }

        if (!isset($_SESSION['cart'][$userId][$productId])) {
            $_SESSION['cart'][$userId][$productId] = 0;
        }

        $_SESSION['cart'][$userId][$productId] += 1;
    }
}

echo "Product added to cart.";
?>
