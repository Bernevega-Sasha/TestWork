<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'twenty-twenty-one-style','twenty-twenty-one-style','twenty-twenty-one-print-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

function custom_product_metabox() {
    add_meta_box(
        'custom-product-metabox',
        'Custom field',
        'render_custom_product_metabox',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'custom_product_metabox');

function render_custom_product_metabox($post) {
    $custom_image = get_post_meta($post->ID, 'custom_image', true);
    $custom_date = get_post_meta($post->ID, 'custom_date', true);
    $custom_type = get_post_meta($post->ID, 'custom_type', true);
    ?>
    <p>
        <label for="custom_image">Product image:</label>
        <input type="text" id="custom_image" name="custom_image" value="<?php echo esc_attr($custom_image); ?>" />
        <input type="button" id="custom_image_button" class="button" value="Download image" />
        <input type="button" id="custom_image_remove_button" class="button" value="Delete image" />
    </p>
    <div id="image_preview">
            <?php if (!empty($custom_image)) : ?>
                <img src="<?php echo esc_url($custom_image); ?>" alt="Product Image" style="max-width: 200px;" />
            <?php endif; ?>
        </div>
    <p>
        <label for="custom_date">Product creation date:</label>
        <input type="date" id="custom_date" name="custom_date" value="<?php echo esc_attr($custom_date); ?>" />
    </p>

    <p>
        <label for="custom_type">Product type:</label>
        <select id="custom_type" name="custom_type">
            <option value="rare" <?php selected($custom_type, 'rare'); ?>>rare</option>
            <option value="frequent" <?php selected($custom_type, 'frequent'); ?>>frequent</option>
            <option value="unusual" <?php selected($custom_type, 'unusual'); ?>>unusual</option>
        </select>
    </p>

    <p>
        <input type="button" id="custom_delete_metadata_button" class="button" value="Delete metadata" />
        <input type="submit" name="save" id="publish" class="button button-primary button-large" value="Update">
    </p>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#custom_image_button').click(function(e) {
                e.preventDefault();
                var image = wp.media({
                    title: 'Select an image',
                    multiple: false
                }).open().on('select', function(e) {
                    var uploadedImage = image.state().get('selection').first();
                    var imageUrl = uploadedImage.toJSON().url;
                    $('#custom_image').val(imageUrl);
                });
            });

            $('#custom_image_remove_button').click(function(e) {
                e.preventDefault();
                $('#custom_image').val('');
            });

            $('#custom_update_metadata_button').click(function(e) {
                e.preventDefault();
                var productId = <?php echo $post->ID; ?>;
                var metadata = {
                    custom_image: $('#custom_image').val(),
                    custom_date: $('#custom_date').val(),
                    custom_type: $('#custom_type').val()
                };

                var productData = {
                    post_id: productId,
                    post_title: $('#title').val(),
                    post_content: $('#content').val()
                };

                var updatedProductData = $.extend({}, metadata, productData);

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'custom_update_product',
                        product_id: productId,
                        product_data: updatedProductData
                    },
                    success: function(response) {
                        alert('Product update');
                    }
                });
            });

            $('#custom_delete_metadata_button').click(function(e) {
                e.preventDefault();
                var productId = <?php echo $post->ID; ?>;
                var metadataKeys = ['custom_image', 'custom_date', 'custom_type'];
                $.each(metadataKeys, function(index, key) {
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'custom_delete_metadata',
                            product_id: productId,
                            metadata_key: key
                        },
                        success: function(response) {
                            $('#custom_image').val('');
                            $('#custom_type').val('');
                            $('#custom_date').val('');
                            $('#image_preview').html('');
                        }
                    });
                });
            });
        });
    </script>
    <?php
}

function save_custom_product_metabox($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    if (isset($_POST['custom_image'])) {
        update_post_meta($post_id, 'custom_image', sanitize_text_field($_POST['custom_image']));
    }

    if (isset($_POST['custom_date'])) {
        update_post_meta($post_id, 'custom_date', sanitize_text_field($_POST['custom_date']));
    }

    if (isset($_POST['custom_type'])) {
        update_post_meta($post_id, 'custom_type', sanitize_text_field($_POST['custom_type']));
    }
}
add_action('save_post', 'save_custom_product_metabox');

add_action('wp_ajax_custom_delete_metadata', 'custom_delete_metadata');
function custom_delete_metadata() {
    if (isset($_POST['product_id']) && isset($_POST['metadata_key'])) {
        $product_id = intval($_POST['product_id']);
        $metadata_key = sanitize_text_field($_POST['metadata_key']);
        
        delete_post_meta($product_id, $metadata_key);
        die();
    }
}

add_action('wp_ajax_custom_update_product', 'custom_update_product');
function custom_update_product() {
    if (isset($_POST['product_id']) && isset($_POST['product_data'])) {
        $product_id = intval($_POST['product_id']);
        $product_data = $_POST['product_data'];

        wp_update_post($product_id);

        foreach ($product_data as $key => $value) {
            if (metadata_exists('post', $product_id, $key)) {
                update_post_meta($product_id, $key, sanitize_text_field($value));
            } else {
                add_post_meta($product_id, $key, sanitize_text_field($value), true);
            }
        }

        die();
    }
}

function loadmore_script() {
    wp_enqueue_script('jquery'); 
    wp_enqueue_script('loadmore__', get_stylesheet_directory_uri() . '/assets/js/loadmore.js', array('jquery')); 
    }
add_action('wp_enqueue_scripts', 'loadmore_script');

function loadmore_get_posts(){
$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = unserialize(stripslashes($_POST['query']));
$args['paged'] = $_POST['page'] + 1; 
$args['post_status'] = 'publish';
query_posts($args);
if(have_posts()) :
    while ( have_posts() ) :
        the_post();
        $custom_image = get_post_meta(get_the_ID(), 'custom_image', true);
        $custom_date = get_post_meta(get_the_ID(), 'custom_date', true);
        $custom_type = get_post_meta(get_the_ID(), 'custom_type', true);
    
        get_template_part( 'template-parts/content/content-single' );
        echo '<p>' .  esc_attr($custom_date) . '<br>' . esc_attr($custom_type);  
    
    endwhile; 
endif;
die();
}

add_action('wp_ajax_loadmore', 'loadmore_get_posts');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_get_posts');

