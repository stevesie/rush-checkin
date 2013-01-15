<?php
	class Core_Number extends Core_Data {
		
		protected $field_type = 'INT(11)';
		private $number_length = 11;
		private $negative = false;
		
		function form_node() {
			return '<input name="'.htmlentities($this->field_name()).'" />';
		}
		
		function length($len) {
			$this->number_length = $len;
		}
		
		function set_decimal() {
			$this->field_type = 'DECIMAL(11,'.$number_length.')';
		}
		
		function set_negative() {
			$this->negative = true;
		}
		
		function is_negative() {
			return $this->negative;
		}
		
	}
?>