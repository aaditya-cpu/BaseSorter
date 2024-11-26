<?php

function scan_for_abandoned_databases() {
    global $wpdb;

    // Fetch tables not associated with the current WordPress installation
    $query = "
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() 
          AND table_name NOT LIKE '{$wpdb->prefix}%'
    ";

    $results = $wpdb->get_results($query, ARRAY_A);

    $abandoned_tables = [];
    if (!empty($results)) {
        foreach ($results as $result) {
            $abandoned_tables[] = $result['table_name'];
        }
    }

    return $abandoned_tables;
}
function delete_database_table($table_name) {
    global $wpdb;

    if (!empty($table_name)) {
        $wpdb->query("DROP TABLE IF EXISTS `$table_name`");
    }
}
