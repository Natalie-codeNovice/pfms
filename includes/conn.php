<?php
// Retrieve environment variables
$dbHost = getenv('MYSQL_ADDON_HOST') ?: 'localhost';
$dbName = getenv('MYSQL_ADDON_DB') ?: 'finance';
$dbUser = getenv('MYSQL_ADDON_USER') ?: 'root';
$dbPassword = getenv('MYSQL_ADDON_PASSWORD') ?: '';

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$dbHost;dbname=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
