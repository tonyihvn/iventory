<?php

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check facilities table structure
    echo "===== Facilities Table Structure =====\n";
    $result = $pdo->query('DESCRIBE facilities')->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $col) {
        echo $col['Field'] . " | " . $col['Type'] . "\n";
    }
    
    // Check sample data
    echo "\n===== Sample Facilities Data (First 10) =====\n";
    $data = $pdo->query('SELECT id, facility_name, state, lga FROM facilities LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $row) {
        echo "ID: {$row['id']} | Name: {$row['facility_name']} | State: {$row['state']} | LGA: {$row['lga']}\n";
    }
    
    // Count total records
    echo "\n===== Total Facilities =====\n";
    $count = $pdo->query('SELECT COUNT(*) as total FROM facilities')->fetch(PDO::FETCH_ASSOC);
    echo "Total: " . $count['total'] . "\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
