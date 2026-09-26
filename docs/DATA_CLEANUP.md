# Data Cleanup & Migration Plan (Phase 7)

## Products Mapping & Cleanup

When migrating data from `legacy/products.js` to the MySQL database, we observed several data integrity issues. The migration script will programmatically reconcile these mismatches.

### 1. Duplicate IDs
- **Issue**: `lap-001` was used twice. Once for the "HP EliteDesk 800 G5 Desktop" (Desktops) and once for the "HP 15s-fq5000 Laptop" (Laptops).
- **Resolution**: The desktop's ID will be rewritten to `dsk-001` during the migration.

### 2. Category Normalization
The `PRODUCTS` array contains inconsistently named categories which do not map 1:1 with the defined `CATEGORIES` list or our normalized database seed.

**Mapping Rules (Old -> New):**
- `"CCTV'S"` -> `"CCTV"`
- `"RAMS"` -> `"RAM"`
- `"Plotter"` -> `"Plotting Machines"`
- `"8-in-ONE Heatrpess"` -> `"Heat Press Machines"`
- `"Power Cables"` -> Remains `"Power Cables"` (This category was missing from the `CATEGORIES` array in legacy JS but is present in our DB seed).

### 3. Missing Fields
- **Slug**: Generated dynamically from the product's name using a standard URL-safe formatting function (e.g. `str_replace`, `strtolower`).
- **Images**: In `products.js`, only `lap-001` has an `image` property. During migration, missing images will simply fall back to a placeholder image, or we can just leave `image_path` as `NULL`. For products with an icon, we will store the icon emoji in the `icon` field.
- **Specs**: Will be encoded as a JSON string when inserting into the database `specs` column.

The migration script will execute these transformations in-memory and perform bulk inserts into the `products` table, looking up the appropriate `category_id` from the normalized `categories` table.
