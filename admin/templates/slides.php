<div class="wrap">
    <h1>Store Slides</h1>
    
    <div id="slides-container">
        <div class="slides-list">
            <?php foreach ($slides as $slide): ?>
            <div class="slide-item" data-id="<?php echo esc_attr($slide->id); ?>" data-category-id="<?php echo isset($slide->slide_data->category_id) ? esc_attr($slide->slide_data->category_id) : ''; ?>">
                <div class="slide-preview">
                    <img src="<?php echo esc_url($slide->slide_data->image_url); ?>" alt="">
                </div>
                <div class="slide-details">
                    <h3><?php echo esc_html($slide->slide_data->title); ?></h3>
                    <p><?php echo esc_html($slide->slide_data->description); ?></p>
                    <a href="<?php echo esc_url($slide->slide_data->link); ?>" target="_blank">
                        <?php echo esc_url($slide->slide_data->link); ?>
                    </a>
                    <?php if (isset($slide->slide_data->category_id) && !empty($slide->slide_data->category_id)): 
                        $category = get_term($slide->slide_data->category_id, 'product_cat');
                        if (!is_wp_error($category) && $category): ?>
                        <div class="slide-category">
                            <strong>Category:</strong> <?php echo esc_html($category->name); ?>
                        </div>
                        <?php endif;
                    endif; ?>
                </div>
                <div class="slide-actions">
                    <button class="button edit-slide">Edit</button>
                    <button class="button delete-slide">Delete</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button id="add-slide" class="button button-primary">Add New Slide </button>
    </div>
    
    <div id="slide-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Edit Slide</h2>
            <form id="slide-form">
                <input type="hidden" name="slide_id" value="">
                <div class="form-group">
                    <label>Image</label>
                    <div class="image-upload">
                        <button type="button" class="button select-image">Select Image</button>
                        <input type="hidden" name="image_url" value="">
                        <div class="image-preview"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label>Link URL</label>
                    <input type="url" name="link" required>
                </div>
                <div class="form-group">
                    <label>WooCommerce Category</label>
                    <select name="category_id">
                        <option value="">Select a category</option>
                        <?php
                        // Get WooCommerce categories
                        $args = array(
                            'taxonomy'   => 'product_cat',
                            'orderby'    => 'name',
                            'hide_empty' => false,
                        );
                        $categories = get_terms($args);
                        
                        if (!empty($categories) && !is_wp_error($categories)) {
                            foreach ($categories as $category) {
                                echo '<option value="' . esc_attr($category->term_id) . '">' . esc_html($category->name) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="button button-primary">Save</button>
                    <button type="button" class="button cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div> 