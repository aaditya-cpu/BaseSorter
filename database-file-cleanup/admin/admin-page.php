<div class="wrap">
    <h1>Database & File Cleanup Utility</h1>
    <div class="glass-container">
        <h2>Database Analysis</h2>
        <p>Review abandoned tables below and safely delete them to free up space.</p>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-databases"></th>
                    <th>Database Table Name</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody id="database-list">
                <?php
                // Fetch abandoned databases dynamically
                $databases = scan_for_abandoned_databases(); // Function from db-functions.php
                if (!empty($databases)) {
                    foreach ($databases as $database) {
                        echo '<tr>
                                <td><input type="checkbox" class="database-checkbox" data-database-name="' . esc_attr($database) . '"></td>
                                <td>' . esc_html($database) . '</td>
                                <td>' . esc_html('Unknown') . '</td>
                              </tr>';
                    }
                } else {
                    echo '<tr>
                            <td colspan="3">' . esc_html__('No abandoned databases found.', 'db-file-cleanup') . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <h2>Duplicate Files</h2>
        <p>Review duplicate media files and delete to save storage.</p>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-files"></th>
                    <th>File Name</th>
                    <th>Location</th>
                    <th>Size</th>
                </tr>
            </thead>
            <tbody id="file-list">
                <?php
                // Fetch duplicate files dynamically
                $files = scan_for_duplicate_files(); // Function from file-functions.php
                if (!empty($files)) {
                    foreach ($files as $file) {
                        $file_path = $file['path'];
                        $file_size = $file['size'];

                        echo '<tr>
                                <td><input type="checkbox" class="file-checkbox" data-file-path="' . esc_attr($file_path) . '"></td>
                                <td>' . esc_html(basename($file_path)) . '</td>
                                <td>' . esc_html(dirname($file_path)) . '</td>
                                <td>' . esc_html($file_size) . '</td>
                              </tr>';
                    }
                } else {
                    echo '<tr>
                            <td colspan="4">' . esc_html__('No duplicate files found.', 'db-file-cleanup') . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <button id="delete-selected" class="button-glass">Delete Selected Items</button>
    </div>
    <div id="loader" style="display: none; text-align: center; margin-top: 20px;">
        <span>Processing...</span>
    </div>
</div>
