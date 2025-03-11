<?php
/**
 * Handles REST API functionality
 */
class Zstore_API {
    private $slides;
    private $settings;
    private $namespace = 'zstore/v1';
    
    public function __construct() {
        $this->slides = new Zstore_Slides();
        $this->settings = new Zstore_Settings();
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    
    /**
     * Register REST API routes
     */
    public function register_routes() {
        register_rest_route($this->namespace, '/settings', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_settings'),
            'permission_callback' => array($this, 'check_permissions'),
            'args' => array(
                'auth_key' => array(
                    'required' => true,
                    'type' => 'string',
                    'description' => 'Authentication key for accessing settings',
                    'validate_callback' => function($param) {
                        return !empty($param);
                    }
                ),
                'cache_bust' => array(
                    'required' => false,
                    'type' => 'string',
                    'description' => 'Cache busting parameter',
                    'validate_callback' => function($param) {
                        return is_string($param);
                    }
                )
            )
        ));
    }
    
    /**
     * Check permissions for the API endpoint
     */
    public function check_permissions($request) {
        // Get the auth key from the request
        $auth_key = $request->get_param('auth_key');
        
        // Get the stored secret key
        $settings = $this->settings->get_all_settings();
        $stored_key = isset($settings['store_settings']['store_secret_keys']) ? 
                     $settings['store_settings']['store_secret_keys'] : '';
        
        // Compare the keys
        return !empty($stored_key) && hash_equals($stored_key, $auth_key);
    }
    
    /**
     * Handle GET request for settings
     */
    public function get_settings($request) {
        try {
            // Force refresh of settings by clearing all caches
            $this->settings->clear_all_caches();
            
            // Get all settings directly from the database, bypassing cache
            global $wpdb;
            $table_name = $wpdb->prefix . 'zstore_settings';
            $results = $wpdb->get_results(
                "SELECT setting_key, setting_value FROM {$table_name}"
            );
            
            $settings = array();
            foreach ($results as $row) {
                $settings[$row->setting_key] = json_decode($row->setting_value, true);
            }
            
            // Ensure store_settings exists and has default values for missing fields
            if (!isset($settings['store_settings'])) {
                $settings['store_settings'] = $this->settings->get_default_settings();
            } else {
                // Merge with defaults to ensure all fields exist
                $defaults = $this->settings->get_default_settings();
                $settings['store_settings'] = array_replace_recursive($defaults, $settings['store_settings']);
            }
            
            // Get all slides directly from the database
            global $wpdb;
            $slides_table = $wpdb->prefix . 'home_slides';
            $slides = $wpdb->get_results(
                "SELECT * FROM {$slides_table} ORDER BY slide_order ASC"
            );
            
            $slides = array_map(function($slide) {
                $slide->slide_data = json_decode($slide->slide_data);
                return $slide;
            }, $slides);
            
            // Combine settings and slides
            $response = array(
                'success' => true,
                'data' => array(
                    'settings' => isset($settings['store_settings']) ? $settings['store_settings'] : array(),
                    'slides' => $slides
                )
            );
            
            // Set cache control headers to prevent caching
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            
            return new WP_REST_Response($response, 200);
            
        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'error' => $e->getMessage()
            ), 500);
        }
    }
} 