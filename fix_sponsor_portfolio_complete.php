<?php
// Complete sponsor_portfolio table schema fix

require_once 'config/config.php';
require_once 'app/core/DatabaseConnector.php';

try {
    $connector = new DatabaseConnector();
    $db = $connector->conn;
    
    echo "Fixing sponsor_portfolio table schema...\n\n";
    
    // List columns needed according to schema
    $requiredColumns = [
        'sponsor_id' => 'int',
        'title' => 'varchar',
        'brand_description' => 'text',
        'sponsorship_category' => 'varchar',
        'past_collaboration' => 'varchar',
        'logo_url' => 'varchar',
        'banner_url' => 'varchar',
        'image_url' => 'varchar',
        'event_name' => 'varchar',
        'year' => 'year'
    ];
    
    // Get current columns
    $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='sponsor_portfolio' AND TABLE_SCHEMA='eventx_db'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $currentColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Current columns: " . implode(', ', $currentColumns) . "\n\n";
    
    // Add missing columns
    $missingColumns = array_diff(array_keys($requiredColumns), $currentColumns);
    
    if(!empty($missingColumns)) {
        echo "Adding missing columns:\n";
        foreach($missingColumns as $col) {
            $addSql = "ALTER TABLE sponsor_portfolio ADD COLUMN $col VARCHAR(255) DEFAULT NULL";
            
            // Special cases for different column types
            if($col === 'brand_description') $addSql = "ALTER TABLE sponsor_portfolio ADD COLUMN $col TEXT DEFAULT NULL";
            if($col === 'year') $addSql = "ALTER TABLE sponsor_portfolio ADD COLUMN $col YEAR DEFAULT NULL";
            
            try {
                $db->prepare($addSql)->execute();
                echo "  ✓ Added '$col'\n";
            } catch(Exception $e) {
                echo "  ✗ Failed to add '$col': " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "No missing columns found!\n";
    }
    
    // List final columns
    echo "\nFinal columns in sponsor_portfolio table:\n";
    $describeSql = "DESC sponsor_portfolio";
    $descStmt = $db->prepare($describeSql);
    $descStmt->execute();
    $columns = $descStmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
    echo "\n✓ Schema fix completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
