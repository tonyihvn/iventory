<?php

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== Remaining Facilities with Numeric LGA =====\n\n";
    
    $remaining = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE lga REGEXP "^[0-9]+$" ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($remaining as $row) {
        echo "ID #{$row['id']}\n";
        echo "  Name: {$row['facility_name']}\n";
        echo "  State: '{$row['state']}'\n";
        echo "  LGA: '{$row['lga']}' (numeric)\n\n";
    }
    
    echo "===== Summary =====\n";
    echo "Total with numeric LGA: " . count($remaining) . "\n";
    echo "Reason: These facilities have invalid state values (not in lga2.js)\n";
    echo "Action: These need manual data entry correction\n";
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
