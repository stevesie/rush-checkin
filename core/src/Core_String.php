<?php
	class Core_String extends Core_Data {
		
		protected $field_type = 'VARCHAR(255)';
		private $string_length = 255;
		private $html = false;
		
		private $phone = false;
		private $password = false;
		private $email = false;
		
		function set_phone() { $this->phone = true; }
		function set_password() { $this->password = true; }
		function set_email() { $this->email = true; }
		
		function get_phone() { return $this->phone; }
		function get_password() { return $this->password; }
		function get_email() { return $this->email; }
		
		function form_node($value = '') {
			return '<input name="'.htmlentities($this->field_name()).'" value="'.$value.'" id="form_'.htmlentities($this->field_name()).'" />';
		}
		
		function get_length() {
			return $this->string_length;
		}
		
		function length($len) {
			if(!is_integer($len)) return false;
			if($len < 1 || $len > 255) return false;
			$this->string_length = $len;
			$this->field_type = "VARCHAR($len)";
		}
		
		function text() {
			$this->string_length = false;
			$this->field_type = 'TEXT';
		}
		
		function set_html() {
			$this->html = true;
		}
		
		function is_html() {
			return $this->html;
		}
	}
?>