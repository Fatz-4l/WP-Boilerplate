<?php

/**
 * Logo Carousel Settings
 * Modular settings page for logo carousel configuration
 */

// Add logo carousel submenu page
function add_logo_carousel_submenu() {
    add_submenu_page(
        'theme-settings',                    // Parent slug (matches main menu slug)
        'Logo Carousel Settings',            // Page title
        'Logo Carousel',                     // Menu title
        'manage_options',                    // Capability
        'logo-carousel-settings',            // Menu slug
        'logo_carousel_settings_page'    // Callback function
    );
}

// Logo carousel settings page callback
function logo_carousel_settings_page() {
    // Handle clear unused logos action
    if (isset($_POST['clear_unused_logos'])) {
        check_admin_referer('logo_carousel_clear_unused');

        $cleared_count = clear_unused_logo_options();
        echo '<div class="notice notice-success"><p>Cleared ' . $cleared_count . ' unused logo entries from database!</p></div>';
    }

    // Handle form submission
    if (isset($_POST['submit'])) {
        check_admin_referer('logo_carousel_settings');

        // Clear existing logos first
        $existing_logos = get_option('logo_carousel_logos', []);
        foreach ($existing_logos as $index => $logo) {
            delete_option("logo_carousel_image_{$index}");
            delete_option("logo_carousel_height_mobile_{$index}");
            delete_option("logo_carousel_height_desktop_{$index}");
            delete_option("logo_carousel_width_{$index}");
        }

        // Save new logo data
        $logos_data = [];
        if (isset($_POST['logos']) && is_array($_POST['logos'])) {
            foreach ($_POST['logos'] as $index => $logo_data) {
                if (!empty($logo_data['image'])) {
                    $logos_data[$index] = [
                        'image' => sanitize_text_field($logo_data['image']),
                        'height_mobile' => floatval($logo_data['height_mobile'] ?? 1.5),
                        'height_desktop' => floatval($logo_data['height_desktop'] ?? 2),
                        'width' => sanitize_text_field($logo_data['width'] ?? 'auto')
                    ];

                    // Also save individual options for backward compatibility
                    update_option("logo_carousel_image_{$index}", $logos_data[$index]['image']);
                    update_option("logo_carousel_height_mobile_{$index}", $logos_data[$index]['height_mobile']);
                    update_option("logo_carousel_height_desktop_{$index}", $logos_data[$index]['height_desktop']);
                    update_option("logo_carousel_width_{$index}", $logos_data[$index]['width']);
                }
            }
        }

        // Save the logos array
        update_option('logo_carousel_logos', $logos_data);

        echo '<div class="notice notice-success"><p>Logo carousel settings saved!</p></div>';
    }

        // Get existing logos
    $logos = get_option('logo_carousel_logos', []);

    // Ensure at least one logo field is shown
    if (empty($logos)) {
        $logos = [0 => ['image' => '', 'height_mobile' => 1.5, 'height_desktop' => 2, 'width' => 'auto']];
    }

    ?>
<div class="wrap">
   <h1>Logo Carousel Settings</h1>
   <form method="post" action="">
      <?php wp_nonce_field('logo_carousel_settings'); ?>

      <div class="postbox">
         <div class="postbox-header">
            <h2 style="color:#E8B45C;">Section - Logo Carousel</h2>
         </div>
         <div class="inside">
            <div id="logo-repeater" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
               <?php foreach ($logos as $index => $logo): ?>
               <div class="logo-item" data-index="<?php echo $index; ?>" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px; background: #fafafa; break-inside: avoid;">
                  <div class="logo-item-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
                     <h4 style="margin: 0; font-size: 14px; font-weight: 600;">Logo <?php echo ($index + 1); ?></h4>
                     <button type="button" class="button-link remove-logo" style="color: #dc3232; text-decoration: none; font-size: 12px;">✕ Remove</button>
                  </div>
                  <div style="display: grid; grid-template-columns: 120px 1fr; gap: 10px; align-items: start;">
                     <div>
                        <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Logo Image</label>
                        <div style="border: 1px solid #ddd; padding: 6px; border-radius: 4px; background: #fff; min-height: 50px; display: flex; align-items: center; justify-content: center; position: relative;">
                           <?php if (!empty($logo['image'])): ?>
                           <img src="<?php echo esc_url($logo['image']); ?>"
                                style="max-width: 100%; max-height: 40px; object-fit: contain;"
                                class="logo-preview-img" />
                           <button type="button" class="button button-small change-image-button"
                                   data-target="<?php echo $index; ?>"
                                   style="position: absolute; top: 2px; right: 2px; min-height: 20px; padding: 1px 6px; font-size: 10px;">
                              Change
                           </button>
                           <?php else: ?>
                           <div class="upload-placeholder" style="text-align: center; color: #666;">
                              <span style="font-size: 11px;">No image selected</span><br>
                              <button type="button" class="button button-small upload-button" data-target="<?php echo $index; ?>">Upload Image</button>
                           </div>
                           <?php endif; ?>
                        </div>
                        <input type="hidden"
                               name="logos[<?php echo $index; ?>][image]"
                               value="<?php echo esc_url($logo['image']); ?>"
                               class="logo-image-input" />
                     </div>
                     <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                        <div>
                           <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Mobile (rem)</label>
                           <input type="number"
                                  name="logos[<?php echo $index; ?>][height_mobile]"
                                  value="<?php echo esc_attr($logo['height_mobile']); ?>"
                                  step="0.1"
                                  min="0.5"
                                  max="10"
                                  class="small-text"
                                  style="width: 100%;" />
                        </div>
                        <div>
                           <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Desktop (rem)</label>
                           <input type="number"
                                  name="logos[<?php echo $index; ?>][height_desktop]"
                                  value="<?php echo esc_attr($logo['height_desktop']); ?>"
                                  step="0.1"
                                  min="0.5"
                                  max="10"
                                  class="small-text"
                                  style="width: 100%;" />
                        </div>
                        <div>
                           <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Width</label>
                           <input type="text"
                                  name="logos[<?php echo $index; ?>][width]"
                                  value="<?php echo esc_attr($logo['width']); ?>"
                                  class="small-text"
                                  placeholder="auto"
                                  style="width: 100%;" />
                        </div>
                     </div>
                  </div>
               </div>
               <?php endforeach; ?>
            </div>

            <div style="margin-bottom: 15px; margin-top: 15px;">
               <button type="button" id="bulk-upload-logos" class="button button-primary">
                  <span class="dashicons dashicons-images-alt2" style="vertical-align: middle;"></span>
                  Select Multiple Logos
               </button>
               <button type="button" id="add-logo" class="button button-secondary" style="margin-left: 10px;">
                  <span class="dashicons dashicons-plus-alt" style="vertical-align: middle;"></span>
                  Add Single Logo
               </button>
            </div>
         </div>
      </div>

      <?php submit_button(); ?>
   </form>

   <!-- Clear Unused Logos Section -->
   <div class="postbox" style="margin-top: 20px;">
      <div class="postbox-header">
         <h2>Database Cleanup</h2>
      </div>
      <div class="inside">
         <p>Remove orphaned logo data from the database that may remain after deleting logos.</p>
         <form method="post" action="" style="display: inline-block;">
            <?php wp_nonce_field('logo_carousel_clear_unused'); ?>
            <button type="submit" name="clear_unused_logos" class="button button-secondary"
                    onclick="return confirm('Are you sure you want to clear unused logo data from the database? This cannot be undone.');">
               <span class="dashicons dashicons-trash" style="vertical-align: middle;"></span>
               Clear Unused Logo Data
            </button>
         </form>
         <p class="description">
            This will scan the database for any logo_carousel_* options that are not currently in use and remove them.
            <strong>This action cannot be undone.</strong>
         </p>
      </div>
   </div>
</div>

<script>
jQuery(document).ready(function($) {
   var logoIndex = <?php echo count($logos); ?>;
   // Bulk media uploader functionality
   $('#bulk-upload-logos').click(function(e) {
      e.preventDefault();
      var bulkMediaUploader = wp.media({
         title: 'Select Multiple Logo Images',
         button: {
            text: 'Add Selected Logos'
         },
         multiple: true
      });
      bulkMediaUploader.on('select', function() {
         var attachments = bulkMediaUploader.state().get('selection').toJSON();
         // Loop through selected images and create logo fields
         attachments.forEach(function(attachment) {
            addLogoWithImage(attachment.url);
         });
         updateLogoNumbers();
      });
      bulkMediaUploader.open();
   });
   // Single media uploader functionality for both upload and change buttons
   $(document).on('click', '.upload-button, .change-image-button', function(e) {
      e.preventDefault();
      var targetIndex = $(this).data('target');
      var logoItem = $(this).closest('.logo-item');
      var targetInput = logoItem.find('.logo-image-input');
      var imageContainer = logoItem.find('.logo-preview-img').parent();
      var mediaUploader = wp.media({
         title: 'Select Logo Image',
         button: {
            text: 'Use This Image'
         },
         multiple: false
      });
      mediaUploader.on('select', function() {
         var attachment = mediaUploader.state().get('selection').first().toJSON();
         targetInput.val(attachment.url);
         // Update the image preview
         updateImagePreview(logoItem, attachment.url, targetIndex);
      });
      mediaUploader.open();
   });
   // Add single logo functionality
   $('#add-logo').click(function() {
      var newLogoHtml = `
                <div class="logo-item" data-index="${logoIndex}">
                    <div class="logo-item-header">
                        <h3>Logo ${logoIndex + 1}</h3>
                        <button type="button" class="button-link remove-logo" style="color: #dc3232;">Remove</button>
                    </div>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label>Image URL</label>
                            </th>
                            <td>
                                <input type="url"
                                       name="logos[${logoIndex}][image]"
                                       value=""
                                       class="regular-text logo-image-input"
                                       placeholder="https://example.com/logo.svg" />
                                <button type="button" class="button upload-button" data-target="${logoIndex}">Upload Image</button>
                                <p class="description">Upload or enter the URL for this logo</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Height Mobile (rem)</label>
                            </th>
                            <td>
                                <input type="number"
                                       name="logos[${logoIndex}][height_mobile]"
                                       value="1.5"
                                       step="0.1"
                                       min="0.5"
                                       max="10"
                                       class="small-text" /> rem
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Height Desktop (rem)</label>
                            </th>
                            <td>
                                <input type="number"
                                       name="logos[${logoIndex}][height_desktop]"
                                       value="2"
                                       step="0.1"
                                       min="0.5"
                                       max="10"
                                       class="small-text" /> rem
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Width</label>
                            </th>
                            <td>
                                <input type="text"
                                       name="logos[${logoIndex}][width]"
                                       value="auto"
                                       class="small-text"
                                       placeholder="auto" />
                                <p class="description">Width of logo (e.g., auto, 100px, 5rem)</p>
                            </td>
                        </tr>
                    </table>
                    <hr style="margin: 20px 0;" />
                </div>
            `;
      $('#logo-repeater').append(newLogoHtml);
      logoIndex++;
      updateLogoNumbers();
   });
   // Remove logo functionality
   $(document).on('click', '.remove-logo', function() {
      if ($('.logo-item').length > 1) {
         $(this).closest('.logo-item').remove();
         updateLogoNumbers();
      } else {
         alert('At least one logo must remain.');
      }
   });
   // Add logo with pre-filled image URL
   function addLogoWithImage(imageUrl) {
      var newLogoHtml = `
                <div class="logo-item" data-index="${logoIndex}" style="border: 1px solid #ddd; padding: 12px; border-radius: 4px; background: #fafafa; break-inside: avoid;">
                    <div class="logo-item-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #ddd;">
                        <h4 style="margin: 0; font-size: 14px; font-weight: 600;">Logo ${logoIndex + 1}</h4>
                        <button type="button" class="button-link remove-logo" style="color: #dc3232; text-decoration: none; font-size: 12px;">✕ Remove</button>
                    </div>
                    <div style="display: grid; grid-template-columns: 120px 1fr; gap: 10px; align-items: start;">
                        <div>
                            <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Logo Image</label>
                            <div style="border: 1px solid #ddd; padding: 6px; border-radius: 4px; background: #fff; min-height: 50px; display: flex; align-items: center; justify-content: center; position: relative;">
                                ${imageUrl ? `
                                    <img src="${imageUrl}"
                                         style="max-width: 100%; max-height: 40px; object-fit: contain;"
                                         class="logo-preview-img" />
                                    <button type="button" class="button button-small change-image-button"
                                            data-target="${logoIndex}"
                                            style="position: absolute; top: 2px; right: 2px; min-height: 20px; padding: 1px 6px; font-size: 10px;">
                                        Change
                                    </button>
                                ` : `
                                    <div class="upload-placeholder" style="text-align: center; color: #666;">
                                        <span style="font-size: 11px;">No image selected</span><br>
                                        <button type="button" class="button button-small upload-button" data-target="${logoIndex}">Upload Image</button>
                                    </div>
                                `}
                            </div>
                            <input type="hidden"
                                   name="logos[${logoIndex}][image]"
                                   value="${imageUrl}"
                                   class="logo-image-input" />
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Mobile (rem)</label>
                                <input type="number"
                                       name="logos[${logoIndex}][height_mobile]"
                                       value="1.5"
                                       step="0.1"
                                       min="0.5"
                                       max="10"
                                       class="small-text"
                                       style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Desktop (rem)</label>
                                <input type="number"
                                       name="logos[${logoIndex}][height_desktop]"
                                       value="2"
                                       step="0.1"
                                       min="0.5"
                                       max="10"
                                       class="small-text"
                                       style="width: 100%;" />
                            </div>
                            <div>
                                <label style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 12px;">Width</label>
                                <input type="text"
                                       name="logos[${logoIndex}][width]"
                                       value="auto"
                                       class="small-text"
                                       placeholder="auto"
                                       style="width: 100%;" />
                            </div>
                        </div>
                    </div>
                </div>
            `;
      $('#logo-repeater').append(newLogoHtml);
      logoIndex++;
   }
   // Update image preview when new image is selected
   function updateImagePreview(logoItem, imageUrl, targetIndex) {
      var imageContainer = logoItem.find('div[style*="min-height: 50px"]');
      var newImageHtml = `
            <img src="${imageUrl}"
                 style="max-width: 100%; max-height: 40px; object-fit: contain;"
                 class="logo-preview-img" />
            <button type="button" class="button button-small change-image-button"
                    data-target="${targetIndex}"
                    style="position: absolute; top: 2px; right: 2px; min-height: 20px; padding: 1px 6px; font-size: 10px;">
                Change
            </button>
        `;
      imageContainer.html(newImageHtml);
   }
   // Update logo numbers and reindex form fields
   function updateLogoNumbers() {
      $('.logo-item').each(function(index) {
         // Update visual logo number
         $(this).find('h4').text('Logo ' + (index + 1));
         $(this).attr('data-index', index);
         // Update all form field names to use the new index
         $(this).find('input[name*="[image]"]').attr('name', 'logos[' + index + '][image]');
         $(this).find('input[name*="[height_mobile]"]').attr('name', 'logos[' + index + '][height_mobile]');
         $(this).find('input[name*="[height_desktop]"]').attr('name', 'logos[' + index + '][height_desktop]');
         $(this).find('input[name*="[width]"]').attr('name', 'logos[' + index + '][width]');
         // Update upload button data-target
         $(this).find('.upload-button').attr('data-target', index);
      });
      // Reset logoIndex to the current count of logos
      logoIndex = $('.logo-item').length;
   }
});
</script>
<?php
}

