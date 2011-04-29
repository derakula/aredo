<?php
/*
CSS Maker
Create CSS with php with less pain. :) useful for theme framework.

By: Ared Irawsuk (http://dilhamsoft.com)
Version 0.9.2

/*
* Copyright 2009  Ared Irawsuk (email : dilhamsoft@gmail.com)
* Released under the MIT and GPL licenses.
*/

/*
Example how to use:

$css = new DilhamsoftCSSMaker();

$css->add('body', 'background', '#fff');
$css->add('#wrapper', 'background-color', '#fff');
$css->add('#wrapper', 'background-image', 'http://example.com/jpg'); // Background image will work even without url()
$css->add('#slider', 'border-radius', '40px'); //Some CSS 3 tag supported, output will automatic create -moz & -webkit prefix

// You can create multiple value!
$css->add('#sidebar', 'border', array('2px', 'solid', '#000'));

// add many css
$css->adds('#map', array('border-radius' => '40px','box-shadow'=> '20px','background-image' => 'http://dilhamosft.com/images/url/fuck.jpg'));

$css->print_css(); // print result
$css->print_html(); // print with <style> tag

*/
if( !class_exists('DilhamsoftCSSMaker') ):
	class DilhamsoftCSSMaker{
	
	var $minify = true;
	var $styles = array();
	var $css3;
	var $browser_prefix;
	var $result;
	
		function DilhamsoftCSSMaker($options=array()){
			$defaults = array('minify' => true);
			$option = array_merge($defaults, $options);
			$this->minify = $option['minify'];
			$this->css3 = array('border-radius', 'box-shadow');
			$this->browser_prefix = array('-moz-', '-webkit-');
			
			if (!$this->minify){
				$this->tab = "\t";
				$this->line = "\n";
			}
		}
		
		function add($dom, $name, $value){
			if (is_array($value))
				$value = implode(' ', $value);
			$this->styles[$dom][] = array($name => $value);
		}
		
		function adds($dom, $arrayed_value){
			if (!is_array($arrayed_value)) return;
			
			foreach ($arrayed_value as $k => $v)
				$this->add($dom, $k, $v);
		}
		
		function _css_syntax($key, $value){
			return $this->tab . $key . ':' . $value . ';' .$this->line;
		}
		
		function css_syntax($key, $value){
			$validate = array('list-style-image', 'border-image','background-image');

			if (in_array($key, $validate)){
				if ($value != 'none' && !eregi('^url', $value))
					$value = "url('$value')";
			}
			
			if (in_array($key, $this->css3)){
				foreach ($this->browser_prefix as $css3)
					$css .= $this->_css_syntax($css3.$key, $value);
			}
			$css .= $this->_css_syntax($key, $value);
			return $css;
		}
		
		function create_style($dom, $properties){
			return $dom.'{'. $this->line . $properties . '}'.$this->line; 
		}
		
		function print_css(){
			echo $this->get_css();
		}
		function print_html(){
			echo $this->get_html();
		}
		
		function get_html(){
			$css = $this->get_css();
			if ($css)
				return "<style type=\"text/css\">$css</style>\n";
		}
		
		function get_css(){
		$styles = $this->styles;
 			foreach ($styles as $dom => $arrs){
				foreach ($arrs as $properties){
					foreach ($properties as $key => $value){
						//if (empty($value)) continue;
						$css = $this->css_syntax($key, $value);
						$created[$dom] .= $css;
					}
				}
			}
			
			if (!is_array($created))
				return '';
			
			foreach ($created as $dom => $properties)
				$output .= $this->create_style($dom, $properties);
			
			return $this->line. $output;
		}
	}
endif;

?>