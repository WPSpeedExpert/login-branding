<?php
/*
Plugin Name:        Login Branding
Description:        Customize the WordPress login screen with branding options, including a two-column layout and a background image.
Version:            1.0.0
Author:             OctaHexa Media LLC
Author URI:         https://octahexa.com
Text Domain:        login-branding
Domain Path:        /languages
License:            GPLv2 or later
GitHub Plugin URI:  https://github.com/WPSpeedExpert/login-branding
GitHub Branch:      main
Primary Branch:     main
*/

// Prevent direct access to the file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Set default options upon plugin activation
function login_branding_activate() {
    $defaults = array(
        'login_branding_logo_url'            => plugin_dir_url(__FILE__) . 'img/octahexa-black-512x150-2.webp',
        'login_branding_logo_link'           => 'https://octahexa.com/',
        'login_branding_footer_text'         => 'Need help? Get in touch: support@your-domain.com',
        'login_branding_background_image_path' => plugin_dir_url(__FILE__) . 'img/background.webp',
        'login_branding_background_color'    => '#ffffff',
    );

    foreach ( $defaults as $key => $value ) {
        if ( get_option( $key ) === false ) {
            update_option( $key, $value );
        }
    }
}
register_activation_hook( __FILE__, 'login_branding_activate' );

// Create the settings page for the plugin
function login_branding_settings_page() {
    ?>
    <div class="wrap">
        <h1>Login Branding Settings</h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('login_branding_options_group');
            do_settings_sections('login_branding');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and add settings fields
function login_branding_register_settings() {
    register_setting('login_branding_options_group', 'login_branding_logo_url');
    register_setting('login_branding_options_group', 'login_branding_logo_link');
    register_setting('login_branding_options_group', 'login_branding_footer_text');
    register_setting('login_branding_options_group', 'login_branding_background_image_path');
    register_setting('login_branding_options_group', 'login_branding_background_color');

    add_settings_section('login_branding_main_section', 'Branding Options', null, 'login_branding');

    add_settings_field('login_branding_logo_url', 'Logo URL', 'login_branding_logo_url_callback', 'login_branding', 'login_branding_main_section');
    add_settings_field('login_branding_logo_link', 'Logo Link', 'login_branding_logo_link_callback', 'login_branding', 'login_branding_main_section');
    add_settings_field('login_branding_footer_text', 'Footer Text', 'login_branding_footer_text_callback', 'login_branding', 'login_branding_main_section');
    add_settings_field('login_branding_background_image_path', 'Background Image Path', 'login_branding_background_image_path_callback', 'login_branding', 'login_branding_main_section');
    add_settings_field('login_branding_background_color', 'Background Color', 'login_branding_background_color_callback', 'login_branding', 'login_branding_main_section');
}

// Callbacks for the settings fields
function login_branding_logo_url_callback() {
    $logo_url = esc_url(get_option('login_branding_logo_url'));
    echo '<input type="text" name="login_branding_logo_url" value="' . $logo_url . '" size="50" />';
}

function login_branding_logo_link_callback() {
    $logo_link = esc_url(get_option('login_branding_logo_link'));
    echo '<input type="text" name="login_branding_logo_link" value="' . $logo_link . '" size="50" />';
}

function login_branding_footer_text_callback() {
    $footer_text = sanitize_text_field(get_option('login_branding_footer_text', 'Need help? Get in touch: support@your-domain.com'));
    echo '<input type="text" name="login_branding_footer_text" value="' . $footer_text . '" size="50" />';
}

function login_branding_background_image_path_callback() {
    $background_image_path = esc_url(get_option('login_branding_background_image_path'));
    echo '<input type="text" name="login_branding_background_image_path" value="' . $background_image_path . '" size="50" />';
}

function login_branding_background_color_callback() {
    $background_color = sanitize_hex_color(get_option('login_branding_background_color', '#ffffff'));
    echo '<input type="text" name="login_branding_background_color" value="' . $background_color . '" class="wp-color-picker-field" data-default-color="#ffffff" />';
}

// Add the settings page to the WordPress admin menu
function login_branding_add_admin_menu() {
    add_options_page('Login Branding Settings', 'Login Branding', 'manage_options', 'login_branding', 'login_branding_settings_page');
}

// Enqueue custom styles for login page
function login_branding_login_style() {
    $logo_url = esc_url(get_option('login_branding_logo_url'));
    $background_image_path = esc_url(get_option('login_branding_background_image_path'));
    $background_color = sanitize_hex_color(get_option('login_branding_background_color', '#ffffff'));
    $footer_text = esc_html(get_option('login_branding_footer_text', 'Need help? Get in touch: support@your-domain.com'));
    ?>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Barlow:wght@500;600&family=Open+Sans:wght@400;500;600&display=swap');
        body.login {
            background: <?php echo $background_image_path ? "url('$background_image_path') no-repeat center center" : $background_color; ?>;
            background-size: cover;
            height: 100vh !important;
            max-height: 100vh !important;
            overflow: hidden;
            font-family: 'Open Sans', sans-serif;
        }
        #login h1 a {
            background-image: url('<?php echo $logo_url; ?>');
            height: 70px;
            width: 310px;
            background-size: contain;
        }
        @media(min-width: 1200px) {
            #login {
                width: 21vw !important;
            }
        }
        @media(max-width: 1200px) {
            #login {
                position: relative !important;
                width: 88vw !important;
                height: 100% !important;
            }
            #login:after {
                display: none !important;
            }
        }
        #login {
            background: #fff;
            padding: 0% 6% !important;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            letter-spacing: 0.3px;
            line-height: 1.8;
        }
        #loginform {
            padding: 0;
            border-radius: 0;
            border: 0 !important;
            box-shadow: none !important;
        }
        #loginform .input {
            border: 1px solid #eee;
            border-radius: 3px !important;
            height: 55px;
            padding: 0 12px; /* Add padding for consistent appearance */
            box-sizing: border-box; /* Ensure padding and border are included in width */
            transition: border-color 0.3s;
        }
        #loginform .input:focus,
        #loginform .input:hover {
            border-color: #009cff; /* Change the border color on hover and focus */
            outline: none;
        }
        #loginform .login label {
            margin-bottom: 10px;
            font-weight: 700;
        }
        .login .button.wp-hide-pw {
            height: 55px;
            top: 8px;
        }
        #loginform .submit input {
            border: none !important;
            background-color: #009cff !important;
            text-transform: uppercase;
            font-weight: 700;
            padding: 10px !important;
            width: 100%;
            transition: all 0.4s;
        }
        #loginform .submit input:hover {
            background-color: #0f88d5 !important;
            transition: all 0.2s;
        }
        #login::after {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 37vw;
            height: 100vh;
            background: <?php echo $background_image_path ? "url('$background_image_path') no-repeat center center" : $background_color; ?>;
            background-size: cover;
            color: #fff;
            font-size: 2vw;
            font-family: 'Barlow', sans-serif;
            font-weight: 200;
            padding-left: 5vw;
            padding-right: 25vw;
            display: flex;
            flex-direction: column;
            justify-content: middle;
            line-height: 1.2;
        }
        #user_login, #user_pass {
            margin-top: 8px;
            margin-bottom: 25px;
        }
        .forgetmenot {
            margin-bottom: 5px !important;
        }
        .language-switcher {
            position: absolute;
            bottom: 25px;
            right: 0px !important;
            height: 30px;
            padding: 0;
            width: 100%;
        }
        .language-switcher form#language-switcher {
            margin-top: 0px;
        }
        #login #nav, #login #backtoblog {
            text-align: center;
        }
        #login #nav a, #login #backtoblog a {
            color: #555 !important;
        }
        #backtoblog::after {
            content: '<?php echo addslashes($footer_text); ?>';
            display: block;
            margin-top: 30px;
            font-size: 16px;
            color: #222;
            font-weight: 600;
        }
    </style>
    <?php
}

// Change the login logo link URL
function login_branding_login_logo_url() {
    $logo_link = esc_url(get_option('login_branding_logo_link', home_url()));
    return $logo_link;
}
add_filter('login_headerurl', 'login_branding_login_logo_url');

// Add a link to the settings page in the plugin list
function login_branding_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=login_branding">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Hooks
add_action('admin_menu', 'login_branding_add_admin_menu');
add_action('admin_init', 'login_branding_register_settings');
add_action('login_enqueue_scripts', 'login_branding_login_style');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'login_branding_settings_link');
