<?php

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== LGA Correction Results =====\n\n";
    
    // Check remaining LGA 0 values
    $remaining = $pdo->query('SELECT COUNT(*) as count FROM facilities WHERE lga = "0" OR lga = 0')->fetch(PDO::FETCH_ASSOC);
    echo "Facilities still with LGA = 0: " . $remaining['count'] . "\n";
    
    // Check total facilities
    $total = $pdo->query('SELECT COUNT(*) as count FROM facilities')->fetch(PDO::FETCH_ASSOC);
    echo "Total facilities: " . $total['count'] . "\n";
    
    // Count numeric LGAs
    $numeric = $pdo->query('SELECT COUNT(*) as count FROM facilities WHERE lga REGEXP "^[0-9]+$"')->fetch(PDO::FETCH_ASSOC);
    echo "Facilities with numeric LGA values: " . $numeric['count'] . "\n";
    
    // Show states distribution
    echo "\n===== Facilities by State =====\n";
    $states = $pdo->query('SELECT state, COUNT(*) as count FROM facilities GROUP BY state ORDER BY count DESC')->fetchAll(PDO::FETCH_ASSOC);
    foreach($states as $state) {
        $stateName = !empty($state['state']) ? $state['state'] : '(empty)';
        echo "  " . str_pad($stateName, 20) . " : " . str_pad($state['count'], 3) . " facilities\n";
    }
    
    // Show sample of corrected facilities
    echo "\n===== Sample of Corrected Facilities =====\n";
    $samples = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE lga != "0" AND lga != 0 AND state IN ("KATSINA", "RIVERS", "NASARAWA", "FCT") ORDER BY state, id LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);
    foreach($samples as $row) {
        echo "  #{$row['id']}: {$row['facility_name']}\n         State: {$row['state']} | LGA: {$row['lga']}\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
