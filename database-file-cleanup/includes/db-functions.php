<?php

/**
 * Scans the database for tables not associated with the current WordPress installation.
 *
 * @return array List of abandoned database tables with their sizes.
 */
function scan_for_abandoned_databases() {
    global $wpdb;

    // Initialize an empty array for abandoned tables
    $abandoned_tables = [];

    try {
        // Fetch tables not associated with the current WordPress installation
        $query = "
            SELECT table_name, 
                   ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS table_size
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
              AND table_name NOT LIKE '{$wpdb->prefix}%'
            GROUP BY table_name
        ";

        // Execute the query
        $results = $wpdb->get_results($query, ARRAY_A);

        // Check for query errors
        if ($wpdb->last_error) {
            error_log("Database query error: " . $wpdb->last_error);
            return [];
        }

        // Prepare the results
        if (!empty($results)) {
            foreach ($results as $result) {
                $abandoned_tables[] = [
                    'name' => isset($result['table_name']) ? $result['table_name'] : 'Unknown',
                    'size' => isset($result['table_size']) ? $result['table_size'] . ' MB' : 'Unknown',
                ];
            }
        }
    } catch (Exception $e) {
        // Log any exceptions
        error_log("Error scanning for abandoned databases: " . $e->getMessage());
    }

    return $abandoned_tables;
}

/**
 * Deletes a specified database table.
 *
 * @param string $table_name The name of the database table to delete.
 * @return bool True if the table is deleted, false otherwise.
 */
function delete_database_table($table_name) {
    global $wpdb;

    // Check if the table name is not empty
    if (empty($table_name)) {
        error_log("Delete failed: Table name is empty.");
        return false;
    }

    try {
        // Sanitize the table name to prevent SQL injection
        $table_name = esc_sql($table_name);

        // Execute the delete query
        $query = "DROP TABLE IF EXISTS `$table_name`";
        $result = $wpdb->query($query);

        if ($result === false) {
            // Log error if the query fails
            error_log("Failed to delete table: $table_name. Error: " . $wpdb->last_error);
            return false;
        }

        return true; // Successfully deleted
    } catch (Exception $e) {
        // Log any exceptions
        error_log("Error deleting table $table_name: " . $e->getMessage());
        return false;
    }
}