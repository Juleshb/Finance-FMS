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

// Delete all transactions
$delete_transactions_query = "DELETE FROM transactions";
if ($mysqli->query($delete_transactions_query) === TRUE) {
    // Delete all journal entries
    $delete_journal_entries_query = "DELETE FROM journal_entries";
    if ($mysqli->query($delete_journal_entries_query) === TRUE) {
        // Delete all accounts
        $delete_accounts_query = "DELETE FROM accounts";
        if ($mysqli->query($delete_accounts_query) === TRUE) {
            echo "success"; // Return "success" if all deletions are successful
        } else {
            echo "Error deleting accounts: " . $mysqli->error;
        }
    } else {
        echo "Error deleting journal entries: " . $mysqli->error;
    }
} else {
    echo "Error deleting transactions: " . $mysqli->error;
}

// Close the database connection
$mysqli->close();
?>
