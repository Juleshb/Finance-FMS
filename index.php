<!DOCTYPE html>
<html>
<head>
    <title>FMS</title>
    <style>
    /* CSS class to change cursor to pointer on hover */
    .account-item:hover {
        cursor: pointer;
    }
</style>
</head>
<body>

    <!-- Include the menu -->
    <?php include 'menu.html'; ?>

   <!-- Your main content here -->
   <div id="confirmationMessage" class="mt-3" style="display: none;">
                <div class="alert alert-success" role="alert">
                    Journal Entry saved successfully!
                </div>
            </div>
            <div id="accconfirmationMessage" class="mt-3" style="display: none;">
                <div class="alert alert-success" role="alert">
                    Account saved successfully!
                </div>
            </div>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
        <h2>Finance Journal Entry Form</h2>
            <!-- Account Input -->
            <div class="form-group col-md-6">
                <label for="account">Dr Account:</label>
                <input type="text" class="form-control" id="accountinput" name="account" required>
            </div>
            <ol id="account"></ol>

            <div class="form-group col-md-6">
                <label for="account">Cr Account:</label>
                <input type="text" class="form-control" id="craccountinput" name="account" required>
            </div>
            <ol id="craccount"></ol>

            <!-- Form to Add New Account -->
            <div id="newAccountForm" style="display: none;">
                <h4>Add New Account</h4>
                <form id="addAccountForm">
                    <div class="form-group">
                        <label for="newAccountName">Account Name:</label>
                        <input type="text" class="form-control" id="newAccountName" name="newAccountName" required>
                    </div>
                    <div class="form-group">
                        <label for="newAccountCategory">Category:</label>
                        <select class="form-control" id="newAccountCategory" name="newAccountCategory" required>
                            <!-- Add the options for different account categories -->
                            <option value="Asset">Asset</option>
                            <option value="Liability">Liability</option>
                            <option value="Equity">Equity</option>
                            <option value="Revenue">Revenue</option>
                            <option value="Expense">Expense</option>
                        </select>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary">Add Account</button>
                </form>
            </div>

            <!-- Journal Entry Form -->
            <form id="journalEntryForm">
            <input type="hidden" name="draccount" id="selectedAccount" value="">
            <input type="hidden" name="craccount" id="selectedcrAccount" value="">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="debit">Amount:</label>
                        <input type="number" class="form-control" id="amount" name="amount"  required>
                    </div>  
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="entry_date">Entry Date:</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Save Journal Entry</button>
            </form>

            
        </div>

        <!-- Journal Entries Display -->
        <div class="col-md-6">
            <h2>Journal Entries</h2>
            <div class="mt-3">
            <button id="deleteTransactionsBtn" class="btn btn-danger">Delete All Transactions</button>
    </div>
            <div id="journalEntryDisplay">
                <!-- The table will be inserted here -->
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle button click to delete all transactions
        $("#deleteTransactionsBtn").on("click", function() {
            // Send AJAX request to delete_all_transactions.php
            $.ajax({
                type: "POST",
                url: "delete_all_transactions.php",
                success: function(response) {
                    if (response === "success") {
                        // Show success message or perform other actions on success
                        alert("All transactions deleted successfully!");
                        $('#journalTable').load(location.href + " #journalTable");
                        // You may reload the page to refresh the transaction display
                        // location.reload();
                    } else {
                        // Show error message or perform other actions on error
                        alert("Error deleting transactions.");
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error here
                    console.log(xhr.responseText);
                    console.log(status);
                    console.log(error);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Handle input changes for the "Account" input
        $("#accountinput").keyup(function(e) {
            var query = $(this).val();

            // Fetch matching accounts from the server using AJAX
            $.ajax({
                type: 'GET',
                url: 'get_accounts.php',
                data: { query: query },
                dataType: "json", // Set the dataType to JSON
                success: function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var name = value; // Access the name directly
                            html += '<li class="account-item" onclick="selectAccount(\'' + name + '\')">' + name + '</li>';
                            i++;
                        });
                        $('#account').html(html);
                        // Show the existing accounts list and hide the new account form
                        $('#account').show();
                        $('#newAccountForm').hide();
                    } else {
                        // Hide the existing accounts list and show the new account form
                        $('#account').hide();
                        $('#newAccountForm').show();
                    }
                },
                error: function(error) {
                    // Handle error here
                    console.log(error);
                }
            });
        });

        $("#craccountinput").keyup(function(e) {
            var query = $(this).val();

            // Fetch matching accounts from the server using AJAX
            $.ajax({
                type: 'GET',
                url: 'get_accounts.php',
                data: { query: query },
                dataType: "json", // Set the dataType to JSON
                success: function(data) {
                    if (Array.isArray(data) && data.length > 0) {
                        var i = 1;
                        var html = '';
                        data.forEach(function(value) {
                            var name = value; // Access the name directly
                            html += '<li class="account-item" onclick="selectcrAccount(\'' + name + '\')">' + name + '</li>';
                            i++;
                        });
                        $('#craccount').html(html);
                        // Show the existing accounts list and hide the new account form
                        $('#craccount').show();
                        $('#newAccountForm').hide();
                    } else {
                        // Hide the existing accounts list and show the new account form
                        $('#account').hide();
                        $('#newAccountForm').show();
                    }
                },
                error: function(error) {
                    // Handle error here
                    console.log(error);
                }
            });
        });

        // Handle form submission for adding a new account
        $("#addAccountForm").submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            // Get form data for the new account
            var formData = $(this).serialize();

            // Send form data to the server using AJAX
            $.ajax({
                type: "POST",
                url: "add_account.php", // Replace with your server-side script URL to add the new account
                data: formData,
                success: function(response) {
                    $("#addAccountForm")[0].reset();
                    $('#newAccountForm').hide();
                    $("#accconfirmationMessage").show();
                    // Handle the response from the server here (e.g., display a success message)
                    console.log(response);
                },
                error: function(error) {
                    // Handle error here
                    console.log(error);
                }
            });
        });
    });

    function selectAccount(accountName) {
        // Set the selected account name in the input field
        $("#selectedAccount").val(accountName);

        // Set the account name in the accountinput field for display
        $("#accountinput").val(accountName);

        // Hide the new account form
        $("#newAccountForm").hide();
        $("#account").hide();
    }
    function selectcrAccount(accountName) {
        // Set the selected account name in the input field
        $("#selectedcrAccount").val(accountName);

        // Set the account name in the accountinput field for display
        $("#craccountinput").val(accountName);

        // Hide the new account form
        $("#newAccountForm").hide();
        $("#craccount").hide();
       
    }
