<?php
session_start();
include('../config/config.php');

$totalAmount = 0;
$profit = 0;
$loss = 0;

// Handle Add Transaction
include('../views/insertDbData.php');

// Handle Update Transaction
include('../views/update.php');

// Handle Delete Transaction
if (isset($_POST['deleteTransaction'])) {
    $id = $_POST['id'];

    if (!empty($id)) {
        $stmt = $conn->prepare("DELETE FROM expenselist WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

// Fetch expenses from the database
$fetchExpenses = $conn->prepare("SELECT * FROM expenselist");
$fetchExpenses->execute();
$expenses = $fetchExpenses->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$totalAmount = 0;
$profit = 0;
$loss = 0;
foreach ($expenses as $expense) {
    $totalAmount += $expense['amount'];
    if ($expense['type'] == 'credit') {
        $profit += $expense['amount'];
    } else {
        $loss += $expense['amount'];
    }
}

if (isset($_POST['resetTransactions'])) {
    $expenses = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>

    <div class="container">
        <h1 class="mainHeading">Expense Tracker</h1>

        <!-- Add Transaction Form -->
        <form action="" method="post" class="transactionForm">
            <input type="text" name="expenseName" class="inputField" placeholder="Enter your Expense Name here: " required />
            <br><br>
            <input type="number" name="expenseAmount" class="inputField" placeholder="Enter amount here: " required />
            <br><br>
            <select name="expenseType" class="inputSelect" required>
                <option value="">Select option here</option>
                <option value="expense">Expense</option>
                <option value="credit">Credit</option>
            </select>
            <br><br>
            <button name="addTransaction" type="submit" class="btnPrimary">Add Transaction</button>
        </form>

        <!-- Total and Profit/Loss Display -->
        <div class="summaryBox">
            <?php
            echo "<p class='summaryText'>Total amount: <span class='totalAmount'>" . $totalAmount . "</span></p>";
            echo "<p class='summaryText'>Overall profit: <span class='profitAmount'>" . $profit . "</span></p>";
            echo "<p class='summaryText'>Overall loss: <span class='lossAmount'>" . $loss . "</span></p>";
            ?>
        </div>

        <!-- Transaction List -->
        <h2 class="sectionHeading">Transaction List</h2>
        <table class="transactionTable">
            <thead>
                <tr class="tableRow">
                    <th>Expense Title</th>
                    <th>Expense Amount</th>
                    <th>Expense Type</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($expenses) {
                    foreach ($expenses as $expense) {
                        echo '<tr class="tableRow">';
                        echo '<td>' . htmlspecialchars($expense['name']) . '</td>';
                        echo '<td>' . htmlspecialchars($expense['amount']) . '</td>';
                        echo '<td>' . htmlspecialchars($expense['type']) . '</td>';
                        echo '<td>
                                <form action="" method="post" class="deleteForm">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($expense['Id']) . '">
                                    <button name="deleteTransaction">Delete</button>
                                </form>
                            </td>';
                        echo '<td>
                                <form action="" method="post" class="updateForm">
                                    <input type="hidden" name="id" value="' . htmlspecialchars($expense['Id']) . '">
                                    <input type="text" name="expenseName" value="' . htmlspecialchars($expense['name']) . '" required>
                                    <input type="number" name="expenseAmount" value="' . htmlspecialchars($expense['amount']) . '" required>
                                    <select name="expenseType" required>
                                        <option value="expense"' . ($expense['type'] === 'expense' ? ' selected' : '') . '>Expense</option>
                                        <option value="credit"' . ($expense['type'] === 'credit' ? ' selected' : '') . '>Credit</option>
                                    </select>
                                    <button name="updateTransaction">Update</button>
                                </form>
                            </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No transactions found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Reset Transactions Button -->
        <form action="" method="post">
            <button name="resetTransactions" type="submit" class="btnReset">Reset Transactions</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.transactionForm');
            const transactionTableBody = document.querySelector('.transactionTable tbody');

            // Handle form submission for adding transactions
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);
                formData.append('addTransaction', true);

                fetch('../views/insertDbData.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    form.reset(); 
                    // Optionally, refresh the transaction list here
                    location.reload();
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
