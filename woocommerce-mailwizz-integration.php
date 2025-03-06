<?php
/*
Plugin Name: WooCommerce MailWizz Integration
Description: Adds MailWizz integration to WooCommerce, including a newsletter subscription checkbox.
Version: 1.1.4
Author: Ryan Martin
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('WC_MAILWIZZ_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Load plugin textdomain for translations
function wc_mailwizz_load_textdomain() {
    load_plugin_textdomain('woocommerce-mailwizz-integration', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'wc_mailwizz_load_textdomain');

// Include plugin files
require_once WC_MAILWIZZ_PLUGIN_DIR . 'includes/settings.php';
require_once WC_MAILWIZZ_PLUGIN_DIR . 'includes/newsletter-webhook.php';

// Initialize the plugin
add_action('plugins_loaded', 'wc_mailwizz_init');
function wc_mailwizz_init() {
    if (class_exists('WooCommerce')) {
        do_action('wc_mailwizz_loaded');
    } else {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>' .
                esc_html__('WooCommerce MailWizz Integration requires WooCommerce to be installed and active.', 'woocommerce-mailwizz-integration') .
                '</p></div>';
        });
    }
}
?>
