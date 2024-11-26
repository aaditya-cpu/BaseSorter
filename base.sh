#!/bin/bash

# Define the base directory structure
BASE_DIR="database-file-cleanup"
ASSETS_DIR="$BASE_DIR/assets"
CSS_DIR="$ASSETS_DIR/css"
JS_DIR="$ASSETS_DIR/js"
INCLUDES_DIR="$BASE_DIR/includes"
ADMIN_DIR="$BASE_DIR/admin"

CONSOLIDATED_FILE="consolidated_file_contents.txt"

# Function to create the directory structure
create_directory_structure() {
    echo "Creating directory structure..."
    mkdir -p "$CSS_DIR" "$JS_DIR" "$INCLUDES_DIR" "$ADMIN_DIR"
    touch "$CSS_DIR/glassmorphism.css"
    touch "$JS_DIR/admin.js"
    touch "$INCLUDES_DIR/class-db-file-cleanup.php"
    touch "$INCLUDES_DIR/db-functions.php"
    touch "$INCLUDES_DIR/file-functions.php"
    touch "$INCLUDES_DIR/security.php"
    touch "$ADMIN_DIR/admin-page.php"
    touch "$ADMIN_DIR/admin-ajax.php"
    touch "$BASE_DIR/database-file-cleanup.php"
    touch "$BASE_DIR/README.md"
    echo "Directory structure created successfully."
}

# Function to consolidate file contents
consolidate_file_contents() {
    echo "Consolidating file contents into $CONSOLIDATED_FILE..."
    echo -e "### Consolidated File Contents\n" > "$CONSOLIDATED_FILE"
    
    for file in $(find "$BASE_DIR" -type f | sort); do
        echo "Processing $file..."
        echo -e "\n#### File: $file\n" >> "$CONSOLIDATED_FILE"
        cat "$file" >> "$CONSOLIDATED_FILE"
        echo -e "\n---\n" >> "$CONSOLIDATED_FILE"
    done
    
    echo "Consolidation complete. Output written to $CONSOLIDATED_FILE."
}

# Main script logic
echo "Select an option:"
echo "1) Create directory structure"
echo "2) Consolidate file contents"
read -rp "Enter your choice (1/2): " choice

case $choice in
    1)
        create_directory_structure
        ;;
    2)
        if [ -d "$BASE_DIR" ]; then
            consolidate_file_contents
        else
            echo "Error: Directory structure does not exist. Please create it first."
        fi
        ;;
    *)
        echo "Invalid choice. Please select 1 or 2."
        ;;
esac
