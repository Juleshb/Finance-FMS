<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Balance</title>
    <!-- Bootstrap CSS -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>
    <?php include 'menu.html'; // Assuming you have a "menu.html" file for the navigation menu ?>
    <div class="container mt-5">
        <div class="row">
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

            // Generate separate tables for each account
            foreach ($ledger_entries as $account_name => $entries) {
                echo "<h4 class='mt-3'>$account_name</h4>";
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped">';
                echo '<tr><th>Date</th><th>Description</th><th>Debit</th><th>Credit</th></tr>';

                // Loop through the ledger entries for the current account
                foreach ($entries as $entry) {
                    $entry_date = $entry['entry_date'];
                    $description = $entry['description'];
                    $debit = $entry['debit'];
                    $credit = $entry['credit'];

                    echo "<tr><td>$entry_date</td><td>$description</td><td>$debit</td><td>$credit</td></tr>";
                }

                echo '</table>';
                echo '</div>';
                echo '<br>'; // Add space between tables
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
