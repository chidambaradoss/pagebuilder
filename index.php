<?php
/**
 * Page Builder - Fixed Main Interface
 */

// Load configuration
$config = include 'config.php';

// Get Bootstrap version from config
$bootstrap_version = $config['bootstrap_version'];
$bootstrap_css = $config['cdn']['bootstrap' . $bootstrap_version . '_css'];
$bootstrap_js = $config['cdn']['bootstrap' . $bootstrap_version . '_js'];

// Define header and footer content (non-editable sections)
$header_content = '
<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My Website</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
';

$footer_content = '
<footer class="site-footer bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>About Us</h5>
                <p>Building amazing websites with our powerful page builder.</p>
            </div>
            <div class="col-md-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Home</a></li>
                    <li><a href="#" class="text-white">About</a></li>
                    <li><a href="#" class="text-white">Services</a></li>
                    <li><a href="#" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Follow Us</h5>
                <div class="social-links">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
        <hr class="bg-white">
        <div class="text-center">
            <p class="mb-0">&copy; 2024 My Website. All rights reserved.</p>
        </div>
    </div>
</footer>
';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Builder - GrapesJS</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo $bootstrap_css; ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $config['cdn']['fontawesome_css']; ?>">

    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- GrapesJS CSS -->
    <link rel="stylesheet" href="<?php echo $config['cdn']['grapesjs_css']; ?>">

    <!-- Custom Editor CSS -->
    <link rel="stylesheet" href="assets/css/editor.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        /* ========================================
           Enhanced Color Picker Positioning
           ======================================== */
        
        /* Ensure color picker dialog appears centered */
        input[type="color"] {
            cursor: pointer !important;
        }

        /* Force color picker popup to be positioned better */
        .gjs-sm-property input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
            border: none;
        }

        .gjs-sm-property input[type="color"]::-webkit-color-swatch {
            border: none;
            border-radius: 3px;
        }

        /* Native datalist popup positioning */
        datalist {
            position: fixed !important;
            z-index: 999999 !important;
        }

        /* Improve color field container */
        .gjs-field-colorp-c {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
            overflow: visible !important;
            position: relative !important;
        }

        .gjs-field-colorp-c:active {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 0 0 3px rgba(0, 123, 255, 0.15) !important;
        }

        /* Custom color preview styling */
        .gjs-clm-tags .gjs-clm-field {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .gjs-clm-tags .gjs-clm-field:hover {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
        }

        /* Color picker positioning override */
        .sp-container,
        .gjs-mdl-dialog-sm,
        [class*="color-picker"],
        [class*="colorpicker"] {
            /* position: fixed !important; */
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 999999 !important;
        }
        .gjs-field.gjs-field-color > .gjs-field-colorp {
            max-width: 100px;
        }
        /* Main Layout */
        .editor-wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Left Panel - Blocks */
        .panel-left {
            width: 280px;
            background: #f8f9fa;
            border-right: 1px solid #ddd;
            overflow-y: auto;
            flex-shrink: 0;
        }
        .gjs-input-holder input {
        color: #000;
        }

        .panel-left h5 {
            margin: 0;
            padding: 15px;
            background: #e9ecef;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            font-weight: 600;
        }

        /* Center Area - Canvas */
        .canvas-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Toolbar */
        .toolbar {
            background: #fff;
            border-bottom: 1px solid #ddd;
            padding: 10px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .toolbar button {
            padding: 8px 15px;
            border: 1px solid #ddd;
            background: #fff;
            cursor: pointer;
            border-radius: 4px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .toolbar button:hover {
            background: #f8f9fa;
            border-color: #007bff;
        }

        .toolbar button.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Auto-save button states */
        #btn-autosave {
            transition: all 0.3s ease;
        }

        #btn-autosave.active {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        #btn-autosave:not(.active) {
            background: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        #btn-autosave.active i {
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Canvas Container */
        #gjs {
            flex: 1;
            overflow: hidden;
        }

        /* Right Panel - Layers/Styles */
        .panel-right {
            width: 320px;
            background: #f8f9fa;
            border-left: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .panel-tabs {
            display: flex;
            background: #e9ecef;
            border-bottom: 1px solid #ddd;
        }

        .panel-tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
        }

        .panel-tab:hover {
            background: #dee2e6;
        }

        .panel-tab.active {
            background: #fff;
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }

        .panel-content {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #fff;
        }

        .panel-section {
            display: none;
        }

        .panel-section.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="editor-wrapper">
        <!-- Left Panel: Blocks -->
        <div class="panel-left">
            <h5 class="bg-primary"><i class="fas fa-th"></i> Components</h5>
            <div class="search-container" style="padding: 10px; background: #fff; border-bottom: 1px solid #ddd;">
                <div style="position: relative;">
                    <input type="text"
                           id="block-search"
                           placeholder="Search components..."
                           style="width: 100%; padding: 8px 35px 8px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px;">
                    <i class="fas fa-search" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                </div>
            </div>
            <div id="blocks"></div>
        </div>

        <!-- Center: Canvas -->
        <div class="canvas-area">
            <!-- Toolbar -->
            <div class="toolbar">
                <button id="btn-save" title="Save Page">
                    <i class="fas fa-save"></i> Save
                </button>
                <button id="btn-autosave" title="Auto-save is OFF - Click to enable">
                    <i class="fas fa-sync-alt"></i> Auto-save
                </button>
                <button id="btn-undo" title="Undo">
                    <i class="fas fa-undo"></i>
                </button>
                <button id="btn-redo" title="Redo">
                    <i class="fas fa-redo"></i>
                </button>
                <button id="btn-preview" title="Preview">
                    <i class="fas fa-eye"></i> Preview
                </button>
                <button id="btn-code" title="View Code">
                    <i class="fas fa-code"></i> Code
                </button>
                <button id="btn-export-html" title="Export as HTML">
                    <i class="fas fa-download"></i> Export HTML
                </button>
                <div style="margin-left: auto; display: flex; gap: 5px;">
                    <button id="btn-desktop" class="active" title="Desktop">
                        <i class="fas fa-desktop"></i>
                    </button>
                    <button id="btn-tablet" title="Tablet">
                        <i class="fas fa-tablet-alt"></i>
                    </button>
                    <button id="btn-mobile" title="Mobile">
                        <i class="fas fa-mobile-alt"></i>
                    </button>
                </div>
            </div>

            <!-- GrapesJS Canvas -->
            <div id="gjs"></div>
        </div>

        <!-- Right Panel: Layers/Styles/Settings -->
        <div class="panel-right">
            <div class="panel-tabs">
                <button class="panel-tab active" data-panel="layers">
                    <i class="fas fa-layer-group"></i> Layers
                </button>
                <button class="panel-tab" data-panel="styles">
                    <i class="fas fa-palette"></i> Styles
                </button>
                <button class="panel-tab" data-panel="settings">
                    <i class="fas fa-cog"></i> Settings
                </button>
            </div>
            <div class="panel-content">
                <div id="layers-panel" class="panel-section active"></div>
                <div id="styles-panel" class="panel-section"></div>
                <div id="settings-panel" class="panel-section"></div>
            </div>
        </div>
    </div>

    <!-- jQuery (required for Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="<?php echo $bootstrap_js; ?>"></script>

    <!-- GrapesJS Core -->
    <script src="<?php echo $config['cdn']['grapesjs_js']; ?>"></script>

    <!-- GrapesJS Plugins -->
    <script src="<?php echo $config['cdn']['grapesjs_blocks_basic']; ?>"></script>

    <!-- Pass PHP configuration to JavaScript -->
    <script>
        window.PAGEBUILDER_CONFIG = {
            bootstrapVersion: <?php echo json_encode($bootstrap_version); ?>,
            apiEndpoints: {
                save: 'api/savePagebuilder.php',
                load: 'api/loadPage.php',
                media: 'api/mediafiles.php',
                upload: 'api/saveimage.php'
            },
            paths: {
                uploadsUrl: '<?php echo $config['paths']['uploads_url']; ?>',
                thumbnailsUrl: '<?php echo $config['paths']['thumbnails_url']; ?>'
            }
        };

        const HEADER_CONTENT = <?php echo json_encode($header_content); ?>;
        const FOOTER_CONTENT = <?php echo json_encode($footer_content); ?>;
        const BOOTSTRAP_VERSION = <?php echo json_encode($bootstrap_version); ?>;
    </script>

    <!-- Custom Plugins -->
    <script src="assets/js/plugins/header-footer-lock.js"></script>
    <script src="assets/js/plugins/code-injection.js"></script>

    <!-- Custom Configuration -->
    <script src="assets/js/config/custom-components.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/media-manager.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/blocks-bootstrap.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/blocks-creative.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/blocks-extended.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/blocks-templates.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/config/blocks-icons.js?v=<?php echo time(); ?>"></script>

    <!-- Main Initialization -->
    <script src="assets/js/main-fixed.js"></script>
</body>
</html>
