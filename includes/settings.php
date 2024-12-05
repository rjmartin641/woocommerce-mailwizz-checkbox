<?php
// Add a custom settings tab under WooCommerce > Settings
add_filter('woocommerce_settings_tabs_array', 'add_mailwizz_settings_tab', 50);
function add_mailwizz_settings_tab($tabs) {
    $tabs['mailwizz'] = __('MailWizz', 'woocommerce-mailwizz-integration');
    return $tabs;
}

// Display settings fields
add_action('woocommerce_settings_mailwizz', 'display_mailwizz_settings');
function display_mailwizz_settings() {
    woocommerce_admin_fields(get_mailwizz_settings());
}

// Save settings fields
add_action('woocommerce_update_options_mailwizz', 'save_mailwizz_settings');
function save_mailwizz_settings() {
    woocommerce_update_options(get_mailwizz_settings());
}

// Define the settings fields
function get_mailwizz_settings() {
    return array(
        'section_title' => array(
            'name'     => __('MailWizz Integration Settings', 'woocommerce-mailwizz-integration'),
            'type'     => 'title',
            'desc'     => 'Enter your MailWizz API credentials here.',
            'id'       => 'mailwizz_section_title',
        ),
        'api_key' => array(
            'name' => __('MailWizz API Key', 'woocommerce-mailwizz-integration'),
            'type' => 'text',
            'desc' => __('Enter your MailWizz API Key.', 'woocommerce-mailwizz-integration'),
            'id'   => 'mailwizz_api_key',
        ),
        'api_endpoint' => array(
            'name' => __('MailWizz API Endpoint', 'woocommerce-mailwizz-integration'),
            'type' => 'text',
            'desc' => __('Enter the API endpoint URL (e.g., https://example.com/api/v1/lists).', 'woocommerce-mailwizz-integration'),
            'id'   => 'mailwizz_api_endpoint',
        ),
        'section_end' => array(
            'type' => 'sectionend',
            'id'   => 'mailwizz_section_end',
        ),
    );
}
?>
