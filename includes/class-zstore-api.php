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
            // Get all settings
            $settings = $this->settings->get_all_settings();
            
            // Get all slides
            $slides = $this->slides->get_slides();
            
            // Combine settings and slides
            $response = array(
                'success' => true,
                'data' => array(
                    'settings' => isset($settings['store_settings']) ? $settings['store_settings'] : array(),
                    'slides' => $slides
                )
            );
            
            return new WP_REST_Response($response, 200);
            
        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'success' => false,
                'error' => $e->getMessage()
            ), 500);
        }
    }
} 