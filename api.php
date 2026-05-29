<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$dataFile = 'data.json';

// Initialize data layer configuration with empty database arrays if missing
if (!file_exists($dataFile)) {
    $defaultState = [
        "users" => [],
        "officers" => [],
        "incidents" => [],
        "dmv" => [],
        "warrants" => [],
        "ems" => [],
        "probation" => [],
        "bail" => []
    ];
    file_put_contents($dataFile, json_encode($defaultState, JSON_PRETTY_PRINT));
}

// Request routing engine
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo file_get_contents($dataFile);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = file_get_contents("php://input");
    $decoded = json_decode($inputData, true);
    
    if ($decoded) {
        file_put_contents($dataFile, json_encode($decoded, JSON_PRETTY_PRINT));
        echo json_encode(["status" => "success", "message" => "Database sync complete."]);
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Malformed state payload."]);
    }
    exit;
}
?>