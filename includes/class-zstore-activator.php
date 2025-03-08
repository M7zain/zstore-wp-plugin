<?php
/**
 * Plugin activation handler
 */
class Zstore_Activator {
    /**
     * Create necessary database tables
     */
    public static function activate() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Create slides table
        $slides_table = $wpdb->prefix . 'home_slides';
        $slides_sql = "CREATE TABLE IF NOT EXISTS $slides_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            slide_order int(11) NOT NULL DEFAULT 0,
            slide_data longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        // Create settings table
        $settings_table = $wpdb->prefix . 'zstore_settings';
        $settings_sql = "CREATE TABLE IF NOT EXISTS $settings_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            setting_key varchar(191) NOT NULL,
            setting_value longtext NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY setting_key (setting_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($slides_sql);
        dbDelta($settings_sql);
    }
} 