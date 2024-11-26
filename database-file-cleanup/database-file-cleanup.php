<?php
/*
Plugin Name: Database and File Cleanup Utility
Description: An advanced plugin for WordPress to clean up databases and files, optimizing storage and performance.
Version: 1.0
Author: Aaditya Uzumaki
Author URI: https://goenka.xyz
License: AAL-1.0
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Define Plugin Version
define('DB_FILE_CLEANUP_VERSION', '1.0');

// Define the plugin directory path
define('DB_FILE_CLEANUP_PATH', plugin_dir_path(__FILE__));
define('DB_FILE_CLEANUP_URL', plugin_dir_url(__FILE__));

// Includes
require_once DB_FILE_CLEANUP_PATH . 'includes/class-db-file-cleanup.php';
require_once DB_FILE_CLEANUP_PATH . 'includes/db-functions.php';
require_once DB_FILE_CLEANUP_PATH . 'includes/file-functions.php';
require_once DB_FILE_CLEANUP_PATH . 'includes/security.php';

/**
 * Initialize the plugin
 */
function db_file_cleanup_init() {
    // Instantiate the main class
    new DB_File_Cleanup();
}
add_action('plugins_loaded', 'db_file_cleanup_init');

/**
 * Plugin activation hook
 */
function db_file_cleanup_activate() {
    // Tasks to perform on activation
    // E.g., add default options or create tables if needed
    if (!current_user_can('activate_plugins')) {
        return;
    }
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'db_file_cleanup_activate');

/**
 * Plugin deactivation hook
 */
function db_file_cleanup_deactivate() {
    // Tasks to perform on deactivation
    if (!current_user_can('activate_plugins')) {
        return;
    }
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'db_file_cleanup_deactivate');

/**
 * Plugin uninstall hook
 */
function db_file_cleanup_uninstall() {
    // Tasks to perform on uninstall
    if (!defined('WP_UNINSTALL_PLUGIN')) {
        exit;
    }

    // Example: Remove options or clean up database tables created by the plugin
    delete_option('db_file_cleanup_settings');
}
register_uninstall_hook(__FILE__, 'db_file_cleanup_uninstall');
