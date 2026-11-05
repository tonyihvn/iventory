<?php

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== LGA Value Types and Content =====\n\n";
    
    // Sample facilities to check actual values
    $samples = $pdo->query('SELECT id, facility_name, state, lga, CAST(lga AS CHAR) as lga_str FROM facilities LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($samples as $row) {
        echo "ID {$row['id']}: {$row['facility_name']}\n";
        echo "  LGA Value: '{$row['lga']}' | Type: " . gettype($row['lga']) . " | Length: " . strlen($row['lga']) . "\n";
        echo "  State: '{$row['state']}'\n\n";
    }
    
    // Check different types of LGA values
    echo "\n===== LGA Value Distribution =====\n";
    
    // Check for empty
    $empty = $pdo->query("SELECT COUNT(*) as count FROM facilities WHERE lga = '' OR lga IS NULL")->fetch(PDO::FETCH_ASSOC);
    echo "Empty or NULL LGA: " . $empty['count'] . "\n";
    
    // Check for '0'
    $zero_str = $pdo->query("SELECT COUNT(*) as count FROM facilities WHERE lga = '0'")->fetch(PDO::FETCH_ASSOC);
    echo "LGA = '0' (string): " . $zero_str['count'] . "\n";
    
    // Check for 0 (numeric)
    $zero_num = $pdo->query("SELECT COUNT(*) as count FROM facilities WHERE lga = 0")->fetch(PDO::FETCH_ASSOC);
    echo "LGA = 0 (numeric): " . $zero_num['count'] . "\n";
    
    // Check for non-numeric values
    $non_numeric = $pdo->query("SELECT COUNT(*) as count FROM facilities WHERE lga NOT REGEXP '^[0-9]+$' AND lga != '' AND lga IS NOT NULL")->fetch(PDO::FETCH_ASSOC);
    echo "Non-numeric LGA values: " . $non_numeric['count'] . "\n";
    
    // Show a sample of non-numeric values
    echo "\n===== Sample Non-Numeric LGA Values =====\n";
    $non_num_samples = $pdo->query("SELECT DISTINCT state, lga FROM facilities WHERE lga NOT REGEXP '^[0-9]+\$' AND lga != '' AND lga IS NOT NULL ORDER BY state LIMIT 15")->fetchAll(PDO::FETCH_ASSOC);
    foreach($non_num_samples as $row) {
        echo "  {$row['state']}: {$row['lga']}\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
