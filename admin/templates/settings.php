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
                        'email' => 'Email Address',
                        'first_name' => 'First Name',
                        'last_name' => 'Last Name',
                        'phone' => 'Phone Number'
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