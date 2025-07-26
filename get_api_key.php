<?php
// get_api_key.php
include 'config.php';

// Return the API key as a JSON response
header('Content-Type: application/json');

// Debugging: Log errors if `$openaiApiKey` is not set
if (!isset($openaiApiKey)) {
    echo json_encode(["error" => "API key is not set"]);
    exit;
}

echo json_encode(["apiKey" => $openaiApiKey]);
?>
