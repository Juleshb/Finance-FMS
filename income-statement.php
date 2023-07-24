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

// Define the specific period (start and end date)
$start_date = '2023-01-01';
$end_date = '2023-12-31';

// Calculate total revenues
$total_revenues = 0;

// Query the relevant ledger entries for revenues
$revenues_query = "SELECT SUM(debit) AS total_revenues FROM transactions 
                    INNER JOIN accounts ON transactions.account_id = accounts.id
                    INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id
                    WHERE accounts.category = 'Revenue' AND journal_entries.entry_date BETWEEN '$start_date' AND '$end_date'";

$revenues_result = $mysqli->query($revenues_query);
if ($revenues_result->num_rows > 0) {
    $row = $revenues_result->fetch_assoc();
    $total_revenues = $row['total_revenues'];
}

// Calculate total expenses
$total_expenses = 0;

// Query the relevant ledger entries for expenses
$expenses_query = "SELECT SUM(debit) AS total_expenses FROM transactions 
                    INNER JOIN accounts ON transactions.account_id = accounts.id
                    INNER JOIN journal_entries ON transactions.journal_entry_id = journal_entries.id
                    WHERE accounts.category = 'Expense' AND journal_entries.entry_date BETWEEN '$start_date' AND '$end_date'";

$expenses_result = $mysqli->query($expenses_query);
if ($expenses_result->num_rows > 0) {
    $row = $expenses_result->fetch_assoc();
    $total_expenses = $row['total_expenses'];
}

// Calculate net income
$net_income = $total_revenues - $total_expenses;

?>

<!DOCTYPE html>
    <?php include 'menu.html'; // Assuming you have a "menu.html" file for the navigation menu ?>
    <div class="container mt-5">
        <h1 class="text-center">Income Statement</h1>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <?php
                // Display Total Revenues
                echo "<h4>Total Revenues</h4>";
                echo "<p>Amount: $total_revenues</p>";
                ?>
            </div>
            <div class="col-md-6">
                <?php
                // Display Total Expenses
                echo "<h4>Total Expenses</h4>";
                echo "<p>Amount: $total_expenses</p>";
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                // Display Net Income
                echo "<h4>Net Income</h4>";
                echo "<p>Amount: $net_income</p>";
                ?>
            </div>
        </div>
    </div>

</body>
</html>

