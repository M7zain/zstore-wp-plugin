jQuery(document).ready(function($) {
    // Initialize sortable slides
    $('.slides-list').sortable({
        update: function(event, ui) {
            updateSlideOrder();
        }
    });
    
    // Image selection
    let mediaUploader;
    
    $(document).on('click', '.select-image', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media({
            title: 'Select Slide Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="image_url"]').val(attachment.url);
            $('.image-preview').html(`<img src="${attachment.url}" alt="">`);
        });
        
        mediaUploader.open();
    });
    
    // Add new slide
    $('#add-slide').on('click', function() {
        resetForm();
        $('#slide-modal').show();
    });
    
    // Edit slide
    $(document).on('click', '.edit-slide', function() {
        const $slide = $(this).closest('.slide-item');
        const slideId = $slide.data('id');
        
        // Populate form with slide data
        $('input[name="slide_id"]').val(slideId);
        $('input[name="image_url"]').val($slide.find('img').attr('src'));
        $('.image-preview').html($slide.find('.slide-preview').html());
        $('input[name="title"]').val($slide.find('h3').text());
        $('textarea[name="description"]').val($slide.find('p').text());
        $('input[name="link"]').val($slide.find('a').attr('href'));
        
        // Set category dropdown value if it exists
        const categoryId = $slide.data('category-id');
        if (categoryId) {
            $('select[name="category_id"]').val(categoryId);
        }
        
        $('#slide-modal').show();
    });
    
    // Delete slide
    $(document).on('click', '.delete-slide', function() {
        if (!confirm('Are you sure you want to delete this slide?')) {
            return;
        }
        
        const $slide = $(this).closest('.slide-item');
        const slideId = $slide.data('id');
        
        $.ajax({
            url: zstoreAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_slide',
                nonce: zstoreAdmin.nonce,
                slide_id: slideId
            },
            success: function(response) {
                if (response.success) {
                    $slide.remove();
                    updateSlideOrder();
                } else {
                    alert('Failed to delete slide');
                }
            }
        });
    });
    
    // Save slide
    $('#slide-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'save_slide');
        formData.append('nonce', zstoreAdmin.nonce);
        
        $.ajax({
            url: zstoreAdmin.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to save slide');
                }
            }
        });
    });
    
    // Close modal
    $('.cancel-modal').on('click', function() {
        $('#slide-modal').hide();
    });
    
    // Update slide order
    function updateSlideOrder() {
        const slides = [];
        $('.slide-item').each(function(index) {
            slides.push({
                id: $(this).data('id'),
                order: index
            });
        });
        
        $.ajax({
            url: zstoreAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'update_slide_order',
                nonce: zstoreAdmin.nonce,
                slides: slides
            }
        });
    }
    
    // Reset form
    function resetForm() {
        $('#slide-form')[0].reset();
        $('input[name="slide_id"]').val('');
        $('input[name="image_url"]').val('');
        $('.image-preview').empty();
        $('select[name="category_id"]').val('');
    }
    
    // Initialize color pickers
    $('.color-picker').wpColorPicker();
    
    // Tab navigation
    $('.nav-tab').on('click', function(e) {
        e.preventDefault();
        
        $('.nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        
        const target = $(this).attr('href').substring(1);
        $('.tab-content').removeClass('active');
        $('#' + target).addClass('active');
    });
    
    // Logo selection
    $('.select-logo').on('click', function(e) {
        e.preventDefault();
        
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        mediaUploader = wp.media({
            title: 'Select Store Logo',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="logo_url"]').val(attachment.url);
            $('.logo-preview').html(`<img src="${attachment.url}" alt="Store Logo">`);
        });
        
        mediaUploader.open();
    });
    
    // Save settings
    $('#zstore-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        // Ensure all fields are properly collected, with consistent structure
        const settings = {
            store_secret_keys: $('input[name="store_secret_keys"]').val(),
            site_url: $('input[name="site_url"]').val(),
            logo_url: $('input[name="logo_url"]').val(),
            // Explicitly include all fields, including new ones
            woocommerce_key: $('#woocommerce_key').val(),
            woocommerce_secret: $('#woocommerce_secret').val(),
            // Address field
            address: $('#address').val(),
            phone: $('#phone').val(),
            whatsapp_number: $('#whatsapp_number').val(),
            privacy_policy_link: $('#privacy_policy_link').val(),
            theme: {
                colors: {
                    primary: $('input[name="primary_color"]').val() || '#FF5733',
                    secondary: $('input[name="secondary_color"]').val() || '#33FF57'
                }
            },
            working_hours: {},
            activity: {
                is_open: $('input[name="is_open"]').is(':checked')
            },
            checkout_form: {}
        };
        
        // Log the settings to confirm all fields are included
        console.log('Settings to save:', settings);
        
        // Collect working hours
        $('input[name^="working_hours"]').each(function() {
            const name = $(this).attr('name');
            const matches = name.match(/working_hours\[(.*?)\]\[(.*?)\]/);
            if (matches) {
                const day = matches[1];
                const type = matches[2];
                if (!settings.working_hours[day]) {
                    settings.working_hours[day] = {};
                }
                settings.working_hours[day][type] = $(this).val();
            }
        });
        
        // Collect checkout fields
        $('input[name^="checkout_fields"]').each(function() {
            const field = $(this).attr('name').match(/checkout_fields\[(.*?)\]/)[1];
            settings.checkout_form[field] = $(this).is(':checked');
        });
        
        $.ajax({
            url: zstoreAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'save_settings',
                nonce: zstoreAdmin.nonce,
                settings: JSON.stringify(settings)
            },
            success: function(response) {
                if (response.success) {
                    alert('Settings saved successfully!');
                } else {
                    alert('Failed to save settings: ' + response.data);
                }
            }
        });
    });
}); 