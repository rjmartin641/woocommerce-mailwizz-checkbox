<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Prevent direct access.
}

// Define constants for plugin paths
define('WC_MAILWIZZ_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include the settings and newsletter webhook files
require_once WC_MAILWIZZ_PLUGIN_DIR . 'includes/settings.php';
require_once WC_MAILWIZZ_PLUGIN_DIR . 'includes/newsletter-webhook.php';

// Action hook for initializing the plugin
add_action('plugins_loaded', 'wc_mailwizz_init');

function wc_mailwizz_init() {
    if ( class_exists( 'WooCommerce' ) ) {
        // Initialize plugin functionality
        do_action('wc_mailwizz_loaded');
    } else {
        // Display an admin notice if WooCommerce is not active
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>' . __('WooCommerce MailWizz Integration requires WooCommerce to be installed and active.', 'woocommerce-mailwizz-integration') . '</p></div>';
        });
    }
}
?>
