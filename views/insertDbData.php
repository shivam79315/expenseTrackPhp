<?php
include("../config/config.php");
if (isset($_POST['addTransaction'])) {
    $name = $_POST['expenseName'];
    $amount = $_POST['expenseAmount'];
    $type = $_POST['expenseType'];

    if (!empty($name) && !empty($amount) && !empty($type)) {
        $stmt = $conn->prepare("INSERT INTO `expenselist` (`name`, `amount`, `type`) VALUES (:name, :amount, :type)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }
}
?>
