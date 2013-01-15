<?php
	class Core_Model {
		
		protected $c; //controller
		protected $action;
		
		protected $model_objects; //relevant DB objects to the model
		
		protected $used_fields;
		protected $validations; //array of field name with an array of check functions
		
		function __construct() {
			$input = func_get_args();
			if(isset($input[0])) {
				$this->c = $input[0];
			}
			$this->action = new Core_Action();
		}
		
		function set_controller($controller) {
			$this->c = $controller;
		}
		
		function use_field($name) {
			$used_fields[] = $name;
		}
		
		
		function get_action() {
			return $this->action;
		}
		
		function generate_db_code($table_name, $field = 'cookie') {
			while(1 == 1) {
				$code = $this->generate_password(26);
				$q = "SELECT COUNT(*) AS counter FROM $table_name WHERE $field = '$code'";
				$result = Core::db()->query($q);
				$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
				$answer = $row['counter'];
				if($answer == 0)
					return $code;
			}
		}
		
		function generate_password($length = 5) {
			$ret = "";
			$chars = "0123456789bcdfghjkmnpqrstvwxyz"; 	
			for($i = 0; $i < $length;$i++) { 
				$char = substr($chars, mt_rand(0, strlen($chars)-1), 1);
				$ret .= $char;
			}
			return $ret;
		} 
		
		function check_unique($table, $field, $value) {
			$error = 'Sorry, this is already taken.';
			$q = 'SELECT COUNT(*) AS counter FROM '.$table.' WHERE '.$field.' = "'.mysql_escape_string($value).'"';
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			return $row['counter'] == 0;
		}
		
		function check_alphanumeric($field, $value) {
			$error = 'Please enter only letters and/or numbers.';
			$valid = regi('[^a-z0-9]', $v_username) ? false : true;
			
		}
		
		function check_nospaces($field, $value) {
			$error = 'Please remove spaces.';
		}
		
		function check_email($value) {
			return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $value) ? true : false;
		}
	}
?>