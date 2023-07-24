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

// Define the specific date (the point in time for the balance sheet)
$specific_date = '2023-12-31'; // Replace this with the specific date you want to create the balance sheet for

// Calculate total assets
$total_assets = 0;

// Query the relevant ledger entries for assets
$assets_query = "SELECT SUM(debit) - SUM(credit) AS total_assets FROM transactions 
                 INNER JOIN accounts ON transactions.account_id = accounts.id
                 INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id
                 WHERE accounts.category = 'Asset' AND journal_entries.entry_date <= '$specific_date'";

$assets_result = $mysqli->query($assets_query);
if ($assets_result->num_rows > 0) {
    $row = $assets_result->fetch_assoc();
    $total_assets = $row['total_assets'];
}

// Calculate total liabilities
$total_liabilities = 0;

// Query the relevant ledger entries for liabilities
$liabilities_query = "SELECT SUM(credit) - SUM(debit) AS total_liabilities FROM transactions 
                      INNER JOIN accounts ON transactions.account_id = accounts.id
                      INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id
                      WHERE accounts.category = 'Liability' AND journal_entries.entry_date <= '$specific_date'";

$liabilities_result = $mysqli->query($liabilities_query);
if ($liabilities_result->num_rows > 0) {
    $row = $liabilities_result->fetch_assoc();
    $total_liabilities = $row['total_liabilities'];
}

// Calculate equity
$total_equity = $total_assets - $total_liabilities;
?>


    <div class="container mt-5">
        <h2>Balance Sheet (As of <?php echo $specific_date; ?>)</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Assets</td>
                    <td><?php echo $total_assets; ?></td>
                </tr>
                <tr>
                    <td>Liabilities</td>
                    <td><?php echo $total_liabilities; ?></td>
                </tr>
                <tr>
                    <td>Equity</td>
                    <td><?php echo $total_equity; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
