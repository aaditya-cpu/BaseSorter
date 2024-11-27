# Database and File Cleanup Utility

**Version**: 1.0  
**Author**: Aaditya Uzumaki  
**License**: AAL-1.0  

## Overview

The **Database and File Cleanup Utility** is a WordPress plugin designed to enhance site performance and optimize storage by identifying and removing unused database tables and duplicate media files. This user-friendly tool simplifies the cleanup process through an intuitive admin interface.

---

## Features

- **Database Analysis**: Detect and safely delete abandoned database tables.
- **Duplicate Media File Detection**: Identify and remove duplicate media files such as images and PDFs.
- **Bulk Actions**: Easily select multiple items for deletion.
- **AJAX-Powered Deletion**: Ensure a seamless deletion process without page reloads.
- **Glassmorphism UI**: Enjoy a sleek and modern interface design.

---

## Installation

1. Download the plugin ZIP file or clone the repository.
2. Navigate to the WordPress Admin Dashboard.
3. Go to `Plugins` > `Add New` > `Upload Plugin`.
4. Upload the ZIP file and click `Install Now`.
5. Activate the plugin.

---

## Usage

1. Access the plugin via the WordPress Admin Dashboard under the "Database and File Cleanup" section.
2. **Database Analysis**:
   - Review the list of abandoned database tables.
   - Select tables for deletion.
3. **Duplicate Media Files**:
   - View duplicate media files with details like name, location, size, and modification date.
   - Select unwanted files for deletion.
4. Click the **Delete Selected Items** button to remove the selected items.

---

## Screenshots

### Database Analysis
- Displays a list of abandoned database tables with sizes.

### Duplicate Media Files
- Provides a table view of duplicate media files with key attributes for review.

---

## Technical Details

### File Structure

- `database-file-cleanup.php`: Main plugin file, includes initialization and lifecycle hooks.
- `admin/admin-page.php`: Renders the plugin admin page with database and file listings.
- `admin/admin-ajax.php`: Handles AJAX requests for item deletion.
- `assets/css/glassmorphism.css`: Provides modern UI styling with a glassmorphism design.
- `assets/js/admin.js`: Implements interactive functionality with jQuery.

### Hooks Used

- **`plugins_loaded`**: Initializes the plugin.
- **`register_activation_hook`**: Executes tasks on plugin activation.
- **`register_deactivation_hook`**: Handles cleanup tasks on deactivation.
- **`register_uninstall_hook`**: Cleans up plugin data on uninstall.

---

## Security Measures

- **Nonce Validation**: Prevents CSRF attacks during AJAX requests.
- **User Permissions Check**: Ensures only authorized users can delete items.
- **Sanitization**: All inputs are sanitized before processing.

---

## Glassmorphism UI Design

The plugin employs a visually appealing glassmorphism design:
- **Backdrop Blur**: Enhances contrast while maintaining a modern aesthetic.
- **Dynamic Hover Effects**: Adds interactivity with smooth transitions.
- **Responsive Design**: Ensures usability across all devices.

---

## Contributing

Contributions are welcome! Follow these steps to contribute:
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature-name`).
3. Commit your changes (`git commit -m "Add feature-name"`).
4. Push to the branch (`git push origin feature-name`).
5. Open a pull request.

---

## License

This plugin is licensed under the **AAL-1.0**. See the [LICENSE](LICENSE) file for more details.

---

## Support

For issues or feature requests, contact the author at [Aaditya Uzumaki](https://goenka.xyz).

---
