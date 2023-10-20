<?php
session_start();
$userId = $_SESSION['user_id']; // Get this from the session after login

if (!isset($_SESSION['cart'][$userId])) {
    $_SESSION['cart'][$userId] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Page</title>
  <link rel="stylesheet" href="assets/css/shop_styles.css">

</head>
<body>
  <?php
	include_once "navmenu.inc"; // Modular Navigation Menu
  ?>
  <header>
    <h1 class="py-4 pl-16 text-6xl text-white font-medium capitalize">My Online Shop</h1>
    <a href="purchase.php" class=" border-t bg-white border border-primary text-primary px-8 py-3 font-medium
                    rounded-md hover:bg-transparent hover:text-white">Go to Purchase</a>
  </header>

  <nav class="py-4 pl-16">
    <label for="category">Filter by Category:</label>
    <select id="category" name="category">
      <option value="all">All</option>
      <option value="Dairy">Dairy</option>
      <option value="Bakery">Bakery</option>
      <option value="Grains">Grains</option>
      <option value="Meat">Meat</option>
      <option value="Fruits">Fruits</option>
      <option value="Vegetables">Vegetables</option>
      <option value="Drinks">Drinks</option>
      <option value="Beverages">Beverages</option>
      <option value="Condiments">Condiments</option>
      <!-- Add more categories here -->
    </select>
  </nav>

  <main>
    <div id="product-container">
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "user_database";
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      $sql = "SELECT ProductID, Name, Description, Price, Category, img_url FROM product";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo '<div class="product-card" data-category="' . $row["Category"] . '" data-id="' . $row["ProductID"] . '">';
              echo '<img src="' . $row["img_url"] . '" alt="' . $row["Name"] . '">';
              echo '<h3>' . $row["Name"] . '</h3>';
              echo '<p>' . $row["Description"] . '</p>';
              echo '<p class="price">Price: $' . $row["Price"] . '</p>';
              echo '<button class="purchase-button" onclick="addToCart(' . $row["ProductID"] . ')">Add to Cart</button>';
              echo '</div>';
          }
      } else {
          echo "0 results";
      }
      $conn->close();
      ?>
    </div>
  </main>

  <footer>
    <p>Copyright © 2023</p>
  </footer>

  <script>
document.addEventListener('DOMContentLoaded', function() {
      const categorySelect = document.getElementById('category');
      const productCards = document.querySelectorAll('.product-card');

      categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;

        productCards.forEach((card) => {
          const productCategory = card.getAttribute('data-category');

          if (selectedCategory === 'all' || productCategory === selectedCategory) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
    function addToCart(productId) {
    let userId = <?php echo $_SESSION['user_id']; ?>; // Fetch user ID from PHP session
    let xhr = new XMLHttpRequest();

    xhr.open('POST', 'add_to_cart.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert('Product added to cart');
        }
    };

    xhr.send('productId=' + productId + '&userId=' + userId);
}


  </script>
</body>
</html>
