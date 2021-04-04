<?php 
/*
Plugin Name: GA Responsive Slider
Plugin URI: https://anmolzabiha.com/plugins/responsive-slider/
Description: ga Full Complete Responsive Slider, just go to sliders post, and add new slider. and use this shortcode [ga_sliders] in any pages or posts. then enjoy it <?php echo do_shortcode('[ga_sliders]'); ?>.
Version: 1.1
Author: Ahmed Ali Tariq
Author URI: https://anmolzabiha.com/
 TextDomain: ga-slider

*/


function ga_slider_custom_post() {
	register_post_type( 'sliders', 
			array(
			'labels' => array(
				'name' => __( 'Sliders', 'ga-slider' ),
				'singular_name' => __( 'Slider', 'ga-slider' ),
				'add_new' => __( 'Add New Slider', 'ga-slider' ),
				'add_new_item' => __( 'Add New Slider', 'ga-slider' ),
				'edit_item'		=> __('Edit Slider Info', 'ga-slider'),
				'view_item'		=> __('View Slider Info', 'ga-slider'), 				
				'not_found' => __( 'Sorry, we couldn\'t find the Slider you are looking for.', 'ga-slider' )
			),
			'public' => true,
			'menu_icon'	=> 'dashicons-admin-users',
			'supports' => array('title', 'custom-fields', 'thumbnail', 'editor'),
			'has_archive' => true,
			'rewrite' => array('slug' => 'slider'),
			'capability_type' => 'page', 
		)
	
	
	);
	
}
add_action('after_setup_theme', 'ga_slider_custom_post');

function ga_slider_normal_file(){
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-thumbnails', array( 'sliders' ) );  
	add_image_size( 'ga_slider_img', 1170, 300 );
	
}
add_action('after_setup_theme', 'ga_slider_normal_file');


function ga_slider_plugin_script(){
	
	wp_enqueue_style( 'ga-slider',  plugin_dir_url( __FILE__ ) . '/gaslider.css', array(), '1.0' );
	
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'ga-slidermin',  plugin_dir_url( __FILE__ ) . '/gaslider.min.js', array( 'jquery' ), '1.0', true );
	
	wp_enqueue_script( 'ga-slider-main',  plugin_dir_url( __FILE__ ) . '/ga-main.js', array( 'jquery' ), '1.0', true );
	
}
add_action('wp_enqueue_scripts', 'ga_slider_plugin_script');

function ga_slider_shortcode($atts){
	extract( shortcode_atts( array(
		'count' => 5,
		'posttype' => 'sliders',
	), $atts ) );
	
    $q = new WP_Query(
        array('posts_per_page' => $count, 'post_type' => $posttype)
        );		
		
		
	$list = '<ul class="gaSlider">';
	while($q->have_posts()) : $q->the_post();
		$idd = get_the_ID();
		$ga_slider_img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'ga_slider_img' );
		$list .= '
		<li><img src="'.$ga_slider_img[0].'" alt="'.get_the_title().'" data-description="'.get_the_content().'"></li>
		
		';        
	endwhile;
	$list.= '</ul>';
	wp_reset_query();
	return $list;
}
add_shortcode('ga-sliders', 'ga_slider_shortcode');	



