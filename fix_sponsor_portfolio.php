<?php
// Fix sponsor_portfolio table - add missing sponsorship_category column

require_once 'config/config.php';
require_once 'app/core/DatabaseConnector.php';

try {
    $connector = new DatabaseConnector();
    $db = $connector->conn;
    
    echo "Checking sponsor_portfolio table structure...\n";
    
    // Check if column exists
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='sponsor_portfolio' AND COLUMN_NAME='sponsorship_category' AND TABLE_SCHEMA='eventx_db'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result) {
        echo "✓ Column 'sponsorship_category' already exists.\n";
    } else {
        echo "✗ Column 'sponsorship_category' not found. Adding...\n";
        
        // Add the column
        $addSql = "ALTER TABLE sponsor_portfolio ADD COLUMN sponsorship_category VARCHAR(100) DEFAULT NULL AFTER brand_description";
        $addStmt = $db->prepare($addSql);
        
        if($addStmt->execute()) {
            echo "✓ Column 'sponsorship_category' added successfully!\n";
        } else {
            echo "✗ Failed to add column.\n";
        }
    }
    
    // List all columns in the table
    echo "\nCurrent columns in sponsor_portfolio table:\n";
    $describeSql = "DESC sponsor_portfolio";
    $descStmt = $db->prepare($describeSql);
    $descStmt->execute();
    $columns = $descStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
    echo "\n✓ Fix completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
