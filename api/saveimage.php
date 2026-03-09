<?php
/**
 * Save Image API with Thumbnail Generation
 *
 * This endpoint handles image uploads, validates files, generates thumbnails,
 * and returns the uploaded file information.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

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

    // Check if files were uploaded
    if (!isset($_FILES['images']) || empty($_FILES['images']['name'])) {
        throw new Exception('No files uploaded');
    }

    $uploadedFiles = [];
    $errors = [];

    // Handle multiple files
    $fileCount = is_array($_FILES['images']['name']) ? count($_FILES['images']['name']) : 1;

    for ($i = 0; $i < $fileCount; $i++) {
        // Get file info
        if (is_array($_FILES['images']['name'])) {
            $fileName = $_FILES['images']['name'][$i];
            $fileTmpName = $_FILES['images']['tmp_name'][$i];
            $fileSize = $_FILES['images']['size'][$i];
            $fileError = $_FILES['images']['error'][$i];
            $fileType = $_FILES['images']['type'][$i];
        } else {
            $fileName = $_FILES['images']['name'];
            $fileTmpName = $_FILES['images']['tmp_name'];
            $fileSize = $_FILES['images']['size'];
            $fileError = $_FILES['images']['error'];
            $fileType = $_FILES['images']['type'];
        }

        // Check for upload errors
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = "Error uploading $fileName: " . getUploadErrorMessage($fileError);
            continue;
        }

        // Validate file type
        if (!in_array($fileType, $config['upload']['allowed_types'])) {
            $errors[] = "Invalid file type for $fileName. Allowed: jpg, png, gif, webp";
            continue;
        }

        // Validate file extension
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (!in_array($fileExt, $config['upload']['allowed_extensions'])) {
            $errors[] = "Invalid file extension for $fileName";
            continue;
        }

        // Validate file size
        if ($fileSize > $config['upload']['max_size']) {
            $maxSizeMB = round($config['upload']['max_size'] / 1048576, 2);
            $errors[] = "$fileName exceeds maximum size of {$maxSizeMB}MB";
            continue;
        }

        // Validate actual image
        $imageInfo = @getimagesize($fileTmpName);
        if ($imageInfo === false) {
            $errors[] = "$fileName is not a valid image";
            continue;
        }

        // Generate unique filename
        $uniqueName = uniqid('img_', true) . '.' . $fileExt;

        // Create directories if they don't exist
        if (!is_dir($config['paths']['uploads'])) {
            mkdir($config['paths']['uploads'], 0755, true);
        }
        if (!is_dir($config['paths']['thumbnails'])) {
            mkdir($config['paths']['thumbnails'], 0755, true);
        }

        // Move uploaded file
        $uploadPath = $config['paths']['uploads'] . $uniqueName;
        if (!move_uploaded_file($fileTmpName, $uploadPath)) {
            $errors[] = "Failed to save $fileName";
            continue;
        }

        // Generate thumbnail
        $thumbnailPath = $config['paths']['thumbnails'] . $uniqueName;
        try {
            generateThumbnail(
                $uploadPath,
                $thumbnailPath,
                $config['upload']['thumbnail_width'],
                $config['upload']['thumbnail_height']
            );
        } catch (Exception $e) {
            // Thumbnail generation failed, but file is uploaded
            error_log("Thumbnail generation failed for $uniqueName: " . $e->getMessage());
        }

        // Add to uploaded files list
        $uploadedFiles[] = [
            'id' => uniqid(),
            'name' => $fileName,
            'filename' => $uniqueName,
            'url' => $config['paths']['uploads_url'] . $uniqueName,
            'thumbnail' => $config['paths']['thumbnails_url'] . $uniqueName,
            'size' => formatFileSize($fileSize),
            'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1],
            'type' => $fileType,
            'uploaded' => date('Y-m-d H:i:s')
        ];

        error_log("Image uploaded successfully: $uniqueName");
    }

    // Return response
    $response = [
        'success' => count($uploadedFiles) > 0,
        'files' => $uploadedFiles,
        'errors' => $errors,
        'message' => count($uploadedFiles) . ' file(s) uploaded successfully'
    ];

    if (count($uploadedFiles) === 0 && count($errors) > 0) {
        $response['message'] = 'Upload failed';
        http_response_code(400);
    }

    echo json_encode($response);

} catch (Exception $e) {
    error_log("Save Image Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'files' => [],
        'errors' => [$e->getMessage()]
    ]);
}

/**
 * Generate thumbnail from image
 */
function generateThumbnail($sourcePath, $destPath, $thumbWidth, $thumbHeight) {
    $imageInfo = getimagesize($sourcePath);
    if ($imageInfo === false) {
        throw new Exception('Invalid image file');
    }

    $sourceWidth = $imageInfo[0];
    $sourceHeight = $imageInfo[1];
    $mimeType = $imageInfo['mime'];

    // Create source image resource
    switch ($mimeType) {
        case 'image/jpeg':
        case 'image/jpg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            throw new Exception('Unsupported image type: ' . $mimeType);
    }

    if ($sourceImage === false) {
        throw new Exception('Failed to create image resource');
    }

    // Calculate thumbnail dimensions (maintain aspect ratio)
    $ratio = min($thumbWidth / $sourceWidth, $thumbHeight / $sourceHeight);
    $newWidth = round($sourceWidth * $ratio);
    $newHeight = round($sourceHeight * $ratio);

    // Create thumbnail
    $thumbnail = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG and GIF
    if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
        imagealphablending($thumbnail, false);
        imagesavealpha($thumbnail, true);
        $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
        imagefilledrectangle($thumbnail, 0, 0, $newWidth, $newHeight, $transparent);
    }

    // Resize with high quality
    imagecopyresampled(
        $thumbnail, $sourceImage,
        0, 0, 0, 0,
        $newWidth, $newHeight,
        $sourceWidth, $sourceHeight
    );

    // Save thumbnail
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
        case 'image/jpg':
            $result = imagejpeg($thumbnail, $destPath, 85);
            break;
        case 'image/png':
            $result = imagepng($thumbnail, $destPath, 8);
            break;
        case 'image/gif':
            $result = imagegif($thumbnail, $destPath);
            break;
        case 'image/webp':
            $result = imagewebp($thumbnail, $destPath, 85);
            break;
    }

    // Clean up
    imagedestroy($sourceImage);
    imagedestroy($thumbnail);

    if (!$result) {
        throw new Exception('Failed to save thumbnail');
    }

    return true;
}

/**
 * Get upload error message
 */
function getUploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
    ];
    return $errors[$errorCode] ?? 'Unknown upload error';
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
