jQuery(document).ready(function ($) {
    $('#delete-selected').on('click', function () {
        $('#select-all-files').on('change', function () {
            $('.file-checkbox').prop('checked', $(this).prop('checked'));
        });
        let selectedItems = []; // Gather selected items

        // Collect selected databases
        $('.database-checkbox:checked').each(function () {
            selectedItems.push({
                type: 'database',
                name: $(this).data('database-name'),
            });
        });

        // Collect selected files
        $('.file-checkbox:checked').each(function () {
            selectedItems.push({
                type: 'file',
                path: $(this).data('file-path'),
            });
        });

        if (selectedItems.length > 0) {
            // Confirm deletion
            if (!confirm('Are you sure you want to delete the selected items?')) {
                return;
            }

            $.ajax({
                url: dbFileCleanup.ajax_url,
                method: 'POST',
                data: {
                    action: 'delete_selected_items',
                    nonce: dbFileCleanup.nonce,
                    items: selectedItems,
                },
                beforeSend: function () {
                    $('#loader').show(); // Show loading animation
                },
                success: function (response) {
                    $('#loader').hide(); // Hide loading animation
                    if (response.success) {
                        alert(response.data.message);
                        // Optionally refresh the page or remove deleted items from the DOM
                        location.reload();
                    } else {
                        alert(response.data.message || 'An error occurred.');
                    }
                },
                error: function () {
                    $('#loader').hide();
                    alert('Failed to delete selected items. Please try again.');
                },
            });
        } else {
            alert('No items selected for deletion.');
        }
    });
});
