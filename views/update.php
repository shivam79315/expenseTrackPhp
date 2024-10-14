<?php
include('../config/config.php');

if (isset($_POST['updateTransaction'])) {
    $id = $_POST['id'];
    $name = $_POST['expenseName'];
    $amount = $_POST['expenseAmount'];
    $type = $_POST['expenseType'];

    if (!empty($name) && !empty($amount) && !empty($type)) {
        $stmt = $conn->prepare("UPDATE expenselist SET name = :name, amount = :amount, type = :type WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }
}
?>
