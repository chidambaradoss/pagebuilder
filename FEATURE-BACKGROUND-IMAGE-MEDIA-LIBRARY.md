# Media Library Background Image Selection Feature

## Summary
Added the ability to select background images from the Media Library for div elements and any other container components through the Style Manager.

## Changes Made

### 1. Media Manager Enhancement (`assets/js/config/media-manager.js`)
- Added `selectionMode` variable to track whether selecting for `src` or `background`
- Modified `open-media-library` command to accept a `mode` parameter
- Updated selection handler to apply images differently based on mode:
  - **src mode**: Sets the `src` attribute (for `<img>` elements)
  - **background mode**: Sets CSS properties:
    - `background-image: url('...')`
    - `background-size: cover`
    - `background-position: center`
    - `background-repeat: no-repeat`
- Updated modal title to show "Select Background Image" when in background mode

### 2. Style Manager Configuration (`assets/js/main-fixed.js`)
- Added custom button property in the "Decorations" sector:
  - **Name**: "Background Image"
  - **Type**: Button
  - **Action**: Opens Media Library in background mode
- Added additional background properties for fine-tuning:
  - `background-repeat`
  - `background-position`
  - `background-attachment`
  - `background-size`
- Removed generic `background` property in favor of specific controls

### 3. Button Styling (`assets/css/editor.css`)
- Added custom styles for the background image button in the Style Manager
- Gradient button with hover effects
- Icon prefix (🖼️) for visual clarity
- Smooth transitions and elevation effects

## How to Use

### For Users
1. Select any div or container element in the canvas
2. Open the **Styles** panel on the right
3. Expand the **Decorations** section
4. Click the **"Select from Media Library"** button under "Background Image"
5. Choose an image from the media library or upload a new one
6. The image will be applied as the background
7. Optionally adjust background properties:
   - Background Size (cover, contain, auto, etc.)
   - Background Position (center, top, bottom, etc.)
   - Background Repeat (no-repeat, repeat, repeat-x, etc.)
   - Background Attachment (scroll, fixed)

### For Developers
To programmatically open the media library for background selection:
```javascript
editor.runCommand('open-media-library', { 
    target: component,  // The component to apply the background to
    mode: 'background'  // Use 'background' mode instead of default 'src'
});
```

## Technical Details

### Selection Modes
- **src mode** (default): Used for `<img>` elements - sets the `src` attribute
- **background mode**: Used for div backgrounds - sets CSS `background-image` and related properties

### CSS Properties Applied
When an image is selected in background mode, these CSS properties are automatically set:
```css
background-image: url('path/to/image.jpg');
background-size: cover;
background-position: center;
background-repeat: no-repeat;
```

Users can then modify these properties using the additional controls in the Style Manager.

## Compatibility
- Works with all block types (divs, sections, containers, etc.)
- Image components continue to work as before with `src` mode
- No breaking changes to existing functionality

## Benefits
1. **Unified Media Management**: All images (both `src` and backgrounds) use the same media library
2. **Consistency**: Same familiar interface for all image selection
3. **Flexibility**: Users can easily switch and update background images
4. **Better UX**: Visual media picker instead of typing URLs
5. **Default Best Practices**: Automatically applies sensible defaults (cover, center, no-repeat)
