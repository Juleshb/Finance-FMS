<?php
include 'menu.html';
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

// Query the ledger entries to get all transactions for each account
$ledger_entries = array();

$ledger_query = "SELECT journal_entries.entry_date, journal_entries.description, accounts.name AS account_name, 
                transactions.debit, transactions.credit
                FROM transactions 
                INNER JOIN accounts ON transactions.account_id = accounts.id
                INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id";

$ledger_result = $mysqli->query($ledger_query);
if ($ledger_result->num_rows > 0) {
    while ($row = $ledger_result->fetch_assoc()) {
        $entry_date = $row['entry_date'];
        $description = $row['description'];
        $account_name = $row['account_name'];
        $debit = $row['debit'];
        $credit = $row['credit'];

        // Store the transaction details in the array
        $ledger_entries[$account_name][] = [
            'entry_date' => $entry_date,
            'description' => $description,
            'debit' => $debit,
            'credit' => $credit
        ];
    }
}

// Initialize total balance for all accounts
$total_balance_all_accounts = 0;

// Generate separate tables for each account
foreach ($ledger_entries as $account_name => $entries) {
echo   '<div class="container mt-5">';
echo   '<div class="row">';
echo "<h4 class='mt-3'>$account_name Transactions</h4>";
echo '<div class="table-responsive">';
echo '<table class="table table-striped col-md-6">';
echo '<tr><th>Date</th><th>Description</th><th>Debit</th><th>Credit</th></tr>';

// Initialize totals for debit and credit for each account
$total_debit = 0;
$total_credit = 0;

// Loop through the ledger entries for the current account
foreach ($entries as $entry) {
    $entry_date = $entry['entry_date'];
    $description = $entry['description'];
    $debit = $entry['debit'];
    $credit = $entry['credit'];

    // Calculate the total debit and total credit for the current account
    $total_debit += $debit;
    $total_credit += $credit;

    echo "<tr><td>$entry_date</td><td>$description</td><td>$debit</td><td>$credit</td></tr>";
}

// Calculate the total balance for the current account
$total_balance = $total_debit - $total_credit;
$total_balance_all_accounts += $total_balance; // Add current account balance to the total balance for all accounts

// Display the total of both debit and credit and the total balance for the current account
echo "<tr><td colspan='2'>Total</td><td>$total_debit</td><td>$total_credit</td></tr>";
echo "<tr><td colspan='3'>Balance</td><td>$total_balance</td></tr>";

echo '</table>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '<br>'; // Add space between tables

}

// Display the total balance for all accounts
echo '<strong>Total Balance for All Accounts:</strong> ' . $total_balance_all_accounts;

?>
