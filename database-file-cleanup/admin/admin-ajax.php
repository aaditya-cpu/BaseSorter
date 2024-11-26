<?php

add_action('wp_ajax_delete_selected_items', 'delete_selected_items_callback');

function delete_selected_items_callback() {
    check_ajax_referer('delete_nonce', 'nonce');
    check_user_permissions();

    $items = isset($_POST['items']) ? $_POST['items'] : [];

    foreach ($items as $item) {
        if ($item['type'] === 'database') {
            $table_name = sanitize_text_field($item['name']);
            delete_database_table($table_name);
        } elseif ($item['type'] === 'file') {
            $file_path = sanitize_text_field($item['path']);
            delete_files([$file_path]);
        }
    }

    wp_send_json_success([
        'message' => __('Selected items have been deleted successfully.', 'db-file-cleanup'),
    ]);
}
