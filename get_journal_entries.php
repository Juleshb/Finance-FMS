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

// Query the database to get Journal Entries
$sql = "SELECT journal_entries.entry_date, accounts.name AS account_name, transactions.debit, transactions.credit, journal_entries.description
        FROM transactions 
        INNER JOIN accounts ON transactions.account_id = accounts.id
        INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id
        ORDER BY journal_entries.entry_date";

$result = $mysqli->query($sql);

// Fetch the results and store them in an array
$journal_entries = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $journal_entries[] = $row;
    }
}

// Close the database connection
$mysqli->close();

// Return the Journal Entries as JSON
header('Content-Type: application/json');
echo json_encode($journal_entries);
?>
