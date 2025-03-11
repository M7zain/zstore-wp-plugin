<div class="wrap">
    <h1>ZStore Settings</h1>
    
    <div class="zstore-settings-container">
        <div class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active">General</a>
            <a href="#theme" class="nav-tab">Theme</a>
            <a href="#working-hours" class="nav-tab">Working Hours</a>
            <a href="#checkout" class="nav-tab">Checkout</a>
        </div>
        
        <form id="zstore-settings-form">
            <!-- General Settings -->
            <div id="general" class="tab-content active">
                <h2>General Settings</h2>
                
                <table class="form-table">
                    <tr>
                        <th><label for="store_secret_keys">Store Secret Keys</label></th>
                        <td>
                            <input type="password" id="store_secret_keys" name="store_secret_keys" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_secret_keys'] ?? ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="site_url">Site URL</label></th>
                        <td>
                            <input type="url" id="site_url" name="site_url" class="regular-text" 
                                value="<?php echo esc_url($settings['site_url'] ?? ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="woocommerce_key">Woocommerce Key</label></th>
                        <td>
                            <input type="text" id="woocommerce_key" name="woocommerce_key" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['woocommerce_key'] ?? ''); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="woocommerce_secret">Woocommerce Secret</label></th>
                        <td>
                            <input type="text" id="woocommerce_secret" name="woocommerce_secret" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['woocommerce_secret'] ?? ''); ?>">
                        </td>
                    </tr>


                    <tr>
                        <th><label for="address">Address</label></th>
                        <td>
                            <input type="text" id="address" name="address" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['address'] ?? ''); ?>">
                        </td>
                    </tr>

                    <!-- phone -->
                    <tr>
                        <th><label for="phone">Phone</label></th>
                        <td>
                            <input type="text" id="phone" name="phone" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['phone'] ?? ''); ?>">
                        </td>
                    </tr>

                    <!-- Whatsapp number -->
                    <tr>
                        <th><label for="whatsapp_number">Whatsapp Number</label></th>
                        <td>
                            <input type="text" id="whatsapp_number" name="whatsapp_number" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['whatsapp_number'] ?? ''); ?>">
                    </tr>

                    <!-- Privacy policy link field -->
                    <tr>
                        <th><label for="privacy_policy_link">Privacy Policy Link</label></th>
                        <td>
                            <input type="text" id="privacy_policy_link" name="privacy_policy_link" class="regular-text" 
                                value="<?php echo esc_attr($settings['store_settings']['privacy_policy_link'] ?? ''); ?>">
                        </td>
                    </tr>

                    <tr>
                        <th><label>Store Logo</label></th>
                        <td>
                            <div class="logo-upload">
                                <button type="button" class="button select-logo">Select Logo</button>
                                <input type="hidden" name="logo_url" value="<?php echo esc_url($settings['logo_url'] ?? ''); ?>">
                                <div class="logo-preview">
                                    <?php if (!empty($settings['logo_url'])): ?>
                                        <img src="<?php echo esc_url($settings['logo_url']); ?>" alt="Store Logo">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label>Store Activity</label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="is_open" value="1" 
                                    <?php checked($settings['activity']['is_open'] ?? true); ?>>
                                Store is currently open
                            </label>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Theme Settings -->
            <div id="theme" class="tab-content">
                <h2>Theme Settings</h2>
                
                <table class="form-table">
                    <tr>
                        <th><label for="primary_color">Primary Color</label></th>
                        <td>
                            <input type="text" id="primary_color" name="primary_color" class="color-picker" 
                                value="<?php echo esc_attr($settings['theme']['colors']['primary'] ?? '#FF5733'); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><label for="secondary_color">Secondary Color</label></th>
                        <td>
                            <input type="text" id="secondary_color" name="secondary_color" class="color-picker" 
                                value="<?php echo esc_attr($settings['theme']['colors']['secondary'] ?? '#33FF57'); ?>">
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Working Hours -->
            <div id="working-hours" class="tab-content">
                <h2>Working Hours</h2>
                
                <table class="form-table">
                    <?php
                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                    foreach ($days as $day):
                        $hours = $settings['working_hours'][$day] ?? array('start' => '09:00', 'end' => '18:00');
                    ?>
                    <tr>
                        <th><label><?php echo esc_html($day); ?></label></th>
                        <td>
                            <input type="time" name="working_hours[<?php echo esc_attr($day); ?>][start]" 
                                value="<?php echo esc_attr($hours['start']); ?>">
                            to
                            <input type="time" name="working_hours[<?php echo esc_attr($day); ?>][end]" 
                                value="<?php echo esc_attr($hours['end']); ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            
            <!-- Checkout Settings -->
            <div id="checkout" class="tab-content">
                <h2>Checkout Form Fields</h2>
                
                <table class="form-table">
                    <?php
                    $fields = array(
                        // Personal Information
                        'email' => 'Email Address',
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                        'phone' => 'Phone Number',
                        
                        // Address Information
                        'address_line1' => 'Address Line 1',
                        'address_line2' => 'Address Line 2',
                        'city' => 'City',
                        'state' => 'State/Province',
                        'postal_code' => 'Postal Code/ZIP',
                        'country' => 'Country',
                        
                        // Billing Information
                        'company' => 'Company Name',
                        
                        // Alternative Shipping
                        'different_shipping' => 'Different Shipping Address',
                        'shipping_first_name' => 'Shipping First Name',
                        'shipping_last_name' => 'Shipping Last Name',
                        'shipping_address_line1' => 'Shipping Address Line 1',
                        'shipping_address_line2' => 'Shipping Address Line 2',
                        'shipping_city' => 'Shipping City',
                        'shipping_state' => 'Shipping State/Province',
                        'shipping_postal_code' => 'Shipping Postal Code/ZIP',
                        'shipping_country' => 'Shipping Country',
                        
                        // Additional Information
                        'order_notes' => 'Order Notes',
                        'create_account' => 'Create Account',
                        'newsletter_subscription' => 'Newsletter Subscription',
                        'terms_acceptance' => 'Terms & Conditions Acceptance'
                    );
                    foreach ($fields as $field => $label):
                    ?>
                    <tr>
                        <th><label><?php echo esc_html($label); ?></label></th>
                        <td>
                            <label>
                                <input type="checkbox" name="checkout_fields[<?php echo esc_attr($field); ?>]" value="1" 
                                    <?php checked($settings['checkout_form'][$field] ?? true); ?>>
                                Enable this field
                            </label>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            
            <p class="submit">
                <button type="submit" class="button button-primary">Save Settings</button>
            </p>
        </form>
    </div>
</div> 