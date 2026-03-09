<?php
/**
 * Media Files API
 *
 * This endpoint returns a list of all uploaded media files with their
 * metadata (thumbnails, sizes, dimensions, etc.)
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

    $uploadsDir = $config['paths']['uploads'];
    $thumbnailsDir = $config['paths']['thumbnails'];
    $uploadsUrl = $config['paths']['uploads_url'];
    $thumbnailsUrl = $config['paths']['thumbnails_url'];

    // Create directories if they don't exist
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }
    if (!is_dir($thumbnailsDir)) {
        mkdir($thumbnailsDir, 0755, true);
    }

    // Check if uploads directory exists after creation attempt
    if (!is_dir($uploadsDir)) {
        echo json_encode([
            'success' => true,
            'files' => [],
            'count' => 0,
            'message' => 'Uploads directory created but empty',
            'debug' => [
                'uploadsDir' => $uploadsDir,
                'uploadsUrl' => $uploadsUrl
            ]
        ]);
        exit;
    }

    $files = [];
    $scan = scandir($uploadsDir);

    foreach ($scan as $file) {
        if ($file === '.' || $file === '..') continue;

        $filePath = $uploadsDir . $file;

        // Skip directories
        if (!is_file($filePath)) continue;

        // Check if it's an image
        $imageInfo = @getimagesize($filePath);
        if ($imageInfo === false) continue;

        // Get file stats
        $fileSize = filesize($filePath);
        $fileTime = filemtime($filePath);

        // Check if thumbnail exists
        $thumbnailPath = $thumbnailsDir . $file;
        $thumbnailUrl = file_exists($thumbnailPath)
            ? $thumbnailsUrl . $file
            : $uploadsUrl . $file;

        // Add file info
        $files[] = [
            'id' => md5($file),
            'name' => $file,
            'original_name' => $file,
            'url' => $uploadsUrl . $file,
            'thumbnail' => $thumbnailUrl,
            'size' => formatFileSize($fileSize),
            'size_bytes' => $fileSize,
            'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1],
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'type' => $imageInfo['mime'],
            'uploaded' => date('Y-m-d H:i:s', $fileTime),
            'timestamp' => $fileTime
        ];
    }

    // Sort by upload date (newest first)
    usort($files, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });

    error_log("Media files loaded: " . count($files) . " files found");

    // Return files
    echo json_encode([
        'success' => true,
        'files' => $files,
        'count' => count($files),
        'message' => count($files) . ' media file(s) found'
    ]);

} catch (Exception $e) {
    error_log("Media Files Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'files' => [],
        'count' => 0
    ]);
}

/**
 * Format file size
 */
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}
