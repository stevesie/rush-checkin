<?php
	class Core_Action {
	
		private $valid = true; //for backend model
		private $json = array(); //for frontend
		
		private $field_messages = array(); //appears when form refuses to submit
		private $errors = array();
		private $warnings = array(); //appears after completion and HTTP redir
		private $messages = array(); //appears after completion and HTTP redir
		
		function redirect($url) {
			$this->redir = $url;
		}
		
		private $redir;
		
		function add_error($message, $front_field = false) {
			$this->valid = false;
			if($front_field)
				$this->field_messages[$front_field] = array($message,"error");
			else
				$this->errors[] = $message;
		}
		
		function add_warning($message, $front_field = false) {
			if($front_field)
				$this->field_messages[$front_field] = array($message,"warning");
			else
				$this->warnings[] = $message;
		}
		
		function add_message($message, $front_field = false) {
			if($front_field)
				$this->field_messages[$front_field] = array($message,"message");
			else
				$this->messages[] = $message;
		}
		
		function json() {
			$out = array(	"valid" => $this->is_valid(),
							"load" => $this->load_form(),
							"field_messages" => $this->field_messages,
							"errors" => $this->errors,
							"warnings" => $this->warnings,
							"messages" => $this->messages,
							"redirect" => $this->redir);
							
			return json_encode($out);
		}
		
		function is_valid() {
			return $this->valid;
		}
		
		function get_errors() {
			return $this->errors;
		}
		
		function get_warnings() {
			return $this->warnings;
		}
		
		function get_messages() {
			return $this->messages;
		}
		
		function load_form() {
			$override = Core::get_post('override_checks');
			if($override) return true;
			return $this->valid;
		}
	}
?>