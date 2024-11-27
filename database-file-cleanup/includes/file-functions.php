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
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'mp4', 'docx']; // Add required file types
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_dir));
    $hash_map = [];
    $duplicates = [];
    $used_files = get_used_files(); // Get the list of used files from the database

    foreach ($files as $file) {
        if ($file->isFile()) {
            $file_extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

            // Skip if not an allowed file type
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                continue;
            }

            $file_path = $file->getRealPath();
            $file_url = wp_get_upload_dir()['baseurl'] . str_replace($upload_dir, '', $file_path);

            // Skip if the file is marked as used
            if (in_array($file_url, $used_files)) {
                continue;
            }

            $hash = md5_file($file_path);
            $file_size = size_format(filesize($file_path)); // Convert size to readable format
            $file_modified = date("F d Y H:i:s.", filemtime($file_path));

            if (isset($hash_map[$hash])) {
                $duplicates[] = [
                    'path' => $file_path,
                    'url' => $file_url, // Include file URL for better traceability
                    'size' => $file_size,
                    'modified' => $file_modified,
                ];
            } else {
                $hash_map[$hash] = true;
            }
        }
    }

    return $duplicates;
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