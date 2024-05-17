<?php
// Check if the product ID is provided
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Create a new PDO connection
    $host = 'localhost';
    $dbname = 'shoping';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the SQL query to remove the product
        $stmt = $pdo->prepare("DELETE FROM sp_product WHERE id = ?");
        $stmt->execute([$productId]);

        echo 'Product removed successfully.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    // Close the database connection
    $pdo = null;
} else {
    echo 'Product ID is missing.';
}
?>
