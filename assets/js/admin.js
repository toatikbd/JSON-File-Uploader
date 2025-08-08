jQuery(document).ready(function($) {
    
    // Handle upload toggle
    $('#upload-toggle').on('change', function() {
        var isChecked = $(this).is(':checked');
        var newStatus = isChecked ? '1' : '0';
        
        if (confirm(jsonUploaderAjax.strings.confirmToggle)) {
            $.ajax({
                url: jsonUploaderAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'json_uploader_toggle_upload',
                    nonce: jsonUploaderAjax.nonce,
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        var message = response.data.message;
                        var noticeClass = isChecked ? 'notice-success' : 'notice-warning';
                        
                        // Remove existing notices
                        $('.json-uploader-notice').remove();
                        
                        // Add new notice
                        var notice = $('<div class="notice ' + noticeClass + ' json-uploader-notice"><p>' + message + '</p></div>');
                        $('.wrap h1').after(notice);
                        
                        // Auto-hide notice after 3 seconds
                        setTimeout(function() {
                            $('.json-uploader-notice').fadeOut();
                        }, 3000);
                        
                        // Reload page to update form state
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        // Revert checkbox state on error
                        $('#upload-toggle').prop('checked', !isChecked);
                        alert('Error: ' + (response.data ? response.data : 'Unknown error occurred'));
                    }
                },
                error: function() {
                    // Revert checkbox state on error
                    $('#upload-toggle').prop('checked', !isChecked);
                    alert('Error: Failed to update upload status');
                }
            });
        } else {
            // Revert checkbox state if user cancels
            $(this).prop('checked', !isChecked);
        }
    });
    
    // File input validation
    $('#json_file').on('change', function() {
        var file = this.files[0];
        var maxSize = 5 * 1024 * 1024; // 5MB
        
        if (file) {
            // Check file extension
            if (!file.name.toLowerCase().endsWith('.json')) {
                alert('Please select a valid JSON file (.json extension)');
                this.value = '';
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                alert('File size exceeds the maximum limit of 5MB');
                this.value = '';
                return;
            }
            
            // Show file info
            var fileInfo = 'Selected file: ' + file.name + ' (' + formatFileSize(file.size) + ')';
            $('.file-info-display').remove();
            $('<div class="file-info-display description">' + fileInfo + '</div>').insertAfter($(this));
        }
    });
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Form submission validation
    $('.json-upload-form').on('submit', function(e) {
        var fileInput = $('#json_file')[0];
        
        if (!fileInput.files.length) {
            alert('Please select a JSON file to upload');
            e.preventDefault();
            return false;
        }
        
        var file = fileInput.files[0];
        var maxSize = 5 * 1024 * 1024; // 5MB
        
        // Double-check file type and size
        if (!file.name.toLowerCase().endsWith('.json')) {
            alert('Please select a valid JSON file (.json extension)');
            e.preventDefault();
            return false;
        }
        
        if (file.size > maxSize) {
            alert('File size exceeds the maximum limit of 5MB');
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        var submitButton = $(this).find('input[type="submit"]');
        var originalText = submitButton.val();
        submitButton.val('Uploading...').prop('disabled', true);
        
        // Re-enable button after 5 seconds as fallback
        setTimeout(function() {
            submitButton.val(originalText).prop('disabled', false);
        }, 5000);
    });
    
    // Auto-hide notices after 5 seconds
    setTimeout(function() {
        $('.notice').fadeOut();
    }, 5000);
    
    // Make JSON content collapsible
    $('.json-content h3').on('click', function() {
        $(this).next('.json-display').slideToggle();
        $(this).toggleClass('collapsed');
    });
    
    // Add collapse/expand functionality to JSON display
    $('.json-content h3').each(function() {
        var $this = $(this);
        var $display = $this.next('.json-display');
        
        // Add collapse/expand icon
        $this.prepend('<span class="dashicons dashicons-arrow-down" style="margin-right: 5px; cursor: pointer;"></span>');
        
        // Handle click on icon
        $this.find('.dashicons').on('click', function(e) {
            e.stopPropagation();
            $display.slideToggle();
            $this.toggleClass('collapsed');
            $(this).toggleClass('dashicons-arrow-down dashicons-arrow-right');
        });
    });
    
});
