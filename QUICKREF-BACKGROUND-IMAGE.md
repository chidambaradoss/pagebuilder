# Quick Reference: Background Image Media Library

## What Was Added
✅ Media Library integration for div/container background images
✅ Button in Style Manager → Decorations → "Background Image"
✅ Additional background control properties (size, position, repeat, attachment)
✅ Custom styling for the media library button

## Files Modified
1. **assets/js/config/media-manager.js**
   - Added selection mode tracking ('src' vs 'background')
   - Updated image selection handler for backgrounds
   - Changed modal title based on selection mode

2. **assets/js/main-fixed.js**
   - Added custom button property in Decorations sector
   - Added background properties (repeat, position, size, attachment)
   - Button opens media library in 'background' mode

3. **assets/css/editor.css**
   - Added styling for the background image button
   - Gradient button design with hover effects

## Testing Checklist
- [ ] Open the page builder
- [ ] Add a div/section/container to the canvas
- [ ] Select the element
- [ ] Open Styles panel → Decorations section
- [ ] Click "Select from Media Library" button
- [ ] Modal should open with title "Select Background Image"
- [ ] Select an image from the library
- [ ] Background image should be applied to the element
- [ ] Verify background-size: cover is applied
- [ ] Verify background-position: center is applied
- [ ] Test adjusting background properties (size, position, repeat)
- [ ] Test that regular image elements still work (double-click to open)

## Usage Example
```javascript
// Programmatic usage
editor.runCommand('open-media-library', { 
    target: selectedComponent,
    mode: 'background' // Key parameter for background images
});
```

## CSS Applied
When selecting a background image, these styles are automatically set:
```css
background-image: url('path/to/image.jpg');
background-size: cover;
background-position: center;
background-repeat: no-repeat;
```

## Notes
- Image components (img tags) continue to use 'src' mode by default
- Background mode is specifically for CSS background-image property
- All images managed through the same unified media library
- No breaking changes to existing functionality
