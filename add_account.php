<?php
$servername = "localhost"; // Replace with your actual database host
$username = "root"; // Replace with your actual database username
$password = ""; // Replace with your actual database password
$dbname = "financial_db"; // Replace with your actual database name

// Create a connection to the database
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['newAccountName']) && isset($_POST['newAccountCategory'])) {
        $newAccountName = $_POST['newAccountName'];
        $newAccountCategory = $_POST['newAccountCategory'];

        // Prepare and execute the SQL statement to insert the new account
        $stmt = $mysqli->prepare("INSERT INTO accounts (name, category) VALUES (?, ?)");
        $stmt->bind_param("ss", $newAccountName, $newAccountCategory);
        if ($stmt->execute()) {
            echo "success"; // Return "success" to indicate successful account creation
        } else {
            echo "error"; // Return "error" to indicate an error occurred
        }
        $stmt->close();
    }
}
?>
