<?php

/**
 * @author Ared Irawsuk
 * @link http://dilhamsoft.com
 * @name DilhamsoftHTMLtags
 * @package DilhamsoftPluginFramework
 * @version 1.0.0
 * @copyright 2011
 * @license GNU GENERAL PUBLIC LICENSE
 */


class DilhamsoftHTMLtags{
	function DilhamsoftHTMLtags(){
	
	}
	
	function a($atts=array(), $content=''){
		return $this->html_tag('a', $atts, $content, true);
	}
	function script($atts=array(), $content=''){
		$atts = wp_parse_args($atts, array('type' => 'text/javascript'));
		return $this->html_tag('script', $atts, $content, true);
	}
	function style($atts=array(), $content=''){
		$atts = wp_parse_args($atts, array('type' => 'text/css'));
		return $this->html_tag('style', $atts, $content, true);
	}
	function img($atts=array()){
		return $this->html_tag('img', $atts);
	}
	
	function input($atts=array()){
		return $this->html_tag('input', $atts);
	}
	
	function meta_robots($atts=array()){
		$args = is_array($atts) ? $atts : array('content' => $atts);
		$args = wp_parse_args($args, array('name' => 'robots'));
		return $this->meta($args);
	}
	function meta_description($atts=array()){
		$args = is_array($atts) ? $atts : array('content' => $atts);
		$args = wp_parse_args($args, array('name' => 'description'));
		return $this->meta($args);
	}
	function meta_keywords($atts=array()){
		$args = is_array($atts) ? $atts : array('content' => $atts);
		$args = wp_parse_args($args, array('name' => 'keywords'));
		return $this->meta($args);
	}
	function meta($atts=array()){
		return $this->html_tag('meta', $atts);
	}
	
	function link_canonical($atts=array()){
		$args = is_array($atts) ? $atts : array('href' => $atts);
		$args = wp_parse_args($args, array('rel' => 'canonical'));
		return $this->link($args);
	}
	function link_favicon($atts=array()){
		$args = is_array($atts) ? $atts : array('href' => $atts);
		$args = wp_parse_args($args, array('rel' => 'shortcut icon'));
		return $this->link($args);
	}
	function link_feed($atts=array()){
		$args = is_array($atts) ? $atts : array('href' => $atts);
		$args = wp_parse_args($args, array('rel' => 'alternate', 'type' => 'application/rss+xml'));
		return $this->link($args);
	}
	function link_stylesheet($atts=array()){
		$args = is_array($atts) ? $atts : array('href' => $atts);
		$args = wp_parse_args($args, array('rel' => 'stylesheet', 'type' => 'text/css'));
		return $this->link($args);
	}
	function link($atts=array()){
		return $this->html_tag('link', $atts);
	}
	
	function html_tag($tag, $atts=array(), $content='', $close_tag=false){
		
		$html_atts = $this->html_attributes($atts);
		$tag = tag_escape($tag);
		
		if ($close_tag)
			$html = '<' . $tag . $html_atts . '>' . esc_html($content) . '</' . $tag . '>' . '\n';
		else
			$html = '<' . $tag . $html_atts . ' />' . "\n";
			
		return $html;
	}

	function html_attributes($atts = array()){
		if (!is_array($atts) || empty($atts))
			return null;
		
		$output = '';
		foreach ($atts as $att => $att_v){
			$att = tag_escape($att);
			$att_v = (is_array($att_v)) ? implode(' ', $att_v) : $att_v;
			$att_v = esc_attr($att_v);
			$output .= " $att=\"$att_v\"";
		}
		
		return $output;
	}

}

?>