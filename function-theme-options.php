<?php

/**
 * @author Ared Irawsuk
 * @copyright 2011
 */


add_action('admin_init', 'my_example_theme_options');

function my_example_theme_options(){

	/* Branding fields */
	$branding[] = array(
			'label' => 'Logo',
			'name' => 'logo',
			'type' => 'wpupload',
			'get_option' => '',
			'help' => 'Logo Banget',
		);
		
	$branding[] = array(
			'label' => 'Favicon',
			'name' => 'favicon',
			'type' => 'wpupload',
			'get_option' => '',
		);
		
	$branding[] = array(
			'label' => 'Footer Text',
			'name' => 'footer_text',
			'type' => 'textarea',
			'get_option' => '',
		);
	
	/* Social media fields */
	$fields[] = array(
			'label' => 'Feedburner RSS Feed',
			'name' => 'feedburner_rss',
			'type' => 'text',
			'get_option' => '',
			
	);
	
	$fields[] = array(
			'label' => 'Feedburner Email ID',
			'name' => 'feedburner_email',
			'type' => 'text',
			'get_option' => '',
	);
	
	$fields[] = array(
			'label' => 'Twitter ID',
			'name' => 'twitter_id',
			'type' => 'text',
			'get_option' => '',
	);
	
	$fields[] = array(
			'label' => 'Facebook API',
			'name' => 'facebook_api',
			'type' => 'text',
			'get_option' => '',
	);
	
	$fields[] = array(
			'label' => 'Facebook App Screet',
			'name' => 'facebook_app_screet',
			'type' => 'text',
			'get_option' => '',
	);
	
	/* Advanced fields */
	
	
	aredo_register_metabox('Branding', $branding);
	aredo_register_metabox('Social Media', $fields);
}

?>