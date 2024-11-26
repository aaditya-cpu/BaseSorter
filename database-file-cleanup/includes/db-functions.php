<?php

function scan_for_abandoned_databases() {
    global $wpdb;

    // Fetch tables not associated with the current WordPress installation
    $query = "
        SELECT table_name, 
               ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS table_size
        FROM information_schema.tables 
        WHERE table_schema = DATABASE()
          AND table_name NOT LIKE '{$wpdb->prefix}%'
        GROUP BY table_name
    ";

    $results = $wpdb->get_results($query, ARRAY_A);

    $abandoned_tables = [];
    if (!empty($results)) {
        foreach ($results as $result) {
            $abandoned_tables[] = [
                'name' => $result['table_name'],
                'size' => $result['table_size'] . ' MB', // Add size information
            ];
        }
    }
    error_log("Skipped file: " . $file->getRealPath());

    return $abandoned_tables;
}


function delete_database_table($table_name) {
    global $wpdb;

    if (!empty($table_name)) {
        $wpdb->query("DROP TABLE IF EXISTS `$table_name`");
    }
}
