<div class="wrap">
    <h1>Store Slides</h1>
    
    <div id="slides-container">
        <div class="slides-list">
            <?php foreach ($slides as $slide): ?>
            <div class="slide-item" data-id="<?php echo esc_attr($slide->id); ?>">
                <div class="slide-preview">
                    <img src="<?php echo esc_url($slide->slide_data->image_url); ?>" alt="">
                </div>
                <div class="slide-details">
                    <h3><?php echo esc_html($slide->slide_data->title); ?></h3>
                    <p><?php echo esc_html($slide->slide_data->description); ?></p>
                    <a href="<?php echo esc_url($slide->slide_data->link); ?>" target="_blank">
                        <?php echo esc_url($slide->slide_data->link); ?>
                    </a>
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
                <div class="form-actions">
                    <button type="submit" class="button button-primary">Save</button>
                    <button type="button" class="button cancel-modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div> 