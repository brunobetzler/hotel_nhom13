<?php
// Get the origin from the request headers
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Allow the request origin dynamically if it is not empty
// This is more flexible for cloud environments like Cloud Shell
if (!empty($origin)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Required for credentials (cookies/sessions) to be sent
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}
