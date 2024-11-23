<?php
// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Delete plugin options
delete_option( 'login_branding_logo_url' );
delete_option( 'login_branding_logo_link' );
delete_option( 'login_branding_footer_text' );
delete_option( 'login_branding_background_image_path' );
delete_option( 'login_branding_background_color' );
