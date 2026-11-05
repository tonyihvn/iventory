<?php

// Read and parse lga2.js to extract all state and LGA data
$content = file_get_contents('js/lga2.js');

// Extract the LGA data from JavaScript
// The data is in: let lgaList = { ... }
$startMarker = 'let lgaList = {';
$start = strpos($content, $startMarker) + strlen($startMarker);
$end = strrpos($content, '};') + 1;
$jsonStr = '{' . substr($content, $start, $end - $start - 1) . '}';

// Convert JavaScript object to PHP array format
// First, fix the JavaScript object to be valid JSON
$jsonStr = preg_replace('/([a-zA-Z0-9_]+):/i', '"$1":', $jsonStr);
$jsonStr = preg_replace('/,\s*}/', '}', $jsonStr);
$jsonStr = preg_replace('/,\s*]/', ']', $jsonStr);

// Decode as JSON
$lgaList = json_decode($jsonStr, true);

if($lgaList) {
    echo "States and LGA counts in lga2.js:\n";
    foreach($lgaList as $state => $lgas) {
        echo "  '{$state}' => " . count($lgas) . " LGAs\n";
    }
} else {
    echo "Failed to parse. Trying alternative method...\n";
    // Alternative: Extract using regex
    preg_match_all('/(\w+)\s*:\s*\[([\s\S]*?)\](?=\s*,?\s*(\w+\s*:|}))/i', $content, $matches);
    
    echo "States found:\n";
    for($i = 0; $i < count($matches[1]); $i++) {
        $state = $matches[1][$i];
        $lgas_text = $matches[2][$i];
        // Count LGA entries by counting quoted strings
        preg_match_all('/"([^"]+)"/', $lgas_text, $lgas);
        echo "  '{$state}' => " . count($lgas[1]) . " LGAs\n";
    }
}
