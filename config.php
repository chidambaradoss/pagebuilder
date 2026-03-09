<?php
/**
 * Page Builder Configuration File
 *
 * This file contains all configuration settings for the GrapesJS page builder
 * including Bootstrap version, CDN URLs, file paths, and upload settings.
 */

return [
    // Bootstrap version to use (4 or 5)
    'bootstrap_version' => '4',

    // CDN URLs for libraries
    'cdn' => [
        // Bootstrap 4
        'bootstrap4_css' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css',
        'bootstrap4_js' => 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js',

        // Bootstrap 5
        'bootstrap5_css' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        'bootstrap5_js' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',

        // GrapesJS
        'grapesjs_css' => 'https://unpkg.com/grapesjs/dist/css/grapes.min.css',
        'grapesjs_js' => 'https://unpkg.com/grapesjs/dist/grapes.min.js',

        // GrapesJS Plugins
        'grapesjs_blocks_basic' => 'https://unpkg.com/grapesjs-blocks-basic',

        // Font Awesome
        'fontawesome_css' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
    ],

    // File system paths
    'paths' => [
        'uploads' => __DIR__ . '/uploads/images/',
        'thumbnails' => __DIR__ . '/uploads/thumbnails/',
        'pages' => __DIR__ . '/pages/',
        'uploads_url' => 'uploads/images/',
        'thumbnails_url' => 'uploads/thumbnails/',
    ],

    // Upload settings
    'upload' => [
        'max_size' => 5242880, // 5MB in bytes
        'allowed_types' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'thumbnail_width' => 300,
        'thumbnail_height' => 300,
    ],

    // Editor settings
    'editor' => [
        'autosave' => true,
        'autosave_interval' => 30, // seconds
        'show_layer_panel' => true,
        'show_style_manager' => true,
        'show_traits_panel' => true,
    ],

    // Security settings
    'security' => [
        'enable_authentication' => true, // Set to true in production
        'csrf_protection' => true,
        'sanitize_html' => true,
    ],
];
