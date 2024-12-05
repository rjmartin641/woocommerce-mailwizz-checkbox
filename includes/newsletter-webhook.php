<?php
// Add the newsletter subscription checkbox above Terms and Conditions
add_action('woocommerce_review_order_before_submit', 'add_newsletter_subscription_checkbox');
function add_newsletter_subscription_checkbox() {
    echo '<div id="newsletter_subscription" style="margin-bottom: 15px;">';
    woocommerce_form_field('newsletter_subscription', array(
        'type'  => 'checkbox',
        'class' => array('form-row-wide'),
        'label' => __('Subscribe to our newsletter', 'woocommerce-mailwizz-integration'),
    ));
    echo '</div>';
}

// Save the checkbox data to the order meta
add_action('woocommerce_checkout_update_order_meta', 'save_newsletter_subscription_checkbox');
function save_newsletter_subscription_checkbox($order_id) {
    if (!empty($_POST['newsletter_subscription'])) {
        update_post_meta($order_id, '_newsletter_subscription', 'yes');
    } else {
        update_post_meta($order_id, '_newsletter_subscription', 'no');
    }
}

// Send subscriber data to MailWizz
add_action('woocommerce_checkout_update_order_meta', 'send_newsletter_data_to_mailwizz', 20, 1);
function send_newsletter_data_to_mailwizz($order_id) {
    // Check if the user opted into the newsletter
    $subscribe = get_post_meta($order_id, '_newsletter_subscription', true);
    if ($subscribe !== 'yes') {
        log_debug('User did not opt-in for newsletter for order ID: ' . $order_id);
        return;
    }

    // Get MailWizz settings
    $api_key = get_option('mailwizz_api_key');
    $api_url = get_option('mailwizz_api_endpoint');
    $debug_mode = get_option('mailwizz_debug_mode') === 'yes'; // Debug mode setting

    if (empty($api_key) || empty($api_url)) {
        log_debug('MailWizz API key or endpoint is missing.', $debug_mode);
        return;
    }

    // Get order details
    $order = wc_get_order($order_id);
    $email = sanitize_email($order->get_billing_email());
    $first_name = sanitize_text_field($order->get_billing_first_name());
    $last_name = sanitize_text_field($order->get_billing_last_name());

    if (empty($email)) {
        log_debug('Email address is missing or invalid for order ID: ' . $order_id, $debug_mode);
        return;
    }

    // Prepare the subscriber data
    $subscriber_data = array(
        'EMAIL' => $email,
        'FNAME' => $first_name,
        'LNAME' => $last_name,
    );

    $body = http_build_query($subscriber_data);

    // Log the subscriber data
    log_debug('Form-Encoded Subscriber Data: ' . $body, $debug_mode);

    // Send the request to MailWizz
    $response = wp_remote_post($api_url, array(
        'method'    => 'POST',
        'headers'   => array(
            'X-Api-Key'    => $api_key,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body'      => $body,
        'timeout'   => 45,
    ));

    if (is_wp_error($response)) {
        log_debug('MailWizz API error: ' . $response->get_error_message(), $debug_mode);
    } else {
        $status_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);

        if ($status_code !== 200) {
            log_debug("MailWizz API error $status_code: $response_body", $debug_mode);
        } else {
            log_debug('MailWizz API success: ' . $response_body, $debug_mode);
        }
    }
}

// Debug logging function
function log_debug($message, $debug_mode) {
    if ($debug_mode) {
        error_log($message);
    }
}
?>
