<?php

session_start();

if(!isset($_SESSION['transactions'])){
    $_SESSION['transactions']=[];
}

$name = '';
$amount = 0;
$type = '';
$totalAmount = 0;
$profit = 0;
$loss = 0;


// Example form handling code to add a new transaction (for reference)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addTransaction'])) {
    $name = $_POST['expenseName'];
    $amount = $_POST['expenseAmount'];
    $type = $_POST['expenseType'];

    $_SESSION['transactions'][] = [
        'expenseName' => $name,
        'expenseAmount' => $amount,
        'expenseType' => $type
    ];
}


foreach($_SESSION['transactions'] as $transaction){
    if($transaction['expenseType']== 'expense'){
        $totalAmount -= $transaction['expenseAmount'];
        $loss += $transaction['expenseAmount'];
    }
    else 
    {
        $totalAmount += $transaction['expenseAmount'];
        $profit += $transaction['expenseAmount'];
    }
}

if(isset($_POST['resetTransactions'])){
    $_SESSION['transactions'] = [];
}

if(isset($_POST['editTransaction'])){
    $index = $_POST['index'];
    $newName = $_POST['newName'];
    $newAmount = $_POST['newAmount'];
    $newType = $_POST['newType'];

    $_SESSION['transactions'][$index] = [
        'expenseName' => $newName,
        'expenseAmount' => $newAmount,
        'expenseType' => $newType
    ];
}

// Handle transaction delete
if (isset($_POST['deleteTransaction'])) {
    $index = $_POST['index'];
    unset($_SESSION['transactions'][$index]);
    $_SESSION['transactions'] = array_values($_SESSION['transactions']); 
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
        <input type="text" name="expenseName" class="inputField" placeholder="Enter your Expense Name here: " />
        <br><br>
        <input type="number" name="expenseAmount" class="inputField" placeholder="Enter amount here: " />
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
            echo "<p class='summaryText'>Total amount: <span class='totalAmount'>".$totalAmount."</span></p>";
            echo "<p class='summaryText'>Overall profit: <span class='profitAmount'>".$profit."</span></p>";
            echo "<p class='summaryText'>Overall loss: <span class='lossAmount'>".$loss."</span></p>";
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['transactions'])): ?>
                <?php foreach($_SESSION['transactions'] as $index => $transaction): ?>
                    <tr class="tableRow">
                        <td><?php echo htmlspecialchars($transaction['expenseName']); ?></td>
                        <td><?php echo ($transaction['expenseType'] === 'expense' ? '-' : '+') . htmlspecialchars($transaction['expenseAmount']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($transaction['expenseType'])); ?></td>
                        <td class="actions">
                            <!-- Toggle Checkbox for Edit Form -->
                            <input type="checkbox" id="editToggle-<?php echo $index; ?>" class="edit-checkbox">
                            <label for="editToggle-<?php echo $index; ?>" class="edit-button">Edit</label>

                            <!-- Edit Form -->
                            <form action="" method="post" class="editForm">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <input type="text" name="newName" value="<?php echo htmlspecialchars($transaction['expenseName']); ?>" class="inputEdit">
                                <input type="number" name="newAmount" value="<?php echo htmlspecialchars($transaction['expenseAmount']); ?>" class="inputEdit">
                                <select name="newType" class="inputSelectEdit">
                                    <option value="expense" <?php if ($transaction['expenseType'] == 'expense') echo 'selected'; ?>>Expense</option>
                                    <option value="credit" <?php if ($transaction['expenseType'] == 'credit') echo 'selected'; ?>>Credit</option>
                                </select>
                                <button type="submit" name="editTransaction" class="btnSecondary">Save</button>
                            </form>

                            <!-- Delete Form -->
                            <form action="" method="post" class="deleteForm">
                                <input type="hidden" name="index" value="<?php echo $index; ?>">
                                <button type="submit" name="deleteTransaction" class="btnDanger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Reset Transactions Button -->
    <form action="" method="post">
        <button name="resetTransactions" type="submit" class="btnReset">Reset Transactions</button>
    </form>
</div>


</body>
</html>