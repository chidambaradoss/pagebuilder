# GrapesJS Page Builder - Complete Implementation

A professional PHP-based page builder using GrapesJS, similar to WordPress Elementor, with Bootstrap integration, custom media management, and advanced editing capabilities.

## Features

### Core Functionality
- ✅ **Bootstrap 4/5 Integration** - Switch between Bootstrap versions via configuration
- ✅ **Non-Editable Header/Footer** - Locked sections that cannot be modified
- ✅ **Body-Only Save** - Saves only body content, excluding header and footer
- ✅ **Light-Mode UI** - Professional light theme with layer panel visibility
- ✅ **Layer Panel** - View component hierarchy and structure
- ✅ **Auto-Save** - Automatic saving every 60 seconds

### Media Management
- ✅ **Custom Media Library** - Browse and select uploaded images
- ✅ **Image Upload** - Drag-and-drop or browse to upload
- ✅ **Thumbnail Generation** - Automatic server-side thumbnail creation
- ✅ **Media Search** - Filter images by filename
- ✅ **Security Validation** - File type, size, and content verification

### Code Injection
- ✅ **HTML Editor** - Edit component HTML directly
- ✅ **CSS Editor** - Add custom styles to components
- ✅ **JavaScript Editor** - Inject custom JavaScript code

### Block Library (30+ Components)
- **Sections**: Hero, Features, Call-to-Action
- **Cards**: Basic, Pricing, Profile
- **Forms**: Contact, Newsletter
- **Navigation**: Navbar, Breadcrumbs
- **Creative**: Gallery, Testimonials, Pricing Tables, Team, Statistics
- **Layout**: Containers, Columns (2, 3)

## Installation

### Prerequisites
- PHP 7.4 or higher
- Apache with mod_rewrite enabled
- GD Library (for thumbnail generation)
- XAMPP or similar local server environment

### Quick Start

1. **Access the Application**
   - Navigate to: `http://localhost/pagebuilder/`
   - The editor will load automatically

2. **Start Building**
   - Drag blocks from the left sidebar onto the canvas
   - Double-click images to open the media library
   - Click the code icon in the toolbar to edit HTML/CSS/JS
   - Click Save button to persist your changes

## Configuration

### Bootstrap Version Switching

Edit `config.php` to switch between Bootstrap 4 and 5:

```php
'bootstrap_version' => '5',  // Change to '4' or '5'
```

### Upload Settings

Configure upload limits in `config.php`:

```php
'upload' => [
    'max_size' => 5242880, // 5MB in bytes
    'allowed_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
    'thumbnail_width' => 300,
    'thumbnail_height' => 300,
],
```

### Header and Footer Content

Customize the header and footer in `index.php`:

```php
$header_content = '
<header>
    <!-- Your custom header HTML -->
</header>
';

$footer_content = '
<footer>
    <!-- Your custom footer HTML -->
</footer>
';
```

## File Structure

```
pagebuilder/
├── index.php                          # Main editor interface
├── config.php                         # Configuration settings
├── .htaccess                          # Security settings
│
├── api/
│   ├── savePagebuilder.php           # Save endpoint
│   ├── loadPage.php                  # Load endpoint
│   ├── saveimage.php                 # Upload endpoint
│   └── mediafiles.php                # Media list endpoint
│
├── assets/
│   ├── css/
│   │   └── editor.css                # Light mode theme
│   ├── js/
│       ├── main.js                   # Main orchestration
│       ├── config/
│       │   ├── editor-config.js      # Editor settings
│       │   ├── custom-components.js  # Custom components
│       │   ├── blocks-bootstrap.js   # Bootstrap blocks
│       │   ├── blocks-creative.js    # Creative blocks
│       │   └── media-manager.js      # Media library
│       └── plugins/
│           ├── header-footer-lock.js # Header/footer locking
│           └── code-injection.js     # Code editor
│
├── uploads/
│   ├── images/                       # Uploaded images
│   ├── thumbnails/                   # Generated thumbnails
│   └── .htaccess                     # Security (no PHP execution)
│
└── pages/
    ├── latest.json                   # Latest saved page
    ├── page_*.json                   # Timestamped versions
    └── .htaccess                     # Security (no direct access)
```

## Usage Guide

### Building Pages

1. **Add Components**
   - Browse the block library in the left sidebar
   - Drag and drop blocks onto the canvas
   - Components are organized by category (Sections, Cards, Forms, etc.)

2. **Edit Components**
   - Click to select a component
   - Use the Settings panel on the right to modify properties
   - Use the Styles panel to adjust visual styling
   - Double-click images to open the media library

3. **Manage Images**
   - Double-click any image component
   - Click "Browse Media Library" tab to view uploaded images
   - Click "Upload New" tab to upload new images
   - Drag and drop files or click to browse
   - Search images by filename

