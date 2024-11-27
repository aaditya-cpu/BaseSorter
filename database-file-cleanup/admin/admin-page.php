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

<script>
jQuery(document).ready(function ($) {
    // Select All Databases Checkbox
    $('#select-all-databases').on('change', function () {
        $('.database-checkbox').prop('checked', this.checked);
    });

    // Select All Files Checkbox
    $('#select-all-files').on('change', function () {
        $('.file-checkbox').prop('checked', this.checked);
    });

    // Delete Selected Items
    $('#delete-selected').on('click', function () {
        let selectedDatabases = [];
        let selectedFiles = [];

        // Collect selected databases
        $('.database-checkbox:checked').each(function () {
            selectedDatabases.push($(this).data('database-name'));
        });

        // Collect selected files
        $('.file-checkbox:checked').each(function () {
            selectedFiles.push($(this).data('file-path'));
        });

        if (selectedDatabases.length === 0 && selectedFiles.length === 0) {
            alert('No items selected for deletion.');
            return;
        }

        if (!confirm('Are you sure you want to delete the selected items? This action cannot be undone.')) {
            return;
        }

        // AJAX call to delete items
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_selected_items',
                databases: selectedDatabases,
                files: selectedFiles,
            },
            beforeSend: function () {
                $('#loader').show();
            },
            success: function (response) {
                $('#loader').hide();
                if (response.success) {
                    alert(response.data.message);
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function () {
                $('#loader').hide();
                alert('An error occurred while attempting to delete the selected items.');
            },
        });
    });
});
</script>