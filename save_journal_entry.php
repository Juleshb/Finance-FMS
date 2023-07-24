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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the journal entry data from the form
    $draccount = $_POST['draccount'];
    $craccount = $_POST['craccount'];
    $amount = $_POST['amount'];
    $entry_date = $_POST['entry_date'];
    $description = $_POST['description'];
    $credit = 0;
    $debit = 0;

    // Insert the journal entry into the database
    $sql = "INSERT INTO journal_entries (entry_date, description) VALUES (?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $entry_date, $description);
    $stmt->execute();
    $stmt->close();

    // Get the newly inserted journal entry ID
    $journal_entry_id = $mysqli->insert_id;

    // Update the ledger accounts based on the account type (Asset, Liability, Equity, Revenue, Expense)
    if ($amount > 0) {
        // Debit amount present, increase the account balance
        $sql = "UPDATE accounts SET balance = balance + ? WHERE name = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ds", $amount, $draccount);
        $stmt->execute();
        $stmt->close();
// Credit amount present, decrease the account balance
        $sql1 = "UPDATE accounts SET balance = balance - ? WHERE name = ?";
        $stmt1 = $mysqli->prepare($sql);
        $stmt1->bind_param("ds", $amount, $craccount);
        $stmt1->execute();
        $stmt1->close();
    } else {
        // Both debit and credit are zero (shouldn't happen in a valid journal entry)
        echo json_encode(['status' => 'error', 'message' => 'Invalid journal entry.']);
        exit;
    }

    // Get the account_id from the accounts table based on the account name
    $sql = "SELECT id FROM accounts WHERE name = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $draccount);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $draccount_id = $row['id'];
    } else {
        // Account not found in the accounts table
        echo json_encode(['status' => 'error', 'message' => 'Invalid account name.']);
        exit;
    }

    $stmt->close();

    $sql = "SELECT id FROM accounts WHERE name = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $craccount);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row) {
        $craccount_id = $row['id'];
    } else {
        // Account not found in the accounts table
        echo json_encode(['status' => 'error', 'message' => 'Invalid account name.']);
        exit;
    }

    $stmt->close();

    // Insert the transaction details into the transactions table
    $sql = "INSERT INTO transactions (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iidd", $journal_entry_id, $draccount_id, $amount, $credit);
    $stmt->execute();
    $stmt->close();

    $sql = "INSERT INTO transactions (journal_entry_id, account_id, debit, credit) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iidd", $journal_entry_id, $craccount_id, $debit, $amount);
    $stmt->execute();
    $stmt->close();
    // Return a success response to the client
    echo json_encode(['status' => 'success']);
} else {
    // Return an error response to the client
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
