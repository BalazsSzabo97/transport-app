<?php

$host = '127.0.0.1';
$port = '3306';
$username = 'root';
$password = '';
$database = 'transport_db';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$database' created or already exists.\n";

    // Run migrations & seeders
    exec('php artisan migrate:fresh --seed', $output, $return_var);
    if ($return_var === 0) {
        echo "Tables migrated and seeded successfully.\n";
    } else {
        echo "Migration failed.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
