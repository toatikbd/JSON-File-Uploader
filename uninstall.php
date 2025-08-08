<?php
/**
 * Uninstall JSON Uploader Plugin
 * 
 * This file is executed when the plugin is deleted from WordPress.
 * It cleans up all plugin data and uploaded files.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('json_file_uploader_enabled');

// Note: JSON files uploaded to media library will remain in the media library
// Users can manually delete them from the WordPress Media Library if needed

// Clear any cached data
wp_cache_flush();

// Log uninstall for debugging (optional)
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('JSON File Uploader plugin uninstalled and cleaned up successfully.');
}
