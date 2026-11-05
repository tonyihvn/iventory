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

// Create a reverse lookup: LGA name => State
$lgaToState = [];
foreach($lgaList as $state => $lgas) {
    foreach($lgas as $lga) {
        $lgaToState[strtolower($lga)] = $state;
    }
}

$host = '127.0.0.1';
$db = 'iventory_online';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "===== Correcting Facilities with Wrong LGA Values =====\n\n";
    
    // Get all facilities with non-numeric LGA that are not correct
    $facilities = $pdo->query("
        SELECT id, facility_name, state, lga 
        FROM facilities 
        WHERE lga NOT REGEXP '^[0-9]+\$' 
        AND lga != '' 
        AND state IN (SELECT DISTINCT state FROM facilities WHERE state IN ('NASARAWA', 'RIVERS', 'KATSINA', 'FCT'))
        ORDER BY state, id
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $corrected = 0;
    
    foreach($facilities as $facility) {
        $id = $facility['id'];
        $facility_name = $facility['facility_name'];
        $state = trim($facility['state']);
        $current_lga = trim($facility['lga']);
        
        // Try to find state in lgaList (case-insensitive)
        $matchedState = null;
        foreach($lgaList as $jsState => $jsLgas) {
            if(strtolower($jsState) === strtolower($state)) {
                $matchedState = $jsState;
                break;
            }
        }
        
        if(!$matchedState) {
            continue;
        }
        
        // Try to extract LGA name from facility name
        $correctLGA = null;
        $facility_lower = strtolower($facility_name);
        
        // Search for LGA names in facility name
        foreach($lgaList[$matchedState] as $lga) {
            $lga_lower = strtolower($lga);
            // Look for LGA name as a complete word in facility name
            if(stripos($facility_name, $lga) !== false || 
               preg_match('/\b' . preg_quote($lga_lower) . '\b/i', $facility_name)) {
                $correctLGA = $lga;
                break;
            }
        }
        
        // If found a better match, update it
        if($correctLGA && $correctLGA !== $current_lga) {
            $pdo->prepare('UPDATE facilities SET lga = ? WHERE id = ?')
                ->execute([$correctLGA, $id]);
            echo "✓ Facility #{$id}: {$facility_name}\n  State: {$state} | Old LGA: {$current_lga} → New LGA: {$correctLGA}\n";
            $corrected++;
        }
    }
    
    echo "\n===== Summary =====\n";
    echo "Facilities corrected: {$corrected}\n";
    
    // Show distribution
    echo "\n===== Current LGA Distribution by State =====\n";
    $dist = $pdo->query("
        SELECT state, COUNT(DISTINCT lga) as lga_count, COUNT(*) as facility_count
        FROM facilities 
        WHERE state IN ('NASARAWA', 'RIVERS', 'KATSINA', 'FCT')
        GROUP BY state 
        ORDER BY facility_count DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach($dist as $row) {
        echo "  {$row['state']}: {$row['facility_count']} facilities across {$row['lga_count']} LGAs\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
