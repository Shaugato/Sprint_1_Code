<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Management Purchase Page</title>
  <link rel="stylesheet" href="assets/css/management_purchase_styles.css">
</head>
<body>
  <?php
	include_once "navmenu.inc"; // Modular Navigation Menu
  ?>
  <header>
    <h1 class="py-4 pl-16 text-6xl text-white font-medium capitalize">Management Purchase Page</h1>
  </header>

  <main>
    <div id="purchase-container" class="card-container">

      <?php



      if (session_status() === PHP_SESSION_NONE) {
        session_start();
	  }

      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "user_database";

      $conn = new mysqli($servername, $username, $password, $dbname);

      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }

      if (isset($_POST['buy'])) {
        $userId = $_POST['user_id'];  // Get user ID from form input

        foreach ($_POST['quantities'] as $productId => $quantity) {
		  if ($quantity > 0){
            $sql = "UPDATE inventory SET StockLevel = StockLevel - $quantity WHERE ProductID = $productId";
            $conn->query($sql);

            $sql = "INSERT INTO order_history (user_id, product_id, quantity, order_date) VALUES ('$userId', '$productId', '$quantity', NOW())";
            $conn->query($sql);
		  }
        }
      }

      $sql = "SELECT ProductID, Name, Description, Price, img_url FROM product";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        echo '<form method="post">';
        echo '<label for="user_id">User ID: </label>';
        echo '<input type="text" name="user_id" required><br><br>';
        while($row = $result->fetch_assoc()) {
          $productId = $row["ProductID"];
          echo '<div class="purchase-card">';
          echo '<img src="'.$row["img_url"].'" alt="'.$row["Name"].'">';
          echo '<h3>'.$row["Name"].'</h3>';
          echo '<p>'.$row["Description"].'</p>';
          echo '<p class="price">Price: $'.$row["Price"].'</p>';
          echo '<input type="number" name="quantities['.$productId.']" class="quantity" value="0">';
          echo '</div>';
        }
        echo '<button class="bg-primary border border-primary text-white px-8 py-3 font-medium
                    rounded-md hover:bg-transparent hover:text-primary" type="submit" name="buy" class="buy-button">Buy</button>';
        echo '</form>';
      } else {
        echo "No products available.";
      }
      ?>

    </div>
  </main>

  <footer>
    <p>Copyright © 2023</p>
  </footer>
</body>
</html>
