<?php
/**
 * Load Page Builder Content API
 *
 * This endpoint retrieves the latest saved page content from JSON file.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use GET.'
    ]);
    exit;
}

try {
    // Load configuration
    $config = include '../config.php';

    // Check if latest.json exists
    $latestPath = $config['paths']['pages'] . 'latest.json';

    if (!file_exists($latestPath)) {
        // No saved content yet - return empty but successful response
        echo json_encode([
            'success' => true,
            'message' => 'No saved page found',
            'data' => null
        ]);
        exit;
    }

    // Read the saved content
    $content = file_get_contents($latestPath);

    if ($content === false) {
        throw new Exception('Failed to read saved page');
    }

    // Parse JSON
    $pageData = json_decode($content, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON in saved page: ' . json_last_error_msg());
    }

    // Log successful load
    error_log("Page loaded successfully from: latest.json");

    // Return the page data
    echo json_encode([
        'success' => true,
        'message' => 'Page loaded successfully',
        'data' => $pageData
    ]);

} catch (Exception $e) {
    // Log error
    error_log("Load Page Builder Error: " . $e->getMessage());

    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'data' => null
    ]);
}
