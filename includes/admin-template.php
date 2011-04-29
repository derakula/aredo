<?php

/**
 * @author Ared Irawsuk
 * @copyright 2011
 */

function aredo_submit_metabox($theme_options) {
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

class aredoMetaboxTemplate{
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
				$field['get_option'] = $theme_options[$field['name']];
			
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

function aredo_admin_page($args){
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

function aredo_admin_scripts(){
?>
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
?>