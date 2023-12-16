<?php
$host = 'localhost'; // Replace with your database host
$dbname = 'myBase'; // Replace with your database name
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password

try {
    // Creating a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h3>Connected successfully</h3>";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
