<?php
global $pdo;
include($_SERVER["DOCUMENT_ROOT"] . "/config/connection_database.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['id'];

    // Error handling and validation
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
    $stmt->bindParam(':id', $categoryId);

    if ($stmt->execute()) {
        echo "Category deleted successfully";
    } else {
        echo "Error deleting category";
    }
}
?>