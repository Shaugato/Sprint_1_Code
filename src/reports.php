<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="assets/css/reports.css">
    <title>Reports</title>
</head>
<body>
  <script>
      function redirectToDashboard() {
        window.location.href = "dashboard.html"; // Replace with the actual URL of your dashboard page
      }
  </script>

  <?php
	include_once "navmenu.inc"; // Modular Navigation Menu
  ?>

  <h1 class="py-4 text-6xl text-gray-800 font-medium mb-4 capitalize">Reports</h1>

  <form action="reports.php" method="post">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" required>

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" required>

    <button class="buttonContent" type="submit">Search</button>
  </form>



  <?php
    if (isset($_GET['use_remote_db']) && $_GET['use_remote_db'] == 'true') {
        $servername = "192.168.116.46";  // replace with the IP address of the host machine
    } else {
        $servername = "localhost";
    }
    $username = "root";
    $password = "";
    $dbname = "user_database";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve start and end dates from the form data
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        // Validate and sanitize the input (you may need more robust validation)
        $start_date = mysqli_real_escape_string($conn, $start_date);
        $end_date = mysqli_real_escape_string($conn, $end_date);

        // Query to retrieve records within the specified date range
        $sql = "SELECT * FROM `purchase` WHERE `Date` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59';";


        // Execute the query
        $result = $conn->query($sql);

        if ($result) {
            echo "<h2>Search Results</h2>";

            echo "<div class='container'>
                    <button class='buttonContent' onclick='downloadCSV()'>Download CSV</button>
                 </div>";


            // Display the results
            echo "<table id='purchaseTable'>
                    <tr>
                        <th>PurchaseID</th>
                        <th>UserID</th>
                        <th>ProductID</th>
                        <th>Quantity</th>
                        <th>Date</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['PurchaseID']}</td>
                        <td>{$row['UserID']}</td>
                        <td>{$row['ProductID']}</td>
                        <td>{$row['Quantity']}</td>
                        <td>{$row['Date']}</td>
                      </tr>";
            }

            echo "</table>";



            // handling for  JSON -> CSV
            $result = $conn->query($sql);

            $purchaseData = $result->fetch_all(MYSQLI_ASSOC);
            // Output JSON data for JavaScript
            echo "<script>var purchaseData = " . json_encode($purchaseData) . ";</script>";


            // Free the result set
            $result->free();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Handle the case where the form wasn't submitted via POST
        echo "<p>No File to Download</p>";
    }

    // Close the database connection
    $conn->close();
  ?>

<script>
    function downloadCSV() {
        // Check if purchaseData is defined and not empty
        if (purchaseData && purchaseData.length > 0) {
            // Get table data from the JSON data
            var data = purchaseData;

            // Convert JSON to CSV
            var csvContent = "";

            // Add the header row
            csvContent += Object.keys(data[0]).join(",") + "\r\n";

            // Add the data rows
            data.forEach(function (row) {
                csvContent += Object.values(row).join(",") + "\r\n";
            });

            // Create a blob and create a link to trigger the download
            var blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            var link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "purchase_data.csv";
            link.click();
        } else {
            console.error("purchaseData is not defined or is empty.");
        }
    }
</script>



</body>
</html>
