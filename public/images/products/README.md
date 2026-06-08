# Product Images

## Structure

Tempatkan semua album cover images di folder ini.

## Naming Convention

- `abbey-road.jpg` - Abbey Road (The Beatles)
- `rumours.jpg` - Rumours (Fleetwood Mac)
- `blue-train.jpg` - Blue Train (John Coltrane)
- `after-hours.jpg` - After Hours (The Weeknd)
- `amber-lights.jpg` - Amber Lights (Fictional Act)
- `quiet-motion.jpg` - Quiet Motion (Midnight City)
- `expiation.jpg` - Expiation (NMIXX)
- `born-pink.jpg` - Born Pink (BLACKPINK)
- `eyes-wide-open.jpg` - Eyes Wide Open (TWICE)
- `freeze.jpg` - Freeze (Stray Kids)
- `emote.jpg` - Emote (IVE)
- `new-jeans.jpg` - New Jeans (NewJeans)
- `henggarae.jpg` - Henggarae (SEVENTEEN)
- `savage.jpg` - Savage (aespa)

## Recommended Specifications

- Format: JPEG or PNG
- Dimensions: Square (preferably 600x600px or larger for quality)
- File size: Max 1MB per image
- Color profile: RGB

## How to Add Images

1. Save album cover image as JPEG/PNG
2. Rename according to convention above
3. Place in `public/images/products/` folder
4. In Blade template, add inline style to product cards:

```blade
<div class="product-cover cover-a" style="background-image: url('/images/products/abbey-road.jpg')"></div>
```

The CSS already supports `background-size: cover` and `background-position: center`, so images will scale automatically.

## Currently Using

- Gradient placeholders (CSS gradients as fallback)
- Ready for real image integration

## Next Steps

1. Gather album cover images
2. Optimize for web (compress, resize to 600x600)
3. Update `.blade.php` templates with inline background-image styles
4. Fallback gradients will still display if images are missing
