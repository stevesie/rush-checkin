<?php
	class Core_View {
		
		protected $c;
		protected $content = array();
		private $cur = false; //current content sub_arr
		
		private $get_vars = array();
		private $js_paths = array(); //avoid loading duplicates
		
		private $app_name;
		
		protected $html_heading = '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		protected $html_start = '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
		protected $head_start = '<head>';
		protected $title = '<title></title>';
		protected $keywords = '<meta name="keywords" content="" />';
		protected $description = '<meta name="description" content="" />';
		protected $head = '';
		protected $head_set = false;
		protected $head_end = '</head>';
		protected $body_start = '<body>';
		protected $end_body = '';
		protected $body_end = '</body>';
		protected $html_end = '</html>';
		
		function set_html_heading($str) {
			$this->html_heading = $str;
		}
		
		function set_html_start($str) {
			$this->html_start = $str;
		}
		
		function set_head_start($str) {
			$this->head_start = $str;
		}
		
		function set_title($str, $override = false) {
			$this->title = $override ? $str : '<title>'.htmlentities($str).'</title>';
		}
		
		function set_keywords($str, $override = false) {
			$this->keywords = $override ? $str : '<meta name="keywords" content="'.htmlentities($str).'" />';
		}
		
		function set_description($str, $override = false) {
			$this->description = $override ? $str : '<meta name="description" content="'.htmlentities($str).'" />';
		}
		
		function add_head($str) {
			$this->head .= $str;
		}
		
		function set_head($str) {
			$this->head = $str;
			$this->head_set = true;
		}
		
		function set_head_end($str) {
			$this->head_end = $str;
		}
		
		function set_body_start($str) {
			$this->body_start = $str;
		}
		
		function add_end_body($str) {
			$this->end_body .= $str;
		}
		
		function set_end_body($str) {
			$this->end_body = $str;
		}
		
		function set_body_end($str) {
			$this->body_end = $str;
		}
		
		function set_html_end($str) {
			$this->html_end = $str;
		}
		
		/**
		 * CONTENT BUFFER
		 */
		function t($str, $ns = 'default') { 
			if($this->cur) {
				if(!array_key_exists($this->cur, $this->content))
					$this->content[$this->cur] = $str;
				else
					$this->content[$this->cur] .= $str;
			}else{
				if(!array_key_exists($ns, $this->content))
					$this->content[$ns] = $str;
				else
					$this->content[$ns] .= $str;
			}
		}
		
		function set_t($val = false) {
			$this->cur = $val;
		}
		
		function declare_order($array) {
			foreach($array as $key => $val)
				$this->content[$key] = '';
		}
		
		
		protected function init() {
		
		}
		
		function get_string() {
			$this->init();
			$out  = $this->html_heading;
			$out .= $this->html_start;
			$out .= $this->head_start;
			if(!$this->head_set) {
				$out .= $this->title;
				$out .= $this->keywords;
				$out .= $this->description;
			}
			$out .= $this->head;
			$out .= $this->head_end;
			$out .= $this->body_start;
			
			foreach($this->content as $str) {
				$out .= $str;
			}
			
			$out .= $this->end_body;
			$out .= $this->body_end;
			$out .= $this->html_end;
			return $out;
		}
		
		function output() {
			echo $this->get_string();
		}
		
		
		/**
		 * CONTROL
		 */
		function c() {
			return $this->c;
		}
		
		function set_controller($controller) {
			$this->c = $controller;
			$this->app_name = $this->c->name();
		}
		
		function __construct() {
			$input = func_get_args();
			if(isset($input)) {
				$this->c = $input[0];
				$this->app_name = $this->c->name();
			}
		}
		
		/**
		 * GET
		 */
		function add_get($name, $value) {
			$this->get_vars[$name] = $value;
		}
		
		function get_get() {
			$first = true;
			$ret = '';
			foreach($this->get_vars as $name => $val) {
				$ret .= $first ? '?' : '&';
				$first = false;
				$ret .= urlencode($name).'='.urlencode($val);
			}
			return $ret;
		}
		
		/**
		 * CSS
		 */
		function add_css($app = false, $file = false, $ie_pre = false, $ie_post = false) {
			$app = $app ? $app : $this->app_name;
			$file = $file ? $file : $app;
			$path = 'apps/'.$app.'/css/'.$file.'.css';
			$this->add_file_css($path, $ie_pre, $ie_post);
		}
		
		function add_file_css($path, $ie_logic = false, $ie_num = false) {
			if($ie_num && $ie_logic) $this->add_head('<!--[if '.$ie_logic.' IE '.$ie_num.']>');
			
			$this->add_head('<link href="'.Core::get_pref("MEDIA_ROOT").$path.'" media="screen" rel="stylesheet" type="text/css" />');
			
			if($ie_num && $ie_logic) $this->add_head('<![endif]-->');
		}
		
		/**
		 * JAVASCRIPT
		 */
		function add_js($app = false, $file = false) {
			$app = $app ? $app : $this->app_name;
			$file = $file ? $file : $app;
			$path = 'apps/'.$app.'/js/'.$file.'.js';
			$this->add_file_js($path);
		}
		
		function add_file_js($path, $load_head = true) {
			if(in_array($path, $this->js_paths)) return;
			$this->js_paths[] = $path;
			if($load_head)
				$this->add_head('<script type="text/javascript" src="'.Core::get_pref("MEDIA_ROOT").$path.'"></script>');
			else
				$this->add_end_body('<script type="text/javascript" src="'.Core::get_pref("MEDIA_ROOT").$path.'"></script>');
		}
		
		function add_url_js($path, $load_head = false) {
			if(in_array($path, $this->js_paths)) return;
			$this->js_paths[] = $path;
			if($load_head)
				$this->add_head('<script type="text/javascript" src="'.$path.'"></script>');
			else
				$this->add_end_body('<script type="text/javascript" src="'.$path.'"></script>');
		}
		
		/**
		 * LINKS
		 */
		function a($string, $link, $css_attr = "") {
			$add_get = strpos($link,'?') !== false ? '' : $this->get_get();
			return '<a '.$css_attr.' href="'.Core::get_pref("URL_ROOT").$link.$add_get.'">'.$string.'</a>';
		}
		
		function a_js($string, $link, $css_attr = "") {
			return '<a '.$css_attr.' href="javascript:'.$link.'">'.$string.'</a>';
		}
		
		/**
		 * MEDIA
		 */
		function img_file($location, $title = "", $css_attr = "") {
			return '<img '.$css_attr.' src="'.Core::get_pref("MEDIA_ROOT").$location.'" title="'.$title.'" alt="'.$title.'" />';
		}
		
		function img($location, $title = "", $css_attr = "") {
			return '<img '.$css_attr.' src="'.Core::get_pref("MEDIA_ROOT").'apps/'.$this->app_name.'/img/'.$location.'" title="'.$title.'" alt="'.$title.'" />';
		}
	}
?>