// Enqueue media uploader for logo carousel settings page
function logo_carousel_enqueue_admin_scripts($hook) {
    if ($hook === 'theme-settings_page_logo-carousel-settings') {
        wp_enqueue_media();
        wp_enqueue_script('jquery');
    }
}
add_action('admin_enqueue_scripts', 'logo_carousel_enqueue_admin_scripts');

// Function to clear unused logo options from database
function clear_unused_logo_options() {
    global $wpdb;

    // Get current logos array to see which indices are in use
    $current_logos = get_option('logo_carousel_logos', []);
    $used_indices = array_keys($current_logos);

    // Find all logo_carousel_* options in database
    $logo_options = $wpdb->get_results(
        "SELECT option_name FROM {$wpdb->options}
         WHERE option_name LIKE 'logo_carousel_image_%'
         OR option_name LIKE 'logo_carousel_height_mobile_%'
         OR option_name LIKE 'logo_carousel_height_desktop_%'
         OR option_name LIKE 'logo_carousel_width_%'",
        ARRAY_A
    );

    $cleared_count = 0;

    foreach ($logo_options as $option) {
        $option_name = $option['option_name'];

        // Extract index from option name (e.g., logo_carousel_image_5 -> 5)
        if (preg_match('/logo_carousel_(?:image|height_mobile|height_desktop|width)_(\d+)/', $option_name, $matches)) {
            $index = intval($matches[1]);

            // If this index is not in current logos array, delete it
            if (!in_array($index, $used_indices)) {
                delete_option($option_name);
                $cleared_count++;
            }
        }
    }

    // Also clean up any legacy numbered options (1-100 range for safety)
    for ($i = 1; $i <= 100; $i++) {
        if (!in_array($i, $used_indices)) {
            $legacy_options = [
                "logo_carousel_image_{$i}",
                "logo_carousel_height_mobile_{$i}",
                "logo_carousel_height_desktop_{$i}",
                "logo_carousel_width_{$i}"
            ];

            foreach ($legacy_options as $legacy_option) {
                if (get_option($legacy_option) !== false) {
                    delete_option($legacy_option);
                    $cleared_count++;
                }
            }
        }
    }

    return $cleared_count;
}

?>