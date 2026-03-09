<?php
/**
 * Save Page Builder Content API
 *
 * This endpoint receives the page body content (excluding header/footer)
 * and saves it to a JSON file for later retrieval.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

try {
    // Load configuration
    $config = include '../config.php';

    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Validate input
    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    if (!isset($data['html']) || !isset($data['components'])) {
        throw new Exception('Missing required fields: html and components');
    }

    // Sanitize input (basic sanitization - use HTML Purifier in production)
    $html = $data['html'];
    $css = isset($data['css']) ? $data['css'] : '';
    $components = $data['components'];
    $timestamp = isset($data['timestamp']) ? $data['timestamp'] : date('c');

    // Validate JSON structure
    $componentsCheck = json_decode($components);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid components JSON: ' . json_last_error_msg());
    }

    // Create page data structure
    $pageData = [
        'html' => $html,
        'css' => $css,
        'components' => $components,
        'timestamp' => $timestamp,
        'version' => '1.0',
        'saved_by' => 'system', // Add user info in production
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ];

    // Create pages directory if it doesn't exist
    if (!is_dir($config['paths']['pages'])) {
        if (!mkdir($config['paths']['pages'], 0755, true)) {
            throw new Exception('Failed to create pages directory');
        }
    }

    // Save timestamped version
    $filename = 'page_' . date('Y-m-d_H-i-s') . '.json';
    $filepath = $config['paths']['pages'] . $filename;

    $result = file_put_contents($filepath, json_encode($pageData, JSON_PRETTY_PRINT));

    if ($result === false) {
        throw new Exception('Failed to write file: ' . $filepath);
    }

    // Also save as "latest.json" for easy loading
    $latestPath = $config['paths']['pages'] . 'latest.json';
    file_put_contents($latestPath, json_encode($pageData, JSON_PRETTY_PRINT));

    // Log successful save
    error_log("Page saved successfully: $filename (" . number_format($result) . " bytes)");

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Page saved successfully',
        'filename' => $filename,
        'size' => $result,
        'timestamp' => $timestamp
    ]);

} catch (Exception $e) {
    // Log error
    error_log("Save Page Builder Error: " . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_type' => get_class($e)
    ]);
}
