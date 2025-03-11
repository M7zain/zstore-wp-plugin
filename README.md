# ZStore Plugin

## Adding New Settings Fields

Follow these steps to add a new settings field to the plugin:

### 1. Add to HTML Template

Add your field to the settings.php template following this pattern:

```php
<tr>
    <th><label for="your_field_name">Your Field Label</label></th>
    <td>
        <input type="text" id="your_field_name" name="your_field_name" class="regular-text" 
            value="<?php echo esc_attr($settings['store_settings']['your_field_name'] ?? ''); ?>">
    </td>
</tr>
```

### 2. Add to JavaScript

Ensure your field is collected in the JavaScript settings object:

```javascript
// In assets/js/admin.js
const settings = {
    // ... existing fields ...
    your_field_name: $('#your_field_name').val(),
    // ... other fields ...
};
```

### 3. Add to Sanitization

Add sanitization for your field in the ajax_save_settings method in class-zstore-admin.php:

```php
// In the ajax_save_settings method
if (isset($settings['your_field_name'])) {
    $sanitized['store_settings']['your_field_name'] = sanitize_text_field($settings['your_field_name']);
}
```

### 4. Add to Default Settings

Add your field to the default settings in class-zstore-settings.php:

```php
// In the get_default_settings method
public function get_default_settings() {
    return array(
        // ... existing fields ...
        'your_field_name' => '',
        // ... other fields ...
    );
}
```

### Important Notes

- Always maintain the nested structure with 'store_settings' for all fields in sanitization
- Use appropriate sanitization functions based on field type (text, URL, etc.)
- IDs in HTML must match the JavaScript selectors
- Field names in JavaScript must match what you check for in PHP sanitization

By following this pattern consistently, all fields will work correctly. 