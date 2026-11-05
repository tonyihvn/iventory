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
    
    echo "===== Fixing All LGA Values with 0 =====\n\n";
    
    // Get ALL facilities with LGA = 0 (regardless of state)
    $facilities = $pdo->query('SELECT * FROM facilities WHERE lga = "0" OR lga = 0 ORDER BY state, id')->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($facilities) . " facilities with LGA = 0\n\n";
    
    $updated = 0;
    $errors = 0;
    
    foreach($facilities as $facility) {
        $id = $facility['id'];
        $state = trim($facility['state']);
        
        // Try to find state in lgaList (case-insensitive comparison)
        $matchedState = null;
        foreach($lgaList as $jsState => $jsLgas) {
            if(strtolower($jsState) === strtolower($state)) {
                $matchedState = $jsState;
                break;
            }
        }
        
        if(!$matchedState) {
            echo "✗ Facility #{$id}: {$facility['facility_name']} - State '{$state}' not found in lga2.js\n";
            $errors++;
            continue;
        }
        
        // Get the first LGA (index 0) which is the default
        if(isset($lgaList[$matchedState][0])) {
            $correctLGA = $lgaList[$matchedState][0];
            
            // Update the facility
            $pdo->prepare('UPDATE facilities SET lga = ? WHERE id = ?')
                ->execute([$correctLGA, $id]);
            
            echo "✓ Facility #{$id}: {$facility['facility_name']} ({$state}) - LGA 0 → '{$correctLGA}'\n";
            $updated++;
        } else {
            echo "✗ Facility #{$id}: {$facility['facility_name']} - Cannot find first LGA for state {$state}\n";
            $errors++;
        }
    }
    
    echo "\n===== Summary =====\n";
    echo "Updated: {$updated}\n";
    echo "Errors: {$errors}\n";
    echo "Total: " . ($updated + $errors) . "\n";
    
    // Final verification
    echo "\n===== Final Verification =====\n";
    $remaining = $pdo->query('SELECT COUNT(*) as count FROM facilities WHERE lga = "0" OR lga = 0')->fetch(PDO::FETCH_ASSOC);
    echo "Facilities still with LGA = 0: " . $remaining['count'] . "\n";
    
    if($remaining['count'] == 0) {
        echo "✓ All LGA values with 0 have been fixed!\n";
    }
    
    // Show sample of updated data
    echo "\n===== Sample of Updated Facilities =====\n";
    $samples = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE state IN ("KATSINA", "RIVERS", "CENTRAL") ORDER BY id LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
    foreach($samples as $row) {
        echo "  #{$row['id']}: {$row['facility_name']} ({$row['state']}/{$row['lga']})\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
