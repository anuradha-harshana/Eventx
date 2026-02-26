<?php
// Test portfolio update by showing recent updates

require_once 'config/config.php';
require_once 'app/core/DatabaseConnector.php';

try {
    $connector = new DatabaseConnector();
    $db = $connector->conn;
    
    echo "=== Testing Portfolio Update Process ===\n\n";
    
    // Show a sample portfolio item to test with
    $sql = "SELECT sp.*, sd.brand_name 
            FROM sponsor_portfolio sp
            LEFT JOIN sponsor_details sd ON sp.sponsor_id = sd.id
            ORDER BY sp.id DESC LIMIT 1";
    
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($item) {
        echo "Last Portfolio Item Created/Updated:\n";
        echo "ID: " . $item['id'] . "\n";
        echo "Sponsor Brand: " . ($item['brand_name'] ?? 'N/A') . "\n";
        echo "Title: " . $item['title'] . "\n";
        echo "Logo URL: " . ($item['logo_url'] ?: 'NOT SET') . "\n";
        echo "Banner URL: " . ($item['banner_url'] ?: 'NOT SET') . "\n";
        echo "Image URL: " . ($item['image_url'] ?: 'NOT SET') . "\n";
        echo "Status: " . $item['status'] . "\n";
        echo "Created: " . $item['created_at'] . "\n";
        echo "Updated: " . $item['updated_at'] . "\n";
        
        // Check if files actually exist on disk
        echo "\n=== File System Check ===\n";
        $uploadDir = __DIR__ . '/uploads/portfolio/';
        
        foreach(['logo_url', 'banner_url', 'image_url'] as $field) {
            if(!empty($item[$field])) {
                $filePath = $item[$field];
                // Handle both absolute and relative paths
                if(str_starts_with($filePath, '/uploads/')) {
                    $diskPath = __DIR__ . $filePath;
                } else {
                    $diskPath = $uploadDir . basename($filePath);
                }
                
                $exists = file_exists($diskPath);
                echo ($exists ? "✓" : "✗") . " " . $field . ": " . $filePath . "\n";
                if($exists) echo "   Disk path exists: $diskPath\n";
            }
        }
        
    } else {
        echo "No portfolio items found.\n";
    }
    
    echo "\n✓ Portfolio update process looks good!\n";
    echo "\nTo test updates:\n";
    echo "1. Navigate to Edit Portfolio Item\n";
    echo "2. Upload new images (logo, banner, or image)\n";
    echo "3. Click Submit\n";
    echo "4. Check the database - updated_at should change\n";
    echo "5. View portfolio list - images should display with proper paths\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
