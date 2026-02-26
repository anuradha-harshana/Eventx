<?php
// Test script to verify portfolio pictures in database

require_once 'config/config.php';
require_once 'app/core/DatabaseConnector.php';

try {
    $connector = new DatabaseConnector();
    $db = $connector->conn;
    
    echo "=== Portfolio Pictures Database Status ===\n\n";
    
    // Get all portfolio items with their image information
    $sql = "SELECT id, sponsor_id, title, logo_url, banner_url, image_url, updated_at 
            FROM sponsor_portfolio 
            ORDER BY updated_at DESC LIMIT 10";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if(empty($items)) {
        echo "No portfolio items found in database.\n";
    } else {
        echo "Recent Portfolio Items:\n";
        echo str_repeat("-", 120) . "\n";
        
        foreach($items as $item) {
            echo "\nID: {$item['id']} | Title: {$item['title']}\n";
            echo "Logo: " . ($item['logo_url'] ? "✓ " . substr($item['logo_url'], 0, 60) : "✗ Not set") . "\n";
            echo "Banner: " . ($item['banner_url'] ? "✓ " . substr($item['banner_url'], 0, 60) : "✗ Not set") . "\n";
            echo "Image: " . ($item['image_url'] ? "✓ " . substr($item['image_url'], 0, 60) : "✗ Not set") . "\n";
            echo "Updated: {$item['updated_at']}\n";
        }
    }
    
    echo "\n\n=== Database Schema Check ===\n";
    $descSql = "DESC sponsor_portfolio";
    $descStmt = $db->prepare($descSql);
    $descStmt->execute();
    $columns = $descStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $imageColumns = ['logo_url', 'banner_url', 'image_url', 'image_url'];
    foreach($columns as $col) {
        if(in_array($col['Field'], $imageColumns)) {
            echo "✓ {$col['Field']} ({$col['Type']})\n";
        }
    }
    
    echo "\n✓ Portfolio Picture Status: OK\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
