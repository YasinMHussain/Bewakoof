<?php
// Error variables
$nameErr = $emailErr = $passwordErr = $ageErr = "";
$name = $email = $password = $age = "";

// Database credentials
$host = "localhost";
$user = "root";
$pass = "";
$db = "userdb";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize function
function clean($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Name validation
    if (empty($_POST["name"])) {
        $nameErr = "Name is required.";
    } else {
        $name = clean($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Only letters and spaces allowed.";
        }
    }

    // Email validation
    if (empty($_POST["email"])) {
        $emailErr = "Email is required.";
    } else {
        $email = clean($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format.";
        }
    }

    // Password validation
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required.";
    } else {
        $password = clean($_POST["password"]);
        if (strlen($password) < 6) {
            $passwordErr = "Password must be at least 6 characters.";
        }
    }

    // Age validation
    if (empty($_POST["age"])) {
        $ageErr = "Age is required.";
    } else {
        $age = clean($_POST["age"]);
        if (!is_numeric($age)) {
            $ageErr = "Age must be a number.";
        }
    }

    // If all fields are valid → Insert into DB
    if ($nameErr == "" && $emailErr == "" && $passwordErr == "" && $ageErr == "") {

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, age) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $age);

        if ($stmt->execute()) {
            echo "<h3>Registration Successful!</h3>";
            echo "Name: $name<br>";
            echo "Email: $email<br>";
            echo "Age: $age<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "<h3>Validation Errors:</h3>";
        echo $nameErr . "<br>";
        echo $emailErr . "<br>";
        echo $passwordErr . "<br>";
        echo $ageErr . "<br>";
    }
}

$conn->close();
?>
