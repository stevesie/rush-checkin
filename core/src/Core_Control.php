<?php
	class Core_Control {
	
		protected $view;
		protected $model;
		
		private $name = '';
		
		function model() {
			return $this->model;
		}
		
		function view() {
			return $this->view;
		}
		
		function __construct() {
			$input = func_get_args();
			if(isset($input[0])) {
				$this->name = $input[0];
			}
		}
		
		function execute() {
			$this->execute_action();
		}
		
		function execute_action() {
			
			sleep(1);
			if(!$this->model) {
				$model_name = ucfirst($this->name()).'_Model';
				$this->model = new $model_name;
			}
			
			$act = Core::get_post('ajax_action');
			$commit = Core::get_post('commit');
			$override = Core::get_post('override_checks');
			
			if(($act && $commit != 'false') && $override != 'true') {
				
				$this->model->$act();
				echo $this->model->get_action()->json();
				return true;
			}
			return false;
		}
		
		function get_url_command($key) {
			$url = Core::get_url();
			$url_arr = explode("/",$url);
			if(!isset($url_arr[$key])) return false;
			return $url_arr[$key];
		}
		
		function get_url() {
			$url = $this->http['REQUEST_URI'];
			$root_url = Core::get_pref("URL_ROOT");
			$root_preg = str_replace("/","\/",$root_url);
			$url = preg_replace("/^".$root_preg."/","",$url);
			return $url;
		}
		
		function get_clean_post($key) {
			if(!isset($_POST[$key])) return null;
			return trim(htmlspecialchars_decode(stripslashes($_POST[$key])));
		}
		
		function set_name($name) { $this->name = $name; }
		function name() { return $this->name; }
		function get_name() { return $this->name; }
	}
?>