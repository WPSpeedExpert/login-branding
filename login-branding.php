<?php
/**
 * Plugin Name:       Login Branding
 * Plugin URI:        https://octahexa.com/plugins/login-branding
 * Description:       Customize the WordPress login screen with branding options, including logo, background, and custom colors, using the Customizer.
 * Version:           1.5.2
 * Author:            OctaHexa Media LLC
 * Author URI:        https://octahexa.com
 * Text Domain:       login-branding
 * Domain Path:       /languages
 * Requires PHP:      7.4
 * Requires at least: 5.6
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/WPSpeedExpert/login-branding.git
 * GitHub Branch:     main
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load plugin textdomain for translations.
 */
function login_branding_load_textdomain() {
    load_plugin_textdomain('login-branding', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'login_branding_load_textdomain');

/**
 * Set default options on plugin activation.
 */
function login_branding_activate() {
    $defaults = [
        'login_branding_logo_url' => plugin_dir_url(__FILE__) . 'img/logo.webp',
        'login_branding_background_image_path' => plugin_dir_url(__FILE__) . 'img/background.webp',
        'login_branding_background_color' => '#ffffff',
        'login_branding_first_column_bg_color' => '#ffffff',
        'login_branding_text_color' => '#000000',
        'login_branding_input_bg_color' => '#ffffff',
        'login_branding_input_text_color' => '#000000',
        'login_branding_button_color' => '#009cff',
        'login_branding_button_hover_color' => '#0f88d5',
        'login_branding_button_text_color' => '#ffffff',
        'login_branding_link_hover_color' => '#0073aa',
        'login_branding_footer_text' => __('Need help? Get in touch: support@yourdomain.com', 'login-branding'),
        'login_branding_logo_link' => home_url(),
    ];

    foreach ($defaults as $key => $value) {
        if (get_option($key) === false) {
            update_option($key, $value);
        }
    }
}
register_activation_hook(__FILE__, 'login_branding_activate');

/**
 * Add branding settings to the Customizer.
 */
function login_branding_customizer_register($wp_customize) {
    $settings = [
        'login_branding_logo_url' => [
            'default'            => plugin_dir_url(__FILE__) . 'img/logo.webp',
            'type'               => 'image',
            'label'              => __('Login Logo', 'login-branding'),
            'sanitize_callback'  => 'esc_url_raw',
        ],
        'login_branding_background_image' => [
            'default'            => plugin_dir_url(__FILE__) . 'img/background.webp',
            'type'               => 'image',
            'label'              => __('Background Image', 'login-branding'),
            'sanitize_callback'  => 'esc_url_raw',
        ],
        'login_branding_background_color' => [
            'default'            => '#ffffff',
            'type'               => 'color',
            'label'              => __('Second Column Background Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_first_column_bg_color' => [
            'default'            => '#ffffff',
            'type'               => 'color',
            'label'              => __('First Column Background Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_text_color' => [
            'default'            => '#000000',
            'type'               => 'color',
            'label'              => __('Text Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_input_bg_color' => [
            'default'            => '#ffffff',
            'type'               => 'color',
            'label'              => __('Input Background Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_input_text_color' => [
            'default'            => '#000000',
            'type'               => 'color',
            'label'              => __('Input Text Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_button_color' => [
            'default'            => '#009cff',
            'type'               => 'color',
            'label'              => __('Login Button Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_button_hover_color' => [
            'default'            => '#0f88d5',
            'type'               => 'color',
            'label'              => __('Login Button Hover Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_button_text_color' => [
            'default'            => '#ffffff',
            'type'               => 'color',
            'label'              => __('Login Button Text Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_link_hover_color' => [
            'default'            => '#0073aa',
            'type'               => 'color',
            'label'              => __('Link Hover Color', 'login-branding'),
            'sanitize_callback'  => 'sanitize_hex_color',
        ],
        'login_branding_footer_text' => [
            'default'            => __('Need help? Get in touch: support@yourdomain.com', 'login-branding'),
            'type'               => 'text',
            'label'              => __('Footer Text', 'login-branding'),
            'sanitize_callback'  => 'sanitize_text_field',
        ],
        'login_branding_logo_link' => [
            'default'            => home_url(),
            'type'               => 'url',
            'label'              => __('Logo Link', 'login-branding'),
            'sanitize_callback'  => 'esc_url_raw',
        ],
    ];

    // Add a new section for Login Branding
    $wp_customize->add_section('login_branding_section', [
        'title'    => __('Login Branding', 'login-branding'),
        'priority' => 30,
    ]);

    foreach ($settings as $key => $setting) {
        $wp_customize->add_setting($key, [
            'default'           => $setting['default'],
            'sanitize_callback' => $setting['sanitize_callback'],
        ]);

        $control_args = [
            'label'    => $setting['label'],
            'section'  => 'login_branding_section',
            'settings' => $key,
        ];

        if ($setting['type'] === 'image') {
            $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $key, $control_args));
        } elseif ($setting['type'] === 'color') {
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, $control_args));
        } else {
            $control_args['type'] = $setting['type'];
            $wp_customize->add_control($key, $control_args);
        }
    }
}
add_action('customize_register', 'login_branding_customizer_register');

/**
 * Enqueue styles for the login page.
 */
function login_branding_enqueue_styles() {
    wp_enqueue_style('login-branding-styles', plugin_dir_url(__FILE__) . 'css/login-branding.css', [], '1.5.2');

    // Add inline styles for dynamic variables
    $inline_css = "
        :root {
            --background-color: " . esc_attr(get_option('login_branding_background_color', '#ffffff')) . ";
            --button-color: " . esc_attr(get_option('login_branding_button_color', '#009cff')) . ";
            --button-hover-color: " . esc_attr(get_option('login_branding_button_hover_color', '#0f88d5')) . ";
        }
    ";
    wp_add_inline_style('login-branding-styles', $inline_css);
}
add_action('login_enqueue_scripts', 'login_branding_enqueue_styles');

/**
 * Change login logo link URL.
 */
function login_branding_login_logo_url() {
    return esc_url(get_option('login_branding_logo_link', home_url()));
}
add_filter('login_headerurl', 'login_branding_login_logo_url');
