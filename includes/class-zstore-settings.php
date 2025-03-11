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
        
        // Ensure store_settings exists and has default values for missing fields
        if (!isset($settings['store_settings'])) {
            $settings['store_settings'] = $this->get_default_settings();
        } else {
            // Merge with defaults to ensure all fields exist
            $defaults = $this->get_default_settings();
            $settings['store_settings'] = array_replace_recursive($defaults, $settings['store_settings']);
        }
        
        return $settings;
    }
    
    /**
     * Clear all possible caches
     */
    public function clear_all_caches() {
        // Clear WordPress object cache
        wp_cache_delete($this->cache_key, $this->cache_group);
        
        // Clear LiteSpeed Cache
        if (class_exists('LiteSpeed\Purge')) {
            \LiteSpeed\Purge::purge_all();
        } else if (function_exists('litespeed_purge_all')) {
            litespeed_purge_all();
        }
        
        // Clear W3 Total Cache
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }
        
        // Clear WP Super Cache
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
        
        // Clear WP Rocket cache
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
        }
        
        // Clear WP Fastest Cache
        if (class_exists('WpFastestCache')) {
            $wpfc = new \WpFastestCache();
            $wpfc->deleteCache(true);
        }
        
        // Clear Autoptimize cache
        if (class_exists('autoptimizeCache')) {
            \autoptimizeCache::clearall();
        }
        
        // Clear Comet Cache
        if (class_exists('comet_cache')) {
            \comet_cache::clear();
        }
        
        // Clear Breeze cache
        if (class_exists('Breeze_Admin')) {
            $breeze = new \Breeze_Admin();
            $breeze->breeze_clear_all_cache();
        }
        
        // Force WordPress to refresh its internal cache
        wp_cache_flush();
        
        // Attempt to clear opcache if available
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }
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
            // Clear all caches
            $this->clear_all_caches();
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
            'woocommerce_key' => '',
            'woocommerce_secret' => '',
            'address' => '',
            // I added the privacy policy link field and added it to the default settings
            'privacy_policy_link' => '',
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
    
    /**
     * Clear settings cache
     */
    public function clear_cache() {
        wp_cache_delete($this->cache_key, $this->cache_group);
    }
} 