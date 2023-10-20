<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Page</title>
  <link rel="stylesheet" href="assets/css/purchase_styles.css">
</head>
<body>
	<?php
		include_once "navmenu.inc"; // Modular Navigation Menu
	?>
  <header>
	<h1 class="py-4 pl-16 text-6xl text-white font-medium capitalize">Purchase Page</h1>
    <a href="shop.php" class=" border-t bg-white border border-primary text-primary px-8 py-3 font-medium
                    rounded-md hover:bg-transparent hover:text-white">Shop</a>
  </header>

  <main>

    <div id="purchase-container">
      <?php
      if (session_status() === PHP_SESSION_NONE) {
    	session_start();
		}
      $userId = $_SESSION['user_id']; // Get this from the session after login

      if (!isset($_SESSION['cart'][$userId])) {
          $_SESSION['cart'][$userId] = [];
      };

      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "user_database";

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      if (isset($_POST['buy'])) {
        $userId = $_SESSION['user_id'];  // Make sure this session variable is set after user login

        foreach ($_SESSION['cart'][$userId] as $productId => $quantity) {
            // Update the inventory
            $sql = "UPDATE inventory SET StockLevel = StockLevel - $quantity WHERE ProductID = $productId";
            $conn->query($sql);

            // Insert into order_history
            $sql = "INSERT INTO order_history (user_id, product_id, quantity, order_date) VALUES ('$userId', '$productId', '$quantity', NOW())";
            $conn->query($sql);
        }

        $_SESSION['cart'][$userId] = [];  // Clear cart for this user
    }

    if (isset($_SESSION['cart'][$userId]) && count($_SESSION['cart'][$userId]) > 0) {
        $productIds = array_keys($_SESSION['cart'][$userId]);
        $ids = implode(",", $productIds);
        $sql = "SELECT ProductID, Name, Description, Price, img_url FROM product WHERE ProductID IN ($ids)";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $productId = $row["ProductID"];
            echo '<div class="purchase-card">';
            echo '<img src="'.$row["img_url"].'" alt="'.$row["Name"].'">';
            echo '<h3>'.$row["Name"].'</h3>';
            echo '<p>'.$row["Description"].'</p>';
            echo '<p class="price">Price: $'.$row["Price"].'</p>';
            echo '<input type="number" class="quantity" data-product-id="'.$productId.'" value="'. (isset($_SESSION['cart'][$userId][$productId]) ? $_SESSION['cart'][$userId][$productId] : 0) .'">';

            echo '</div>';
          }
        } else {
          echo "No products in cart";
        }
      } else {
        echo "Your cart is empty.";
      }
      ?>

      <!-- Add Buy button here -->
      <form method="post">
            <button type="submit" name="buy" class="buy-button">Buy</button>
        </form>

      <div id="total-amount">
        <!-- Total amount will be displayed here -->
      </div>
    </div>
  </main>

  <footer>
    <p>Copyright © 2023</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const quantities = document.querySelectorAll('.quantity');
      let totalAmount = 0;

      // Function to update the total amount
      function updateTotalAmount() {
        totalAmount = 0; // Reset total amount
        quantities.forEach((quantityElem) => {
          const price = parseFloat(quantityElem.closest('.purchase-card').querySelector('.price').textContent.replace('Price: $', ''));
          totalAmount += price * quantityElem.value;
        });
        document.getElementById('total-amount').textContent = 'Total Amount: $' + totalAmount.toFixed(2);
      }

      // Initial total amount calculation
      updateTotalAmount();

      quantities.forEach((quantityElem) => {
        // Attach event listener to update total amount when quantity changes
        quantityElem.addEventListener('input', function() {
          updateTotalAmount();
        });
      });
    });
  </script>
</body>
</html>
