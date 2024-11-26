<?php

function check_user_permissions() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Unauthorized user', 'db-file-cleanup'));
    }
}

function verify_nonce($nonce) {
    if (!wp_verify_nonce($nonce, 'delete_nonce')) {
        wp_die(__('Security check failed', 'db-file-cleanup'));
    }
}
