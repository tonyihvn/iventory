<?php

// Read and parse lga2.js to extract all state and LGA data
$content = file_get_contents('js/lga2.js');

// Extract the LGA data from JavaScript
$startMarker = 'let lgaList = {';
$start = strpos($content, $startMarker) + strlen($startMarker);
$end = strrpos($content, '};');
$jsObjectStr = substr($content, $start, $end - $start);

// Build PHP array by extracting state and LGA arrays
$lgaList = [];
preg_match_all('/(\w+)\s*:\s*\[([\s\S]*?)\]/', $jsObjectStr, $matches);

for($i = 0; $i < count($matches[1]); $i++) {
    $state = $matches[1][$i];
    $lgasText = $matches[2][$i];
    
    // Extract LGA names from quoted strings
    preg_match_all('/"([^"]*)"/', $lgasText, $lgaMatches);
    $lgas = $lgaMatches[1];
    
    $lgaList[$state] = $lgas;
}

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== Fixing Remaining LGA Data =====\n\n";
    
    // Define manual fixes for remaining facilities
    $fixes = [
        345 => ['state' => 'Rivers', 'lga_index' => 0],
        350 => ['state' => 'Katsina', 'lga_index' => 0],
        375 => ['state' => 'Katsina', 'lga_index' => 0],
        383 => ['state' => null, 'lga' => 'Abuja (Warehouse)'], // Manual entry
        384 => ['state' => 'CENTRAL', 'lga_index' => 0],
    ];
    
    $updated = 0;
    
    foreach($fixes as $id => $fix) {
        $facility = $pdo->query("SELECT * FROM facilities WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
        
        if(!$facility) {
            echo "✗ Facility #{$id} not found\n";
            continue;
        }
        
        if($fix['state'] === null) {
            // Manual entry for warehouse
            $pdo->prepare('UPDATE facilities SET state = ?, lga = ? WHERE id = ?')
                ->execute(['Abuja', $fix['lga'], $id]);
            echo "✓ Facility #{$id}: {$facility['facility_name']} - Manual update: state='Abuja', lga='{$fix['lga']}'\n";
            $updated++;
        } else {
            // Try to find state and get LGA
            $matchedState = null;
            foreach($lgaList as $jsState => $jsLgas) {
                if(strtolower($jsState) === strtolower($fix['state'])) {
                    $matchedState = $jsState;
                    break;
                }
            }
            
            if($matchedState && isset($lgaList[$matchedState][$fix['lga_index']])) {
                $correctLGA = $lgaList[$matchedState][$fix['lga_index']];
                $pdo->prepare('UPDATE facilities SET lga = ? WHERE id = ?')
                    ->execute([$correctLGA, $id]);
                echo "✓ Facility #{$id}: {$facility['facility_name']} - LGA 0 → '{$correctLGA}'\n";
                $updated++;
            } else {
                echo "✗ Facility #{$id}: {$facility['facility_name']} - Cannot find state '{$fix['state']}' in lga2.js\n";
            }
        }
    }
    
    echo "\n===== Summary =====\n";
    echo "Updated: {$updated}\n";
    echo "Total fixed in this batch: " . count($fixes) . "\n";
    
    // Final verification
    echo "\n===== Final Verification =====\n";
    $remaining = $pdo->query('SELECT COUNT(*) as count FROM facilities WHERE lga REGEXP "^[0-9]+$"')->fetch(PDO::FETCH_ASSOC);
    echo "Facilities still with numeric LGA: " . $remaining['count'] . "\n";
    
    if($remaining['count'] == 0) {
        echo "✓ SUCCESS: All LGA data has been corrected!\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
