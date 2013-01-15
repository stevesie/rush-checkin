<?php
	class Form_Lib {
		
		private $view;
		
		private $action;
		private $validate;
		
		private $destination;
		private $form_head;
		
		private $struct = array();
		
		private $errors;
		private $captcha = false;
		
		private $tiny_name;
		private $tiny_id;
		
		function __construct($view) {
			$view->add_file_js('lib/form/js/form.js');
			//$view->add_file_js('/ext/json2/json2.js');
			$view->add_url_js('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',true);
			$view->add_file_css('lib/form/css/form.css');
			$this->view = $view;
		}

		function set_tiny_mce($name, $id) {
			$this->tiny_name = $name;
			$this->tiny_id = $id;
		}
		
		function get_form_tag($id = false, $process_url = false, $do_ajax = true) {
			$str = '<form method="post"';
			
			if($do_ajax) {
				$str .= ' onsubmit="return attempt_submit(this';
				
				if($this->tiny_name && $this->tiny_id) {
					$str .= ', \''.$this->tiny_name.'\', \''.$this->tiny_id.'\'';
				}else
					$str .= ',false,false';
				$str .= ');"';
			}
				
			if($process_url) $str .= ' action="'.Core::get_pref("URL_ROOT").$process_url.'"';
			else $str .= ' action="'.Core::get_pref("URL_ROOT").$this->view->c()->get_name().'"';
			if($id) $str .= ' id="'.$id.'"';
			$str .= '><input type="hidden" name="commit" value="false"/>';
			return $str;
		}
		
		function get_action_tag($action) {
			$str = '<input type="hidden" name="ajax_action" value="'.$action.'"/>';
			return $str;
		}
		
		function get_silent_tag() {
			$str = '<input type="hidden" name="silent" value="true"/>';
			return $str;
		}
		
		function get_commit_tag($send) {
			$str = '<input type="hidden" name="commit" value="'.$send.'"/>';
			return $str;
		}
		
		function get_override_tag() {
			$str = '<input type="hidden" name="override_checks" value="true"/>';
			return $str;
		}
		
		function get_captcha_tag() {
			$lib = new Recaptcha_Lib();
			return $lib->get_box();
		}
		
		function get_errors_tag() {
			$str = '<div id="form_errors"><ul id="form_errors_ul">';
			
			$model = $this->view->c()->model();
			if(isset($model) && isset($_POST['commit'])) {
				$action = $this->view->c()->model()->get_action();
				$make_visible = true;
				
				$arr = array();
				foreach($action->get_errors() as $error) {
					if($make_visible) {
						$str = '<div id="form_errors" style="display:block;"><ul>';
						$make_visible = false;
					}
					if(!in_array($error,$arr))
						$str .= '<li>'.htmlentities($error).'</li>';
					$arr[] = $error;
				}
			}
			
			$str .= '</ul></div>';
			return $str;
		}
		
		function get_messages_tag() {
			
			$str = '<div id="form_messages"><ul id="form_messages_ul">';
			
			$model = $this->view->c()->model();
			if(isset($model) && isset($_POST['commit'])) {
				$action = $this->view->c()->model()->get_action();
				$make_visible = true;
				
				$arr = array();
				foreach($action->get_messages() as $message) {
					if($make_visible) {
						$str = '<div id="form_messages" style="display:block;"><ul>';
						$make_visible = false;
					}
					if(!in_array($message,$arr))
						$str .= '<li>'.htmlentities($message).'</li>';
					$arr[] = $message;
				}
			}
			
			$str .= '</ul></div>';
			return $str;
		
		}
	}
?>