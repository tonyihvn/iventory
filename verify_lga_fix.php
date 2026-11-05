<?php

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== Verification Results =====\n\n";
    
    // Check if any numeric LGA values still remain
    $result = $pdo->query('SELECT COUNT(*) as count FROM facilities WHERE lga REGEXP "^[0-9]+$"')->fetch(PDO::FETCH_ASSOC);
    echo "Facilities still with numeric LGA: " . $result['count'] . "\n";
    
    // Show the problematic facility
    echo "\n===== Problem Facility =====\n";
    $problem = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE id = 383')->fetch(PDO::FETCH_ASSOC);
    echo "ID: " . $problem['id'] . "\n";
    echo "Name: " . $problem['facility_name'] . "\n";
    echo "State: " . $problem['state'] . " (INVALID - not a valid state)\n";
    echo "LGA: " . $problem['lga'] . "\n";
    
    // Show total facilities
    echo "\n===== Database Summary =====\n";
    $total = $pdo->query('SELECT COUNT(*) as count FROM facilities')->fetch(PDO::FETCH_ASSOC);
    echo "Total facilities: " . $total['count'] . "\n";
    
    // Count by state
    echo "\n===== Facilities by State (Top 10) =====\n";
    $states = $pdo->query('SELECT state, COUNT(*) as count FROM facilities GROUP BY state ORDER BY count DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
    foreach($states as $state) {
        echo "  " . $state['state'] . ": " . $state['count'] . " facilities\n";
    }
    
    // Show sample of updated facilities
    echo "\n===== Sample of Updated Facilities =====\n";
    $samples = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE lga NOT REGEXP "^[0-9]" AND lga != "" ORDER BY id LIMIT 15')->fetchAll(PDO::FETCH_ASSOC);
    foreach($samples as $row) {
        echo "  #{$row['id']}: {$row['facility_name']} ({$row['state']}/{$row['lga']})\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