4. **Inject Custom Code**
   - Select a component
   - Click the code icon (<>) in the toolbar
   - Edit HTML, CSS, or JavaScript
   - Click "Apply Changes" to update

5. **Save Your Work**
   - Click the Save button in the toolbar
   - Auto-save runs every 60 seconds
   - Saved content loads automatically on page refresh

### Device Preview

Switch between device modes using the device buttons:
- **Desktop** - Full width view
- **Tablet** - 768px width
- **Mobile** - 375px width

### Panel Switching

Toggle between different panels on the right:
- **Layers** - Component hierarchy view
- **Styles** - Visual styling options
- **Settings** - Component properties

## Security Features

### Upload Security
- ✅ File type validation (MIME + extension)
- ✅ Actual image verification with `getimagesize()`
- ✅ File size limits enforcement
- ✅ Unique random filenames
- ✅ `.htaccess` prevents PHP execution in uploads

### Directory Protection
- ✅ Uploads directory: Images only, no PHP
- ✅ Pages directory: No direct access
- ✅ Hidden files protected

### Headers
- ✅ X-Frame-Options (clickjacking protection)
- ✅ X-XSS-Protection
- ✅ X-Content-Type-Options (MIME sniffing prevention)
- ✅ Referrer-Policy

## API Endpoints

### Save Page Content
```
POST /api/savePagebuilder.php
Content-Type: application/json

{
    "html": "<div>...</div>",
    "css": ".class { ... }",
    "components": "{ ... }",
    "timestamp": "2024-01-01T12:00:00Z"
}
```

### Load Page Content
```
GET /api/loadPage.php

Response:
{
    "success": true,
    "data": {
        "html": "...",
        "css": "...",
        "components": "...",
        "timestamp": "..."
    }
}
```

### Upload Images
```
POST /api/saveimage.php
Content-Type: multipart/form-data

Form Data: images[] = [files]
```

### List Media Files
```
GET /api/mediafiles.php

Response:
{
    "success": true,
    "files": [
        {
            "id": "...",
            "name": "...",
            "url": "...",
            "thumbnail": "...",
            "size": "...",
            "dimensions": "..."
        }
    ]
}
```

## Troubleshooting

### Images Not Uploading
- Check PHP upload limits in `php.ini`
- Verify GD library is installed: `php -m | grep -i gd`
- Ensure `uploads/` directory has write permissions (755)

### Styles Not Working
- Clear browser cache
- Check browser console for JavaScript errors
- Verify Bootstrap CDN is loading correctly

### Save Not Working
- Check `pages/` directory has write permissions (755)
- Verify API endpoint URLs in browser console
- Check PHP error logs for server-side errors

### .htaccess Not Working
- Ensure mod_rewrite is enabled in Apache
- Check Apache error logs
- Verify AllowOverride is set to All

## Browser Compatibility

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

## Performance

- **Auto-save**: 60-second intervals
- **Thumbnail generation**: Server-side using GD
- **File size limit**: 5MB per image (configurable)
- **Max upload**: 10 images simultaneously

## Production Deployment

### Before Going Live:

1. **Enable HTTPS**
   - Uncomment HTTPS redirect in `.htaccess`

2. **Disable Error Display**
   - Set `display_errors = Off` in `php.ini`

3. **Enable Authentication**
   - Uncomment authentication checks in API files
   - Implement session-based authentication

4. **Configure CSP**
   - Uncomment and configure Content-Security-Policy header

5. **Set Secure Permissions**
   - Files: 644
   - Directories: 755
   - config.php: 600

6. **Backup Strategy**
   - Regular backups of `pages/` directory
   - Database backups (if you add database storage)

## Extending the Builder

### Adding Custom Blocks

Edit `assets/js/config/blocks-custom.js`:

```javascript
bm.add('my-custom-block', {
    label: 'My Block',
    category: 'Custom',
    content: `<div class="my-block">Custom content</div>`
});
```

### Adding Custom Components

Edit `assets/js/config/custom-components.js`:

```javascript
editor.DomComponents.addType('my-component', {
    extend: 'default',
    model: {
        defaults: {
            // Component configuration
        }
    }
});
```

## Support

For issues, questions, or feature requests:
- Check the [GrapesJS Documentation](https://grapesjs.com/docs/)
- Review browser console for errors
- Check Apache/PHP error logs

## License

This project uses open-source libraries:
- GrapesJS: BSD 3-Clause License
- Bootstrap: MIT License
- Font Awesome: Free License

## Credits

Built with:
- [GrapesJS](https://grapesjs.com/) - Web Builder Framework
- [Bootstrap](https://getbootstrap.com/) - CSS Framework
- [Font Awesome](https://fontawesome.com/) - Icons
- PHP GD Library - Image Processing

---

**Version**: 1.0.0
**Last Updated**: 2024
**Author**: Page Builder Development Team
