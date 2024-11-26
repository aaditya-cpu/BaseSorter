<?php

class DB_File_Cleanup {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_delete_selected_items', [$this, 'handle_deletion']);
    }

    public function add_admin_page() {
        add_menu_page(
            'Database & File Cleanup',
            'Cleanup Utility',
            'manage_options',
            'db-file-cleanup',
            [$this, 'render_admin_page'],
            'dashicons-database',
            100
        );
    }

    public function enqueue_assets($hook_suffix) {
        if ($hook_suffix === 'toplevel_page_db-file-cleanup') {
            wp_enqueue_style('glass-css', plugin_dir_url(__FILE__) . '../assets/css/glassmorphism.css');
            wp_enqueue_script('admin-js', plugin_dir_url(__FILE__) . '../assets/js/admin.js', ['jquery'], null, true);
            wp_localize_script('admin-js', 'dbFileCleanup', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('delete_nonce'),
            ]);
        }
    }

    public function render_admin_page() {
        include plugin_dir_path(__FILE__) . '../admin/admin-page.php';
    }

    public function handle_deletion() {
        check_ajax_referer('delete_nonce', 'nonce');

        $items = isset($_POST['items']) ? $_POST['items'] : [];
        if (empty($items)) {
            wp_send_json_error(['message' => __('No items selected.', 'db-file-cleanup')]);
        }

        foreach ($items as $item) {
            if ($item['type'] === 'database') {
                delete_database_table(sanitize_text_field($item['name']));
            } elseif ($item['type'] === 'file') {
                delete_files([sanitize_text_field($item['path'])]);
            }
        }

        wp_send_json_success(['message' => __('Selected items have been deleted successfully.', 'db-file-cleanup')]);
    }
}
