<?php
/**
 * File:            uninstall.php
 * Version:         1.1.1
 * Last Modified:   2024-12-03
 * Description:     Clean up all plugin options and related data during uninstallation.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit; // Exit if accessed directly.
}

// Plugin options to delete
$options = [
    'login_branding_logo_url',
    'login_branding_background_image_path',
    'login_branding_background_color',
    'login_branding_first_column_bg_color',
    'login_branding_text_color',
    'login_branding_input_bg_color',
    'login_branding_input_text_color',
    'login_branding_button_color',
    'login_branding_button_hover_color',
    'login_branding_button_text_color',
    'login_branding_link_hover_color',
    'login_branding_footer_text',
    'login_branding_logo_link',
];

// Remove each option from the database
foreach ($options as $option) {
    delete_option($option);
}

// Remove options stored in site-wide settings for multisite installations
if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        foreach ($options as $option) {
            delete_option($option);
        }
        restore_current_blog();
    }
}
