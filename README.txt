=== JSON Uploader ===
Contributors: Atiqur Rahaman
Tags: json, upload, admin, tools, file management
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that allows administrators to upload and parse JSON files from the WordPress dashboard with advanced security features and upload management.

== Description ==

JSON Uploader is a comprehensive WordPress plugin designed for administrators who need to upload and manage JSON files directly from the WordPress dashboard. The plugin provides a secure, user-friendly interface with advanced features for file validation, content parsing, and upload management.

= Key Features =

* **Secure File Upload**: Restricted to JSON files only with comprehensive validation
* **File Size Control**: Maximum 5MB file size limit with customizable settings
* **Upload Management**: Toggle upload functionality on/off for administrative control
* **JSON Content Preview**: Automatic parsing and formatted display of JSON content
* **Download Links**: Easy access to uploaded files with secure download links
* **Security Features**: WordPress nonces, capability checks, and file type validation
* **Responsive Design**: Mobile-friendly admin interface
* **Error Handling**: Comprehensive error messages and validation feedback

= Security Features =

* WordPress nonce verification for all form submissions
* User capability checks (requires 'manage_options' capability)
* File type validation (JSON only)
* File size restrictions
* Secure file storage in protected directory
* AJAX security with nonce verification

= File Storage =

Uploaded files are stored in the WordPress Media Library:
* Files are added as attachments in the WordPress database
* Accessible through the standard Media Library interface
* Organized by upload date and file type
* Can be managed like any other media file

== Installation ==

1. Upload the `json-uploader` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Tools' > 'Upload JSON' in the WordPress admin
4. Configure upload settings and start uploading JSON files

= Manual Installation =

1. Download the plugin files
2. Extract the ZIP file
3. Upload the `json-uploader` folder to `/wp-content/plugins/`
4. Activate the plugin in WordPress admin

= Requirements =

* WordPress 5.0 or higher
* PHP 7.4 or higher
* Administrator privileges for plugin usage

== Frequently Asked Questions ==

= Can non-administrators use this plugin? =

No, this plugin is designed for administrators only. Users must have the 'manage_options' capability to access the upload functionality.

= What file types are supported? =

Only JSON files (.json extension) are supported. The plugin validates both file extension and MIME type.

= What is the maximum file size? =

The maximum file size is 5MB. This can be modified in the plugin code if needed.

= Where are uploaded files stored? =

Files are stored in the WordPress Media Library and can be accessed through the Media Library interface.

= Can I disable uploads temporarily? =

Yes, use the "Enable JSON uploads" toggle in the plugin settings to enable/disable upload functionality.

= Is the JSON content validated? =

Yes, the plugin validates JSON syntax and will reject invalid JSON files.

= Are uploaded files secure? =

Yes, files are stored in the WordPress Media Library with proper WordPress file handling and database integration.

== Screenshots ==

1. Main upload interface with file selection
2. Upload settings toggle
3. File information display
4. JSON content preview
5. Error handling and validation

== Changelog ==

= 1.0.0 =
* Initial release
* File upload functionality
* JSON content parsing and display
* Upload toggle feature
* Security features implementation
* Responsive admin interface

== Upgrade Notice ==

= 1.0.0 =
Initial release of JSON Uploader plugin.

== Usage ==

### Basic Usage

1. **Access the Plugin**: Go to 'Tools' > 'Upload JSON' in WordPress admin
2. **Enable Uploads**: Ensure the "Enable JSON uploads" toggle is checked
3. **Select File**: Choose a JSON file (max 5MB)
4. **Upload**: Click "Upload JSON File" button
5. **View Results**: See file information and JSON content preview

### Advanced Features

**Upload Management**
- Toggle upload functionality on/off using the checkbox in settings
- When disabled, users see a warning message
- Changes are applied immediately via AJAX

**File Validation**
- Automatic file type checking (.json extension)
- File size validation (5MB limit)
- JSON syntax validation
- Comprehensive error messages

**Content Display**
- Formatted JSON preview with syntax highlighting
- Collapsible content sections
- Download links for uploaded files
- Direct links to Media Library
- File information table

### Security Considerations

- Only administrators can access the plugin
- All form submissions use WordPress nonces
- File uploads are validated and sanitized
- Uploaded files are stored in a protected directory
- AJAX requests include security verification

### Customization

The plugin can be customized by modifying:
- File size limits in the main plugin file
- Allowed file types
- Admin interface styling
- Error messages and notifications

== Support ==

For support, feature requests, or bug reports, please contact the plugin developer or create an issue in the plugin repository.

== Credits ==

Developed with WordPress coding standards and best practices for security and performance.
