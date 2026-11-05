<?php

// Read and parse lga2.js to extract all state and LGA data
$content = file_get_contents('js/lga2.js');

// Extract the LGA data from JavaScript
// The data is in: let lgaList = { ... };
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

echo "✓ Loaded " . count($lgaList) . " states from lga2.js\n\n";

// Now use this data to update the facilities table
$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== Fixing LGA Data in Facilities Table =====\n\n";
    
    // Get all facilities that have numeric LGA values
    $facilities = $pdo->query('SELECT * FROM facilities WHERE lga REGEXP "^[0-9]+$"')->fetchAll(PDO::FETCH_ASSOC);
    
    $updated = 0;
    $errors = 0;
    
    foreach($facilities as $facility) {
        $id = $facility['id'];
        $state = trim($facility['state']);
        $lga = trim($facility['lga']);
        
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
        
        // Check if LGA is numeric
        if(is_numeric($lga) && !empty($lga)) {
            $lgaIndex = intval($lga);
            
            // Get the correct LGA name from the list
            if(isset($lgaList[$matchedState][$lgaIndex])) {
                $correctLGA = $lgaList[$matchedState][$lgaIndex];
                
                // Update the facility
                $stmt = $pdo->prepare('UPDATE facilities SET lga = ? WHERE id = ?');
                $stmt->execute([$correctLGA, $id]);
                
                echo "✓ Facility #{$id}: {$facility['facility_name']} - LGA '{$lga}' → '{$correctLGA}'\n";
                $updated++;
            } else {
                echo "✗ Facility #{$id}: {$facility['facility_name']} - Invalid LGA index {$lga} for {$state} (has " . count($lgaList[$matchedState]) . " LGAs)\n";
                $errors++;
            }
        }
    }
    
    echo "\n===== Summary =====\n";
    echo "Updated: {$updated}\n";
    echo "Errors: {$errors}\n";
    
    // Show sample of updated data
    if($updated > 0) {
        echo "\n===== Sample Updated Facilities =====\n";
        $samples = $pdo->query('SELECT id, facility_name, state, lga FROM facilities WHERE lga NOT REGEXP "^[0-9]" LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
        foreach($samples as $row) {
            echo "  #{$row['id']}: {$row['facility_name']} ({$row['state']}/{$row['lga']})\n";
        }
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString();
}
