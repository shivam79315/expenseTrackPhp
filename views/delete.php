<?php
include('../config/config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM expenselist WHERE Id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    // Redirect back to the index page after deletion
    header("Location: ../public/index.php");
    exit();
}
?>