</script>


<script>
    $(document).ready(function() {
        // Function to fetch and display Journal Entries in a table
        function fetchJournalEntries() {
            $.ajax({
                type: "GET",
                url: "get_journal_entries.php", // Replace with your server-side script URL to fetch Journal Entries
                dataType: "json",
                success: function(response) {
                    // Check if there are Journal Entries to display
                    if (response.length > 0) {
                        // Create the Journal Entry table
                        var tableHTML = '<table class="table table-striped" id="journalTable">';
                        tableHTML += '<thead><tr><th>Date</th><th>Account</th><th>Description</th><th>Debit</th><th>Credit</th></tr></thead>';
                        tableHTML += '<tbody>';
                        // Loop through the Journal Entries and add rows to the table
                        for (var i = 0; i < response.length; i++) {
                            var entry = response[i];
                            tableHTML += '<tr><td>' + entry.entry_date + '</td>';
                            tableHTML += '<td>' + entry.account_name + '</td>';
                            tableHTML += '<td>' + entry.description + '</td>';
                            tableHTML += '<td>' + entry.debit + 'Rwf</td>';
                            tableHTML += '<td>' + entry.credit + 'Rwf</td></tr>';
                            
                        }
                        tableHTML += '</tbody></table>';

                        // Insert the table into the #journalEntryDisplay div
                        $("#journalEntryDisplay").html(tableHTML);
                    } else {
                        // If no Journal Entries, display a message
                        $("#journalEntryDisplay").html('<p>No Journal Entries found.</p>');
                    }
                },
                error: function(error) {
                    // Handle error here
                    console.log(error);
                }
            });
        }

        // Call the function to fetch and display Journal Entries
        fetchJournalEntries();
            // Handle form submission
            $("#journalEntryForm").submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                // Get form data
                var formData = $(this).serialize();

                // Send form data to the server using AJAX
                $.ajax({
                    type: "POST",
                    url: "save_journal_entry.php", // Replace with your server-side script URL
                    data: formData,
                    success: function(response) {
                        // Show confirmation message on success
                        $("#confirmationMessage").show();
                        $('#journalTable').load(location.href + " #journalTable");
                        $("#journalEntryForm")[0].reset(); // Clear the form fields
                        
                    },
                    error: function(error) {
                        // Handle error here
                        console.log(error);
                    }
                });
            });
        });
    </script>
</body>
</html>
