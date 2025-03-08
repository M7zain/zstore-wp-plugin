<?php
/**
 * Handles store settings management
 */
class Zstore_Settings {
    private $table_name;
    private $cache_group = 'zstore_settings';
    private $cache_key = 'all_settings';
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'zstore_settings';
        wp_cache_add_global_groups($this->cache_group);
    }
    
    /**
     * Get all settings
     */
    public function get_all_settings() {
        // Try to get from cache first
        $settings = wp_cache_get($this->cache_key, $this->cache_group);
        
        if (false === $settings) {
            global $wpdb;
            $results = $wpdb->get_results(
                "SELECT setting_key, setting_value FROM {$this->table_name}"
            );
            
            $settings = array();
            foreach ($results as $row) {
                $settings[$row->setting_key] = json_decode($row->setting_value, true);
            }
            
            // Cache the results
            wp_cache_set($this->cache_key, $settings, $this->cache_group);
        }
        
        return $settings;
    }
    
    /**
     * Update a setting
     */
    public function update_setting($key, $value) {
        global $wpdb;
        
        $data = array(
            'setting_value' => wp_json_encode($value)
        );
        
        $result = $wpdb->replace(
            $this->table_name,
            array(
                'setting_key' => $key,
                'setting_value' => wp_json_encode($value)
            )
        );
        
        if ($result) {
            wp_cache_delete($this->cache_key, $this->cache_group);
            return true;
        }
        
        return false;
    }
    
    /**
     * Get default settings
     */
    public function get_default_settings() {
        return array(
            'store_secret_keys' => '',
            'site_url' => get_site_url(),
            'logo_url' => '',
            'theme' => array(
                'colors' => array(
                    'primary' => '#FF5733',
                    'secondary' => '#33FF57'
                )
            ),
            'working_hours' => array(
                'Monday' => array('start' => '09:00', 'end' => '18:00'),
                'Tuesday' => array('start' => '09:00', 'end' => '18:00'),
                'Wednesday' => array('start' => '09:00', 'end' => '18:00'),
                'Thursday' => array('start' => '09:00', 'end' => '18:00'),
                'Friday' => array('start' => '09:00', 'end' => '18:00'),
                'Saturday' => array('start' => '10:00', 'end' => '16:00'),
                'Sunday' => array('start' => '10:00', 'end' => '16:00')
            ),
            'activity' => array(
                'is_open' => true
            ),
            'checkout_form' => array(
                'email' => true,
                'first_name' => true,
                'last_name' => true,
                'phone' => true
            )
        );
    }
} 