<?php
include('../config/config.php');

// Function to fetch and display the expense list
function fetchExpenseList($conn) {
    $fetchExpenses = $conn->prepare("SELECT * FROM expenselist");
    $fetchExpenses->execute();
    $expenses = $fetchExpenses->fetchAll(PDO::FETCH_ASSOC);

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
                    <form action="" method="post">
                        <input type="hidden" name="id" value="' . htmlspecialchars($expense['Id']) . '">
                        <input type="text" name="expenseName" placeholder="Edit Name" required>
                        <input type="number" name="expenseAmount" placeholder="Edit Amount" required>
                        <select name="expenseType" required>
                            <option value="expense"' . ($expense['type'] === 'expense' ? ' selected' : '') . '>Expense</option>
                            <option value="credit"' . ($expense['type'] === 'credit' ? ' selected' : '') . '>Credit</option>
                        </select>
                        <button name="Submit">Update</button>
                    </form>
                </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">No transactions found.</td></tr>';
    }
}
?>
