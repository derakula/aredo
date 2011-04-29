<?php
/*
Plugin Name: Aredo Theme Framework
Plugin URI: http://dilhamsoft.com/
Description: Aredo Premium Themes Framework
Version: 1.0.0
Author: Ared Irawsuk
Author URI: http://dilhamsoft.com/
*/

define('AREDO_PLUGIN_MODE', true);

function aredo_get_info($show){
	switch ($show){
		case 'version':
			$output = '1.5.0';
		break;
		case 'includes_url':
			$output = get_template_directory_uri() . '/includes';
			
			if (defined('AREDO_PLUGIN_MODE'))
				$output = plugins_url(__FILE__) . '/includes/';
			
		break;
		case 'documentation':
		
		break;
	}
	return apply_filters('aredo_info', $output, $show);
}

function aredo_register_scripts(){
	$css = aredo_get_info('includes_url') . '/css';
	$js = aredo_get_info('includes_url') . '/js';
	
	/* Lightbox collection */
	wp_register_style('prettyphoto', $css . '/prettyPhoto.css');
	wp_register_script('jquery-prettyphoto', $js . '/jquery.prettyPhoto.min.js', array('jquery'), '2.5.6', 1);
	
	wp_register_script('jquery-mousewheel', $js . '/jquery.mousewheel.min.js', array('jquery'), '3.0.2', 1);
	wp_register_style('fancybox', $css . '/fancybox.css');
	wp_register_script('jquery-fancybox', $js . '/jquery.fancybox.min.js', array('jquery'), '1.3.1', 1);
	
	wp_register_style('colorbox', $css . '/colorbox.css');
	wp_register_script('jquery-colorbox', $js . '/jquery.colorbox.min.js', array('jquery'), '1.3.9', 1);
	
	/* MISC */
	wp_register_script('jquery-gmap', $js . '/jquery.gmap.min.js', array('jquery'), '1.1.0', 1);
}

function aredo_print_js_settings($var_name = 'dthemes', $data=array()){
	$print = json_encode($data);
	
	$output = "var $var_name = $print;";
	
	echo "<script type=\"text/javascripts\">$output</script>\n";
}

function aredo_register_metabox($title, $fields, $position='normal', $save_callback=''){
	$template = new aredoMetaboxTemplate($fields);
	add_meta_box('aredometabox_' . sanitize_title($title), $title, array(&$template, 'template'), 'appearance_page_dthemes-options', 'normal', 'core');
}

function aredo_init(){
	global $aredo_theme;
	$aredo_theme = new AredoThemeFramework();
}
add_action('init', 'aredo_init');

/*####################################
** ADMIN
######################################*/

class AredoThemeFramework{
	var $admin_menu = array();
	var $contextual_help;
	var $css_url;
	var $js_url;
	
	function AredoThemeFramework(){
		
		add_action("admin_menu", array(&$this, 'admin_menu'));
	}
	
	function add_metabox(){
		global $page_hook;
	}
	
	function admin_menu(){
		if ( ! current_user_can('edit_theme_options') )
			return;
			
		$theme_name = get_current_theme();
		
		/* array('page_title', 'menu_name', 'page_url');*/
		$page = add_theme_page($theme_name . ' Theme Options', 'Extend Theme', 'edit_theme_options', 'dthemes-options', array(&$this, 'admin_page'));
		
		add_action("load-$page", array(&$this, 'admin_load'));
	/* 	add_action("load-$page", array(&$this, 'take_action'));
		add_action("load-$page", array(&$this, 'reset_option')); */
	}
	
	function admin_load(){
		global $pagenow, $page_hook, $current_screen;
		
		add_thickbox();
		wp_enqueue_script('postbox');
		wp_enqueue_script('wp-lists');
		
		//add_action('admin_print_footer_scripts', 'dthemes_admin_scripts', 50);
		
		add_action('admin_head', array(&$this, 'admin_scripts'), 50);
		
		$theme_options = get_theme_mods();

		add_meta_box('themeoptionsubmitdiv', __('Save'), array(&$this, 'submit_metabox'), $page_hook, 'side', 'core');
	/*	add_meta_box('dthemes_metabox_general', 'Branding', 'dthemes_metabox_general', 'dilhamsoft_themes', 'normal', 'core'); */
		
		do_action('add_meta_boxes', $page_hook, $theme_options);
		do_action('add_meta_boxes_link', $theme_options);

		do_action('do_meta_boxes', $page_hook, 'normal', $theme_options);
		do_action('do_meta_boxes', $page_hook, 'advanced', $theme_options);
		do_action('do_meta_boxes', $page_hook, 'side', $theme_options);

		add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
		
		add_contextual_help($current_screen, $this->contextual_help);
	}
	
	function admin_scripts(){
		global $page_hook;
		
		/* add postbox functionality */
		
		echo "<script type=\"text/javascript\">jQuery(document).ready( function($) { postboxes.add_postbox_toggles('$page_hook'); });</script>\n";
	}
	
	function admin_page(){
		global $pagenow, $plugin_page, $page_hook, $title, $screen_layout_columns, $current_screen;
		$heading = sprintf( __( '<a href="%s">Links</a> / Add New Link' ), 'link-manager.php' );

		$plugin_url = add_query_arg('page', $plugin_page, $pagenow);
		$submit_text = __('Save Changes', 'dilhamsoft-themes');
		$form = "<form name=\"addlink\" id=\"addlink\" method=\"post\" action=\"$plugin_url\">";
		$action = 'update-theme-options';
		$nonce_action = 'update-theme-options';
		$theme_options = get_theme_mods();
		
		do_action('aredo_admin_page-' . $page_hook, compact($plugin_url, $form, $action, $nonce_action, $theme_options));
	}
	
	function update_option($name, $value){
		return set_theme_mod($name, $value);
	}
	
	function bulk_save($post, $fields){
	
		foreach ($fields as $section => $fields){
			foreach ($fields as $save){
				$name = $save['name'];
				$value = $post[$name];
								
				if (isset($value)){
					$this->update_option($name, $value);
				}
			}
		}
	}
	
}




?>
