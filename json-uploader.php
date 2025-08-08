<?php
/**
 * Plugin Name: JSON File Uploader
 * Plugin URI: https://github.com/toatikbd/JSON-File-Uploader
 * Description: A WordPress plugin that allows administrators to upload JSON files to the media library and parse their content from the WordPress dashboard.
 * Version: 1.0.0
 * Author: Atiqur Rahaman
 * License: GPL v2 or later
 * Text Domain: json-file-uploader
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('JSON_UPLOADER_VERSION', '1.0.0');
define('JSON_UPLOADER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JSON_UPLOADER_PLUGIN_PATH', plugin_dir_path(__FILE__));

class JSON_Uploader {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_json_uploader_toggle_upload', array($this, 'toggle_upload_status'));
        add_filter('upload_mimes', array($this, 'add_json_mime_type'));
        register_activation_hook(__FILE__, array($this, 'activate_plugin'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate_plugin'));
    }
    
    /**
     * Plugin activation hook
     */
    public function activate_plugin() {
        // Set default upload status to enabled
        add_option('json_uploader_enabled', '1');
    }
    
    /**
     * Plugin deactivation hook
     */
    public function deactivate_plugin() {
        // Clean up if needed
    }
    
    /**
     * Add JSON MIME type to allowed upload types
     */
    public function add_json_mime_type($mimes) {
        // Only allow JSON uploads if the plugin is enabled
        if (get_option('json_uploader_enabled', '1') === '1') {
            $mimes['json'] = 'application/json';
        }
        return $mimes;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_management_page(
            __('Upload JSON', 'json-uploader'),
            __('Upload JSON', 'json-uploader'),
            'manage_options',
            'json-uploader',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'tools_page_json-uploader') {
            return;
        }
        
        wp_enqueue_script(
            'json-uploader-admin',
            JSON_UPLOADER_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            JSON_UPLOADER_VERSION,
            true
        );
        
        wp_enqueue_style(
            'json-uploader-admin',
            JSON_UPLOADER_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            JSON_UPLOADER_VERSION
        );
        
        wp_localize_script('json-uploader-admin', 'jsonUploaderAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('json_uploader_nonce'),
            'strings' => array(
                'confirmToggle' => __('Are you sure you want to change the upload status?', 'json-uploader')
            )
        ));
    }
    

    
    /**
     * Get server configuration information
     */
    private function get_server_configuration_info() {
        $info = array();
        
        // Check upload limits
        $info['upload_max_filesize'] = ini_get('upload_max_filesize');
        $info['post_max_size'] = ini_get('post_max_size');
        $info['max_execution_time'] = ini_get('max_execution_time');
        $info['memory_limit'] = ini_get('memory_limit');
        
        // Check if JSON MIME type is allowed
        $allowed_mimes = get_allowed_mime_types();
        $info['json_allowed'] = isset($allowed_mimes['json']);
        
        return $info;
    }
    
    /**
     * Toggle upload status via AJAX
     */
    public function toggle_upload_status() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'json_uploader_nonce')) {
            wp_die(__('Security check failed.', 'json-uploader'));
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions.', 'json-uploader'));
        }
        
        $current_status = get_option('json_uploader_enabled', '1');
        $new_status = $current_status === '1' ? '0' : '1';
        update_option('json_uploader_enabled', $new_status);
        
        wp_send_json_success(array(
            'status' => $new_status,
            'message' => $new_status === '1' ? __('Upload enabled.', 'json-uploader') : __('Upload disabled.', 'json-uploader')
        ));
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'json-uploader'));
        }
        
                 $upload_enabled = get_option('json_uploader_enabled', '1') === '1';
        
        // Check server configuration
        $server_info = $this->get_server_configuration_info();
        
        ?>
        <div class="wrap">
            <h1><?php _e('JSON Uploader', 'json-uploader'); ?></h1>
            
                         <!-- Server Configuration Info -->
             <div class="server-config-section">
                 <h2><?php _e('Server Configuration', 'json-uploader'); ?></h2>
                 <table class="widefat">
                     <tr>
                         <th><?php _e('Upload Max Filesize:', 'json-uploader'); ?></th>
                         <td><?php echo esc_html($server_info['upload_max_filesize']); ?></td>
                     </tr>
                     <tr>
                         <th><?php _e('POST Max Size:', 'json-uploader'); ?></th>
                         <td><?php echo esc_html($server_info['post_max_size']); ?></td>
                     </tr>
                     <tr>
                         <th><?php _e('JSON Files Allowed:', 'json-uploader'); ?></th>
                         <td>
                             <?php if ($server_info['json_allowed']): ?>
                                 <span style="color: green;">✓ <?php _e('Yes', 'json-uploader'); ?></span>
                             <?php else: ?>
                                 <span style="color: red;">✗ <?php _e('No - This may cause upload issues', 'json-uploader'); ?></span>
                             <?php endif; ?>
                         </td>
                     </tr>
                 </table>
                 <?php if (!$server_info['json_allowed']): ?>
                     <div class="notice notice-warning">
                         <p><strong><?php _e('Warning:', 'json-uploader'); ?></strong> <?php _e('JSON files are not currently allowed by your server configuration. The plugin will attempt to add support, but you may need to contact your hosting provider.', 'json-uploader'); ?></p>
                     </div>
                 <?php endif; ?>
             </div>
             
             <!-- Upload Status Toggle -->
             <div class="upload-status-section">
                 <h2><?php _e('Upload Settings', 'json-uploader'); ?></h2>
                 <p>
                     <label for="upload-toggle">
                         <input type="checkbox" id="upload-toggle" <?php checked($upload_enabled); ?> />
                         <?php _e('Enable JSON uploads', 'json-uploader'); ?>
                     </label>
                 </p>
                 <p class="description">
                     <?php _e('When disabled, users will not be able to upload JSON files.', 'json-uploader'); ?>
                 </p>
             </div>
            
                         <!-- Upload Instructions -->
             <div class="upload-form-section">
                 <h2><?php _e('Upload JSON Files', 'json-uploader'); ?></h2>
                 
                 <?php if (!$upload_enabled): ?>
                     <div class="notice notice-warning">
                         <p><?php _e('JSON upload functionality is currently disabled. Enable it above to upload JSON files to the Media Library.', 'json-uploader'); ?></p>
                     </div>
                 <?php else: ?>
                     <div class="upload-instructions">
                         <p><strong><?php _e('How to upload JSON files:', 'json-uploader'); ?></strong></p>
                         <ol>
                             <li><?php _e('Go to Media Library (Media → Add New)', 'json-uploader'); ?></li>
                             <li><?php _e('Click "Select Files" or drag and drop your JSON file', 'json-uploader'); ?></li>
                             <li><?php _e('The file will be uploaded and added to your Media Library', 'json-uploader'); ?></li>
                             <li><?php _e('You can then view and manage JSON files like any other media file', 'json-uploader'); ?></li>
                         </ol>
                         <p class="description">
                             <?php _e('Maximum file size: 5MB. Only .json files are allowed.', 'json-uploader'); ?>
                         </p>
                         <p>
                             <a href="<?php echo admin_url('upload.php'); ?>" class="button button-primary">
                                 <?php _e('Go to Media Library', 'json-uploader'); ?>
                             </a>
                         </p>
                     </div>
                 <?php endif; ?>
             </div>
            
            
        </div>
        <?php
    }
}

// Initialize the plugin
new JSON_Uploader();
