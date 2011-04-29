<?php
/*
Plugin Name: Aredo Theme Framework
Plugin URI: http://dilhamsoft.com/
Description: Dilhamsoft Premium Themes Framework
Version: 1.0.0
Author: Ared Irawsuk
Author URI: http://dilhamsoft.com/
*/

class DilhamsoftThemes {
	var $plugin_page;
	var $plugin_url;
	
	function DilhamsoftThemes(){
	
	}
	
	function __construct(){
	
	}
	
}

class DilhamsoftMetabox {
	var $plugin_page;
	var $plugin_url;
	
	function DilhamsoftMetabox(){
	
	}
	
	function __construct(){
	
	}
	
	function form(){
	
	}
	
	function update(){
	
	}
}

add_action('admin_init', 'dthemes_metabox_general');

function dthemes_metabox_general(){

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
	
	
	dthemes_register_metabox('Branding', $branding);
	dthemes_register_metabox('Social Media', $fields);
}



class dthemesMetaboxTemplate{
	var $fields = array();
	
	function dthemesMetaboxTemplate($fields=array()){
		$this->fields = $fields;
	}
	
	function action(){
	
	}
	
	function template($theme_options){
		$simpleform = new SimpleForms();
		$simpleform->auto_id = true;
		$simpleform->prefix_id = 'dthemes-';
		$simpleform->exclude_atts = array('help');
		
		//$simpleform->adds($this->fields);
		
		$box_url = admin_url('media-upload.php?type=image&amp;width=640&amp;height=468&amp;TB_iframe=true');
		
		//$upload_button .= " <a href='#' class='remove-upload hide-if-no-js button'>Del</a>";
		
		foreach ($this->fields as $field){
			if (!isset($field['get_option']))
				$field['get_option'] = get_theme_mod($field['name']);
			
			if ($field['type'] == 'wpupload'){
				$field['type'] = 'text';
				$field['class'] = 'upload-input';
				$rel = $simpleform->get_id($field);
				$upload_button = "<a href='$box_url' class='thickbox add-image hide-if-no-js button' title='Add an Image' rel='$rel'>Add</a>";
				$field['wrap_field'] = '%s ' . $upload_button;
			}
				
			$simpleform->add($field);
		}

		?>
		<div class="dthemes-form">
		<?php foreach ($simpleform->fields as $output): ?>
			<div class="wrap-form">
				<div class="l"><?php echo $output['label']; ?></div>
				<div class="r">
					<?php echo $output['html']; ?>
					<!--<div class="extra"><?php //echo $upload_button;?></div>
					<div class="description"><?php //echo $output['data']['help']; ?></div> -->
				</div>
			</div>
		<?php endforeach; ?>
		</div>
		
		<div class="submit">
			<input name="save" id="save" class="button" value="<?php _e( 'Save' ) ?>" type="submit" />	
			
		</div>
		<div class="clear"></div>
		<?php	
	}
}

function dthemes_register_metabox($title, $fields, $position='normal', $save_callback=''){
	$template = new dthemesMetaboxTemplate($fields);
	add_meta_box('dthemesmetabox_' . sanitize_title($title), $title, array(&$template, 'template'), 'appearance_page_dthemes-options', 'normal', 'core');
}


/*####################################
** ADMIN
######################################*/

class DilhamsoftThemeFramework{
	var $admin_menu = array();
	var $contextual_help;
	var $css_url;
	var $js_url;
	
	function DilhamsoftThemeFramework(){
		
		add_action("admin_menu", array(&$this, 'admin_menu'));
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
		do_action('add_meta_boxes_link', $link);

		do_action('do_meta_boxes', $page_hook, 'normal', $theme_options);
		do_action('do_meta_boxes', $page_hook, 'advanced', $theme_options);
		do_action('do_meta_boxes', $page_hook, 'side', $theme_options);

		add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
		
		add_contextual_help($current_screen, $this->contextual_help);
	}
	
