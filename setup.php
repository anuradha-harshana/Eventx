<?php
$host = 'db';
$user = 'root';
$pass = '';
$filename = 'schema.sql';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = file_get_contents($filename);
    $pdo->exec($sql);

    echo "Database and tables created successfully!";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
