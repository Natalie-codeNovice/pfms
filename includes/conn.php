<?php
// Retrieve environment variables
$dbName = getenv('MYSQL_ADDON_DB');
$dbHost = getenv('MYSQL_ADDON_HOST');
$dbPort = getenv('MYSQL_ADDON_PORT');
$dbUser = getenv('MYSQL_ADDON_USER');
$dbPassword = getenv('MYSQL_ADDON_PASSWORD');

try {
    // Create a new PDO instance
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
    $pdo = new PDO($dsn, $dbUser, $dbPassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
