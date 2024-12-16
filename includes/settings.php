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

// Define the settings fields for the MailWizz tab
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
            'desc' => __('Enter the API endpoint URL (e.g., https://example.com/api/v1/lists/{list-unique-id}/subscribers).', 'woocommerce-mailwizz-integration'),
            'id'   => 'mailwizz_api_endpoint',
        ),
        'debug_mode' => array(
            'name' => __('Debug Mode', 'woocommerce-mailwizz-integration'),
            'type' => 'checkbox',
            'desc' => __('Enable debug mode to log all actions.', 'woocommerce-mailwizz-integration'),
            'id'   => 'mailwizz_debug_mode',
        ),
        'section_end' => array(
            'type' => 'sectionend',
            'id'   => 'mailwizz_section_end',
        ),
    );
}

// Add the "Check newsletter subscription checkbox by default" setting to WooCommerce > Settings > General
add_filter('woocommerce_settings_general', 'wc_mailwizz_add_general_settings', 50);
function wc_mailwizz_add_general_settings($settings) {
    $updated_settings = array();

    foreach ($settings as $section) {
        $updated_settings[] = $section;

        // Insert our new field after 'woocommerce_allow_tracking'
        if (isset($section['id']) && 'woocommerce_allow_tracking' === $section['id']) {
            $updated_settings[] = array(
                'title'    => __('Check newsletter subscription checkbox by default', 'woocommerce-mailwizz-integration'),
                'desc'     => __('If enabled, the newsletter subscription checkbox will be checked by default during checkout.', 'woocommerce-mailwizz-integration'),
                'id'       => 'mailwizz_newsletter_checkbox_default_checked',
                'default'  => 'no',
                'type'     => 'checkbox',
                'desc_tip' => true,
            );
        }
    }

    return $updated_settings;
}
?>
