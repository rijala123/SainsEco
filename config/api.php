<?php
/**
 * API & Hosting Configuration File
 * Configure API base URL, secrets, and JSON response helpers for local or remote deployment.
 */

// Host auto-detection or environment override
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'] ?? 'localhost/gamelingkungan';
$defaultBaseUrl = $protocol . $host;

define('API_BASE_URL', getenv('API_BASE_URL') ?: $defaultBaseUrl);
define('API_KEY', getenv('API_KEY') ?: 'ECO_GAME_SECRET_TOKEN_2026');
define('APP_NAME', 'Enhanced Eco Clean Game');
define('APP_VERSION', '1.0.0');

/**
 * Send JSON Response
 */
function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Access-Key');
    
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($requestMethod === 'OPTIONS') {
        exit(0);
    }
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

/**
 * Handle CORS Preflight
 */
function handleCorsHeaders() {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Access-Key');
    $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    if ($requestMethod === 'OPTIONS') {
        http_response_code(200);
        exit(0);
    }
}
