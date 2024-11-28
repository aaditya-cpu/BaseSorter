<?php

/**
 * Scans the upload directory for duplicate files while excluding files actively used on the website.
 *
 * @return array List of duplicate files with their metadata.
 */
function scan_for_duplicate_files() {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        WP_Filesystem();
    }

    $upload_dir = wp_get_upload_dir()['basedir'];
    $allowed_extensions = [
        // Image formats
        'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp', 'avif', 'svg',

        // Document formats
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf', 'odt', 'ods', 'odp', 'epub',

        // Audio formats
        'mp3', 'wav', 'ogg', 'flac', 'aac',

        // Video formats
        'mp4', 'mkv', 'avi', 'mov', 'wmv', 'flv', 'webm',

        // Compressed formats
        'zip', 'rar', 'tar', 'gz', '7z',

        // Others
        'json', 'xml', 'csv', 'yaml'
    ];

    // Folders and file patterns to exclude
    $excluded_folders = ['photo-gallery']; // Plugin-specific folders to ignore
    $thumbnail_patterns = ['-150x150', '-300x300', '_thumb', '_small', '_large']; // Thumbnail naming patterns

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_dir));
    $hash_map = [];
    $duplicates = [];
    $used_files = get_used_files(); // List of files actively used in WordPress

    foreach ($files as $file) {
        if ($file->isFile()) {
            $file_extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

            // Skip if not an allowed file type
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                continue;
            }

            $file_path = $file->getRealPath();
            $file_url = wp_get_upload_dir()['baseurl'] . str_replace($upload_dir, '', $file_path);

            // Skip excluded folders
            foreach ($excluded_folders as $folder) {
                if (strpos($file_path, DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR) !== false) {
                    continue 2; // Skip this file
                }
            }

            // Skip files matching thumbnail patterns
            foreach ($thumbnail_patterns as $pattern) {
                if (strpos($file->getFilename(), $pattern) !== false) {
                    continue 2; // Skip this file
                }
            }

            // Skip actively used files
            if (in_array($file_url, $used_files)) {
                continue;
            }

            // Generate hash to identify duplicates
            $hash = md5_file($file_path);
            $file_size = size_format(filesize($file_path)); // Human-readable size format
            $file_modified = date("F d Y H:i:s.", filemtime($file_path));

            if (isset($hash_map[$hash])) {
                // If duplicate, add to the list
                $duplicates[] = [
                    'path' => $file_path,
                    'url' => $file_url,
                    'size' => $file_size,
                    'modified' => $file_modified,
                ];
            } else {
                // Add hash to the map if unique
                $hash_map[$hash] = true;
            }
        }
    }

    return $duplicates; // Returns the list of duplicates for review
}



/**
 * Gets a list of URLs for files currently used on the website.
 *
 * @return array List of file URLs used in the website.
 */
function get_used_files() {
    global $wpdb;

    // Query all posts and their attached media
    $query = "
        SELECT pm.meta_value AS file_url
        FROM {$wpdb->prefix}posts p
        INNER JOIN {$wpdb->prefix}postmeta pm
            ON p.ID = pm.post_id
        WHERE pm.meta_key = '_wp_attached_file'
          AND p.post_status = 'publish'
    ";

    $results = $wpdb->get_results($query, ARRAY_A);

    $used_files = [];
    if (!empty($results)) {
        $upload_dir = wp_get_upload_dir();
        foreach ($results as $result) {
            $used_files[] = $upload_dir['baseurl'] . '/' . $result['file_url'];
        }
    }

    return $used_files;
}

/**
 * Deletes specified files, ensuring that actively used files are not removed.
 *
 * @param array $file_paths Array of file paths to delete.
 */
function delete_files($file_paths) {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        WP_Filesystem();
    }

    $used_files = get_used_files(); // Get the list of used files

    foreach ($file_paths as $file_path) {
        $file_url = wp_get_upload_dir()['baseurl'] . str_replace(wp_get_upload_dir()['basedir'], '', $file_path);

        // Skip if the file is marked as used
        if (in_array($file_url, $used_files)) {
            error_log("Skipped deletion of used file: $file_path");
            continue;
        }

        if ($wp_filesystem->exists($file_path)) {
            if ($wp_filesystem->delete($file_path)) {
                error_log("Deleted file: $file_path");
            } else {
                error_log("Failed to delete file: $file_path");
            }
        }
    }
}
