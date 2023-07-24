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

if (isset($_GET['query'])) {
    $query = $_GET['query'];
   

    // Query the accounts table for matching accounts
    $sql = "SELECT name FROM accounts WHERE name LIKE '%$query%'";
    $result = $mysqli->query($sql);

    $accounts = array();
    while ($row = $result->fetch_assoc()) {
        $accounts[] = $row['name'];
    }

    // Set the Content-Type header to JSON
    header('Content-Type: application/json');

    // Return the account names as a JSON array
    echo json_encode($accounts);
}
?>
