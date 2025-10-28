<?php
// Enable all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Simple test that should always work
$response = [
    "status" => "success",
    "message" => "Simple test is working!",
    "server_time" => date("Y-m-d H:i:s"),
    "php_version" => phpversion(),
    "received_data" => [
        "method" => $_SERVER['REQUEST_METHOD'],
        "post_data" => $_POST,
        "json_input" => file_get_contents('php://input')
    ]
];

echo json_encode($response, JSON_PRETTY_PRINT);
?>