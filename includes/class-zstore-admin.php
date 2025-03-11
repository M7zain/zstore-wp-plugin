<?php
/**
 * Handles admin interface functionality
 */
class Zstore_Admin {
    private $slides;
    private $settings;
    
    public function __construct() {
        $this->slides = new Zstore_Slides();
        $this->settings = new Zstore_Settings();
        
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_save_slide', array($this, 'ajax_save_slide'));
        add_action('wp_ajax_delete_slide', array($this, 'ajax_delete_slide'));
        add_action('wp_ajax_save_settings', array($this, 'ajax_save_settings'));
    }
    
    /**
     * Add admin menu items
     */
    public function add_admin_menu() {
        add_menu_page(
            'ZStore Settings',
            'ZStore',
            'manage_options',
            'zstore-settings',
            array($this, 'render_settings_page'),
            'dashicons-store',
            30
        );
        
        add_submenu_page(
            'zstore-settings',
            'Slides',
            'Slides',
            'manage_options',
            'zstore-slides',
            array($this, 'render_slides_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (!in_array($hook, array('toplevel_page_zstore-settings', 'zstore_page_zstore-slides'))) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        
        wp_enqueue_script(
            'zstore-admin',
            ZSTORE_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'jquery-ui-sortable', 'wp-color-picker'),
            ZSTORE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'zstore-admin',
            ZSTORE_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            ZSTORE_VERSION
        );
        
        wp_localize_script('zstore-admin', 'zstoreAdmin', array(
            'nonce' => wp_create_nonce('zstore_admin_nonce'),
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        $settings = $this->settings->get_all_settings();
        include ZSTORE_PLUGIN_DIR . 'admin/templates/settings.php';
    }
    
    /**
     * Render slides page
     */
    public function render_slides_page() {
        $slides = $this->slides->get_slides();
        include ZSTORE_PLUGIN_DIR . 'admin/templates/slides.php';
    }
    
    /**
     * Handle AJAX slide save
     */
    public function ajax_save_slide() {
        check_ajax_referer('zstore_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $slide_data = array(
            'image_url' => sanitize_text_field($_POST['image_url']),
            'title' => sanitize_text_field($_POST['title']),
            'description' => wp_kses_post($_POST['description']),
            'link' => esc_url_raw($_POST['link']),
            'order' => intval($_POST['order'])
        );
        
        $slide_id = isset($_POST['slide_id']) ? intval($_POST['slide_id']) : null;
        $result = $this->slides->save_slide($slide_data, $slide_id);
        
        if ($result) {
            wp_send_json_success(array('slide_id' => $result));
        } else {
            wp_send_json_error('Failed to save slide');
        }
    }
    
    /**
     * Handle AJAX slide deletion
     */
    public function ajax_delete_slide() {
        check_ajax_referer('zstore_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $slide_id = intval($_POST['slide_id']);
        $result = $this->slides->delete_slide($slide_id);
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to delete slide');
        }
    }
    
    /**
     * Handle AJAX settings save
     */
    public function ajax_save_settings() {
        check_ajax_referer('zstore_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }
        
        $settings = json_decode(stripslashes($_POST['settings']), true);
        
        // Debug: Log received settings
        error_log('Received settings in AJAX: ' . print_r($settings, true));
        
        // Validate and sanitize settings
        $sanitized = array();
        
        // Create the store_settings structure if it doesn't exist
        if (!isset($sanitized['store_settings'])) {
            $sanitized['store_settings'] = array();
        }
        
        // Store all fields in the store_settings nested structure
        if (isset($settings['store_secret_keys'])) {
            $sanitized['store_settings']['store_secret_keys'] = sanitize_text_field($settings['store_secret_keys']);
        }
        
        if (isset($settings['site_url'])) {
            $sanitized['store_settings']['site_url'] = esc_url_raw($settings['site_url']);
        }
        
        if (isset($settings['logo_url'])) {
            $sanitized['store_settings']['logo_url'] = esc_url_raw($settings['logo_url']);
        }
        
        // Address 
        if (isset($settings['address'])) {
            $sanitized['store_settings']['address'] = sanitize_text_field($settings['address']);
        }
        //phone
        if (isset($settings['phone'])) {
            $sanitized['store_settings']['phone'] = sanitize_text_field($settings['phone']);
        }
        //whatsapp number
        if (isset($settings['whatsapp_number'])) {
            $sanitized['store_settings']['whatsapp_number'] = sanitize_text_field($settings['whatsapp_number']);
        }
        
        if (isset($settings['woocommerce_key'])) {
            $sanitized['store_settings']['woocommerce_key'] = sanitize_text_field($settings['woocommerce_key']);
        }
        
        if (isset($settings['woocommerce_secret'])) {
            $sanitized['store_settings']['woocommerce_secret'] = sanitize_text_field($settings['woocommerce_secret']);
        }

        // Privacy policy link field
        if (isset($settings['privacy_policy_link'])) {
            $sanitized['store_settings']['privacy_policy_link'] = sanitize_text_field($settings['privacy_policy_link']);
        }
        
        // Theme settings
        if (isset($settings['theme']['colors'])) {
            $sanitized['store_settings']['theme'] = array(
                'colors' => array(
                    'primary' => sanitize_hex_color($settings['theme']['colors']['primary']),
                    'secondary' => sanitize_hex_color($settings['theme']['colors']['secondary'])
                )
            );
        }
        
        // Working hours
        if (isset($settings['working_hours'])) {
            $sanitized['store_settings']['working_hours'] = array();
            foreach ($settings['working_hours'] as $day => $hours) {
                $sanitized['store_settings']['working_hours'][$day] = array(
                    'start' => sanitize_text_field($hours['start']),
                    'end' => sanitize_text_field($hours['end'])
                );
            }
        }
        
        // Activity
        if (isset($settings['activity'])) {
            $sanitized['store_settings']['activity'] = array(
                'is_open' => (bool) $settings['activity']['is_open']
            );
        }
        
        // Checkout form
        if (isset($settings['checkout_form'])) {
            $sanitized['store_settings']['checkout_form'] = array(
                'email' => (bool) $settings['checkout_form']['email'],
                'first_name' => (bool) $settings['checkout_form']['first_name'],
                'last_name' => (bool) $settings['checkout_form']['last_name'],
                'phone' => (bool) $settings['checkout_form']['phone'],
                'address_line1' => (bool) $settings['checkout_form']['address_line1'],
                'address_line2' => (bool) $settings['checkout_form']['address_line2'],
                'city' => (bool) $settings['checkout_form']['city'],
                'state' => (bool) $settings['checkout_form']['state'],
                'postal_code' => (bool) $settings['checkout_form']['postal_code'],
                'country' => (bool) $settings['checkout_form']['country'],
                'company' => (bool) $settings['checkout_form']['company'],
                'different_shipping' => (bool) $settings['checkout_form']['different_shipping'],
                'shipping_first_name' => (bool) $settings['checkout_form']['shipping_first_name'],
                'shipping_last_name' => (bool) $settings['checkout_form']['shipping_last_name'],
                'shipping_address_line1' => (bool) $settings['checkout_form']['shipping_address_line1'],
                'shipping_address_line2' => (bool) $settings['checkout_form']['shipping_address_line2'],
                'shipping_city' => (bool) $settings['checkout_form']['shipping_city'],
                'shipping_state' => (bool) $settings['checkout_form']['shipping_state'],
                'shipping_postal_code' => (bool) $settings['checkout_form']['shipping_postal_code'],
                'shipping_country' => (bool) $settings['checkout_form']['shipping_country'],
                'order_notes' => (bool) $settings['checkout_form']['order_notes'],
                'create_account' => (bool) $settings['checkout_form']['create_account'],
                'newsletter_subscription' => (bool) $settings['checkout_form']['newsletter_subscription'],
                'terms_acceptance' => (bool) $settings['checkout_form']['terms_acceptance']
            );
        }
        
        // Debug: Log sanitized settings
        error_log('Sanitized settings: ' . print_r($sanitized, true));
        
        // Try the normal update method first
        $result = $this->settings->update_setting('store_settings', $sanitized['store_settings']);
        
        // If that fails, try a direct database update
        if (!$result) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'zstore_settings';
            
            $result = $wpdb->replace(
                $table_name,
                array(
                    'setting_key' => 'store_settings',
                    'setting_value' => wp_json_encode($sanitized['store_settings'])
                )
            );
            
            // Clear all caches
            $this->settings->clear_all_caches();
        }
        
        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to save settings');
        }
    }

    // Ensure you have a function that saves the settings to the database
    public function save_settings($sanitized_settings) {
        update_option('zstore_settings', $sanitized_settings);
        // Or whatever option name you're using to store settings
    }
} 