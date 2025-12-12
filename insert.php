<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "formdata";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create table if not exists
$table = "user_info";
$conn->query("CREATE TABLE IF NOT EXISTS $table (
  id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  middle_name VARCHAR(50),
  last_name VARCHAR(50),
  contact_no VARCHAR(15),
  email VARCHAR(100)
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $firstName = $_POST["firstName"];
  $middleName = $_POST["middleName"];
  $lastName = $_POST["lastName"];
  $contactNo = $_POST["contactNo"];
  $email = $_POST["email"];

  // Insert data
  $stmt = $conn->prepare("INSERT INTO $table (first_name, middle_name, last_name, contact_no, email) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $firstName, $middleName, $lastName, $contactNo, $email);
  $stmt->execute();
  $stmt->close();
}

// Fetch all records
$result = $conn->query("SELECT * FROM $table");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="table-container">
    <h3>Submitted Records</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Middle Name</th>
        <th>Last Name</th>
        <th>Contact No</th>
        <th>Email</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo htmlspecialchars($row["id"]); ?></td>
        <td><?php echo htmlspecialchars($row["first_name"]); ?></td>
        <td><?php echo htmlspecialchars($row["middle_name"]); ?></td>
        <td><?php echo htmlspecialchars($row["last_name"]); ?></td>
        <td><?php echo htmlspecialchars($row["contact_no"]); ?></td>
        <td><?php echo htmlspecialchars($row["email"]); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
<?php
$conn->close();
?>