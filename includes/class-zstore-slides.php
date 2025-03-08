<?php
/**
 * Handles slide management functionality
 */
class Zstore_Slides {
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'home_slides';
    }
    
    /**
     * Get all slides
     */
    public function get_slides() {
        global $wpdb;
        
        $slides = $wpdb->get_results(
            "SELECT * FROM {$this->table_name} ORDER BY slide_order ASC"
        );
        
        return array_map(function($slide) {
            $slide->slide_data = json_decode($slide->slide_data);
            return $slide;
        }, $slides);
    }
    
    /**
     * Add or update a slide
     */
    public function save_slide($slide_data, $slide_id = null) {
        global $wpdb;
        
        $data = array(
            'slide_data' => wp_json_encode($slide_data),
            'slide_order' => isset($slide_data['order']) ? $slide_data['order'] : 0
        );
        
        if ($slide_id) {
            $wpdb->update(
                $this->table_name,
                $data,
                array('id' => $slide_id)
            );
            return $slide_id;
        } else {
            $wpdb->insert($this->table_name, $data);
            return $wpdb->insert_id;
        }
    }
    
    /**
     * Delete a slide
     */
    public function delete_slide($slide_id) {
        global $wpdb;
        return $wpdb->delete(
            $this->table_name,
            array('id' => $slide_id)
        );
    }
} 