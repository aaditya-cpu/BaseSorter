<div class="wrap">
    <h1>Database & File Cleanup Utility</h1>
    <div class="glass-container">
        <!-- Database Analysis Section -->
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
                $databases = scan_for_abandoned_databases();
                if (!empty($databases)) {
                    foreach ($databases as $database) {
                        echo '<tr>
                                <td><input type="checkbox" class="database-checkbox" data-database-name="' . esc_attr($database['name']) . '"></td>
                                <td>' . esc_html($database['name']) . '</td>
                                <td>' . esc_html($database['size']) . '</td>
                              </tr>';
                    }
                } else {
                    echo '<tr>
                            <td colspan="3">' . esc_html__('No abandoned databases found or query failed.', 'db-file-cleanup') . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Duplicate Media Files Section -->
        <h2>Duplicate Media Files</h2>
        <p>Review duplicate media files (e.g., images, PDFs) and delete to save storage.</p>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-files"></th>
                    <th>File Name</th>
                    <th>Location</th>
                    <th>Size</th>
                    <th>Last Modified</th>
                </tr>
            </thead>
            <tbody id="file-list">
                <?php
                $files = scan_for_duplicate_files();
                if (!empty($files)) {
                    foreach ($files as $file) {
                        echo '<tr>
                                <td><input type="checkbox" class="file-checkbox" data-file-path="' . esc_attr($file['path']) . '"></td>
                                <td>' . esc_html(basename($file['path'])) . '</td>
                                <td>' . esc_html(dirname($file['path'])) . '</td>
                                <td>' . esc_html($file['size']) . '</td>
                                <td>' . esc_html($file['modified']) . '</td>
                              </tr>';
                    }
                } else {
                    echo '<tr>
                            <td colspan="5">' . esc_html__('No duplicate media files found or directory is empty.', 'db-file-cleanup') . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Delete Selected Button -->
        <button id="delete-selected" class="button-glass">Delete Selected Items</button>
    </div>

    <!-- Loader -->
    <div id="loader" style="display: none; text-align: center; margin-top: 20px;">
        <span>Processing...</span>
    </div>
</div>
