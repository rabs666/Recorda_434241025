# Article Images

## Structure

Tempatkan semua article thumbnail images di folder ini.

## Naming Convention

- `vinyl-collecting-101.jpg` - Vinyl Collecting 101
- `best-k-pop-albums-2023.jpg` - Best K-Pop Albums 2023
- `how-to-care-for-records.jpg` - How to Care for Records
- `jazz-classics-explained.jpg` - Jazz Classics Explained

## Recommended Specifications

- Format: JPEG or PNG
- Dimensions: Rectangular (preferably 300x200px or 16:9 ratio)
- File size: Max 500KB per image
- Color profile: RGB

## How to Add Images

1. Save article thumbnail image as JPEG/PNG
2. Rename according to convention above
3. Place in `public/images/articles/` folder
4. In Blade template, add to article card:

```blade
<div class="article-thumb" style="background-image: url('/images/articles/vinyl-collecting-101.jpg')"></div>
```

## Currently Using

- Placeholder backgrounds
- Ready for real image integration

## Next Steps

1. Create or gather article thumbnail images
2. Optimize for web (compress, resize to 300x200)
3. Update `.blade.php` templates with inline background-image styles
