<?php
/*
Plugin Name: WooCommerce MailWizz Integration
Description: Adds MailWizz integration to WooCommerce, including a newsletter subscription checkbox.
Version: 1.1.2
Author: Ryan Martin
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('WC_MAILWIZZ_PLUGIN_DIR', plugin_dir_path(__FILE__));

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
                __('WooCommerce MailWizz Integration requires WooCommerce to be installed and active.', 'woocommerce-mailwizz-integration') .
                '</p></div>';
        });
    }
}
?>
