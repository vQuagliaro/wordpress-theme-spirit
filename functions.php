<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'customize_register', array( $this, 'register_customize' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['logo'] = get_option('my_theme_logo');
		$context['site'] = $this;
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		return $twig;
	}

	function register_customize( $wp_customize ) {

		$wp_customize->remove_section('static_front_page');


		$wp_customize->add_section( 'my_theme_new_section_name' , array(
				'title'      => __('Visible Section Name','my_theme'),
				'priority'   => 30,
		));

		$wp_customize->add_setting('my_theme_image_background', array(
				'capability'        => 'edit_theme_options',
				'type'           => 'option'
		));

		$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'image_upload_test', array(
				'label'    => __('Image Upload Test', 'themename'),
				'section'  => 'my_theme_new_section_name',
				'settings' => 'my_theme_image_background',
		)));

		$wp_customize->add_section( 'my_theme_header' , array(
				'title'      => __('Header','my_theme'),
				'priority'   => 30,
		));

		$wp_customize->add_setting('my_theme_logo', array(
				'capability'        => 'edit_theme_options',
				'type'           => 'option'
		));

		$wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'image_upload_test', array(
				'label'    => __('Image Upload Test', 'themename'),
				'section'  => 'my_theme_header',
				'settings' => 'my_theme_logo',
		)));
	}
}

new StarterSite();

function myfoo( $text ) {
	$text .= ' bar!';
	return $text;
}



