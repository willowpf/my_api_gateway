<?php
$config = require 'config.php';
$api_keys = $config['api_keys'];
$rate_limit = $config['rate_limit'];

$request_path = $_GET['request_path'] ?? '';
$headers = getallheaders();

$api_key = $headers['X-API-Key'] ?? null;
$status_code = 200;

// Auth check
if (!$api_key || !array_key_exists($api_key, $api_keys)) {
    $status_code = 401;
    http_response_code($status_code);
    echo json_encode(['error' => 'Invalid or missing API Key']);
    log_request($api_key, $request_path, $status_code);
    exit;
}

// Rate limit check
$rate_file = __DIR__ . "/ratelimit_data/{$api_key}.json";
$data = file_exists($rate_file) ? json_decode(file_get_contents($rate_file), true) : ['timestamp' => time(), 'count' => 0];
$now = time();

if ($now - $data['timestamp'] > $rate_limit['window']) {
    $data = ['timestamp' => $now, 'count' => 1];
} else {
    if ($data['count'] >= $rate_limit['limit']) {
        $status_code = 429;
        http_response_code($status_code);
        echo json_encode(['error' => 'Rate limit exceeded']);
        log_request($api_key, $request_path, $status_code);
        exit;
    }
    $data['count']++;
}
file_put_contents($rate_file, json_encode($data));

// Routing
ob_start();
switch ($request_path) {
    case 'users':
        include 'services/service_users.php';
        break;
    case 'products':
        include 'services/service_products.php';
        break;
    default:
        $status_code = 404;
        http_response_code($status_code);
        echo json_encode(['error' => 'Invalid endpoint']);
}
$output = ob_get_clean();
echo $output;
log_request($api_key, $request_path, $status_code);

// Logging
function log_request($key, $path, $status) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');
    $log = "[$time] - IP: $ip - API Key: " . ($key ?? 'None') . " - Path: $path - Status: $status" . PHP_EOL;
    file_put_contents(__DIR__ . '/logs/gateway.log', $log, FILE_APPEND);
}
