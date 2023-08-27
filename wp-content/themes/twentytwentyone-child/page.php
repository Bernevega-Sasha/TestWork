<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

get_header();
$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
$count_posts = wp_count_posts();


$params = array(
	'posts_per_page' => 3, 
	'post_type'       => 'product',
	'paged'           => $current_page 
);
query_posts($params);

$max_pages = $wp_query->max_num_pages;
$wp_query->is_archive = true;
$wp_query->is_home = false;
while ( have_posts() ) :
	the_post();
	$custom_image = get_post_meta($post->ID, 'custom_image', true);
    $custom_date = get_post_meta($post->ID, 'custom_date', true);
    $custom_type = get_post_meta($post->ID, 'custom_type', true);

	get_template_part( 'template-parts/content/content-single' );
	echo '<p>' .  esc_attr($custom_date) . '<br>' . esc_attr($custom_type);   
endwhile; 
if ($wp_query->max_num_pages > 1) : ?>
	<script>
		var ajaxurl = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
		var posts_vars = '<?php echo serialize($wp_query->query_vars); ?>';
		var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
		var max_pages = '<?php echo $max_pages; ?>';
	</script>
	<button style="position: relative; left: 50%;" id="loadmore">Load More</button>
<?php endif; 
get_footer();