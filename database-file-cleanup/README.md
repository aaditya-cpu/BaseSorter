```markdown
# Database & File Cleanup Utility Plugin

## Overview

Imagine your WordPress website as a big room where you keep all your photos, documents, and old records. Some of these records are actively used (like decorations on the walls), while others are just lying around taking up space. This plugin acts like a cleaning robot for your room. It helps you:

- **Find and clean up unused database tables** (old records not connected to your WordPress site).
- **Detect duplicate media files** (extra copies of photos or documents taking up space).
- **Safely delete duplicates or old files** without harming the ones you're using.

The plugin ensures safety by checking which files are actively used on your website and excluding them from deletion.

---

## How It Ensures Safety

### Step 1: Detecting Duplicate Files
1. **Scan the uploads folder**  
   The plugin scans the `wp-content/uploads` directory for all files.
2. **Hash comparison**  
   Each file's content is analyzed using hashing (like a digital fingerprint). Files with the same fingerprint are marked as duplicates.
3. **Check against active files**  
   Duplicates are cross-referenced with files listed in the WordPress database. Files attached to posts, pages, or used anywhere on the site are excluded.

### Step 2: Verifying Database Tables
1. **Check for abandoned tables**  
   Identifies database tables that don’t belong to WordPress or its plugins/themes and flags them as safe to delete.
2. **Exclude active tables**  
   Database tables actively used by WordPress are protected from deletion.

---

## Key Functions

### File Functions
- **`scan_for_duplicate_files()`**
  - Finds duplicate files in the uploads folder.
  - Compares file fingerprints and marks unused duplicates for deletion.
  - Protects files actively used on the website.
- **`delete_files($file_paths)`**
  - Deletes selected files from the server.
  - Double-checks that files aren’t listed as active before deletion.
- **`get_used_files()`**
  - Queries the WordPress database to get a list of files currently used in posts or pages.
  - Ensures these files are never flagged as duplicates.

### Database Functions
- **`scan_for_abandoned_databases()`**
  - Checks for tables in the database that don’t belong to WordPress.
  - Lists these tables for deletion.
- **`delete_database_table($table_name)`**
  - Safely deletes selected database tables if marked as unused.

---

## Practical Flow of the Plugin

### Admin Interface
- Displays two sections: **Unused Database Tables** and **Duplicate Files**.
- Provides checkboxes for selecting items to delete.

### Safety Net
- Ensures selected files and database tables are not actively used before deletion.

### AJAX for Real-Time Deletion
- When "Delete Selected Items" is clicked, selected items are sent to the server.
- Files are rechecked before deletion to prevent accidental removal.

---

## Example Scenarios

### Scenario: Duplicate Image
- Files: `image1.jpg` (used in a post) and `image1-copy.jpg` (not used).
- The plugin detects the duplicate, confirms `image1.jpg` is used, and flags only `image1-copy.jpg` for deletion.

### Scenario: Database Cleanup
- A plugin you uninstalled left behind a table in the database.
- The plugin detects it as abandoned and lists it for deletion.

---

## Why This Plugin is Safe

1. **Cross-Verification**  
   Actively used files are checked in the database before deletion.
2. **Fallback Mechanisms**  
   Files in doubt are excluded from deletion.
3. **Minimal Impact**  
   Only removes duplicates and unused tables/files, ensuring website operation is unaffected.

---

## How to Use the Plugin

1. **Install the Plugin**
   - Upload the plugin to your WordPress site and activate it.

2. **Access the Admin Page**
   - Navigate to **"Database & File Cleanup Utility"** in the WordPress admin menu.

3. **Review Items**
   - Review the lists of duplicate files and unused database tables.

4. **Delete Selected Items**
   - Check the boxes for items you want to delete and click the **"Delete Selected Items"** button.

5. **Confirmation**
   - A final check ensures nothing important is deleted.

---

## Attribution and License

This plugin is developed by **Aaditya Uzumaki** under the **AAL-1.0 License**. It utilizes WordPress core functions and complies with open-source standards. For more details, visit the plugin author's website.

Feel free to reach out with suggestions or feedback to make this cleaning robot even better!
```