	function admin_scripts(){
		global $page_hook;
?>
<!-- add postbox functionality -->
<script type="text/javascript">jQuery(document).ready( function($) {
	postboxes.add_postbox_toggles('<?php echo $page_hook; ?>'); });
</script>
<!-- add thickbox functionality -->
<script type='text/javascript'>
var set_receiver = function(dom){
	window.receiver = jQuery(dom).attr('id');
}
var send_to_editor = function(img){
	tb_remove();
	var $me = jQuery(img), src;
	if($me.is('a')){
		src = $me.find('img').attr('src');
	}else{
		src = $me.attr('src');
	}
	jQuery('#'+window.receiver).attr('value', src);
	jQuery('#'+window.receiver + '_preview').attr('src', src);
};

jQuery(document).ready(function($){

$("a.add-image").live("click", function(){
	var preview = $(this).attr('rel');
	set_receiver('#'+preview);
});

$('a.remove-upload').click(function(){
	var target = $(this).attr('href');
	var preview = target + '_preview';
	$(target).attr('value', '');
	$(preview).attr('src', '');
		return false;
});

});
</script>
<?php
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
	?>
<div class="wrap">
<?php screen_icon(); ?>
<h2><?php echo esc_html( $title ); ?></h2>

<?php if ( isset( $_GET['saved'] ) ) : ?>
<div id="message" class="updated"><p><?php _e('Options Saved.'); ?></p></div>
<?php endif; ?>

<?php echo $form; ?>

<?php
wp_nonce_field( $nonce_action );
wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ); ?>

<div id="poststuff" class="metabox-holder<?php echo 2 == $screen_layout_columns ? ' has-right-sidebar' : ''; ?>">

<div id="side-info-column" class="inner-sidebar">
<?php

do_action('dthemes_submit_box');
$side_meta_boxes = do_meta_boxes( $page_hook, 'side', $theme_options );

?>
</div><!-- #side-info-column -->

<div id="post-body">
<div id="post-body-content">
<?php do_action('dthemes_body_content_box'); ?>

<?php

do_meta_boxes($page_hook, 'normal', $theme_options);

do_meta_boxes($page_hook, 'advanced', $theme_options);

?>
<input type="hidden" name="action" value="<?php echo $action; ?>" />

</div>
</div>
</div>

</form>
</div>
	<?php
	
	}
	
	function submit_metabox($theme_options) {
	?>
<div class="submitbox" id="submitlink">

<div id="minor-publishing">

<?php // Hidden submit button early on so that the browser chooses the right button when form is submitted with Return key ?>
<div style="display:none;">
<?php submit_button( __( 'Save' ), 'button', 'save', false ); ?>
</div>

<div id="minor-publishing-actions">
<div id="preview-action">
	<a class="preview button" href="<?php bloginfo('home'); ?>" target="_blank" tabindex="4"><?php _e('Preview'); ?></a>
</div>
<div class="clear"></div>
</div>

<div id="misc-publishing-actions">
<div class="misc-pub-section misc-pub-section">
	Aredo Themes
</div>
<div class="misc-pub-section misc-pub-section-last">
	Version: 1.0.0
</div>
</div>

</div>

<div id="major-publishing-actions">
<?php do_action('dthemes_submitbox_start'); ?>
<div id="delete-action">
<?php
/* if ( !empty($_GET['action']) && 'edit' == $_GET['action'] && current_user_can('manage_links') ) {  */?>
	<a class="submitdelete deletion" href="<?php echo wp_nonce_url("link.php?action=delete&amp;link_id=$link->link_id", 'delete-bookmark_' . $link->link_id); ?>" onclick="if ( confirm('<?php echo esc_js(sprintf(__("You are about to delete this link '%s'\n  'Cancel' to stop, 'OK' to delete."), $link->link_name )); ?>') ) {return true;}return false;"><?php _e('Reset to default'); ?></a>
<?php /* }  */ ?>
</div>

<div id="publishing-action">
	<input name="save" type="submit" class="button-primary" id="publish" tabindex="4" accesskey="p" value="<?php esc_attr_e('Save Changes') ?>" />
</div>
<div class="clear"></div>
</div>
<?php do_action('dthemes_theme_box'); ?>
<div class="clear"></div>
</div>
	<?php
	}

}


$dilhamsoft_themes = new DilhamsoftThemeFramework();

?>
