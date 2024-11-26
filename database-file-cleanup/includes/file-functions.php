<?php

function scan_for_duplicate_files() {
    global $wp_filesystem;

    if (empty($wp_filesystem)) {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        WP_Filesystem();
    }

    $upload_dir = wp_get_upload_dir()['basedir'];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($upload_dir));
    $hash_map = [];
    $duplicates = [];

    foreach ($files as $file) {
        if ($file->isFile()) {
            $hash = md5_file($file->getRealPath());
            $file_path = $file->getRealPath();
            $file_size = filesize($file_path); // Get size in bytes

            if (isset($hash_map[$hash])) {
                $duplicates[] = [
                    'path' => $file_path,
                    'size' => size_format($file_size), // Convert to readable format
                ];
            } else {
                $hash_map[$hash] = true;
            }
        }
    }

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
