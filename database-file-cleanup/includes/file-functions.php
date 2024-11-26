<?php

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

    foreach ($files as $file) {
        if ($file->isFile()) {
            $file_extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);

            // Skip if not an allowed file type
            if (!in_array(strtolower($file_extension), $allowed_extensions)) {
                continue;
            }

            $hash = md5_file($file->getRealPath());
            $file_path = $file->getRealPath();
            $file_size = size_format(filesize($file_path)); // Convert size to readable format
            $file_modified = date("F d Y H:i:s.", filemtime($file_path));

            if (isset($hash_map[$hash])) {
                $duplicates[] = [
                    'path' => $file_path,
                    'size' => $file_size,
                    'modified' => $file_modified,
                ];
            } else {
                $hash_map[$hash] = true;
            }
        }
    }
    error_log("Skipped file: " . $file->getRealPath());
    return $duplicates;
}



function delete_files($file_paths) {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        WP_Filesystem();
    }

    foreach ($file_paths as $file_path) {
        if ($wp_filesystem->exists($file_path)) {
            $wp_filesystem->delete($file_path);
        }
    }
}
