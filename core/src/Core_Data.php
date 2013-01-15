<?php
	
	class Core_Data {
		
		private $field; //field name used in the database and form POST
		private $label; //field used in the view for the data field
		private $multiple = false;
		private $unique = false;
		private $required = false;
		private $table = false;
		private $locked = false; //can only admins edit this?
		protected $object = false;
		
		function is_obj() {
			return $this->object;
		}
		
		protected $field_type = false;
		
		function __construct($field = false, $label = false) {
			if(!$label) $label = $field;
			$this->field = $field;
			$this->label = $label;
		}
		
		function field_type() {
			return $this->field_type;
		}
		
		function has_many() {
			$this->multiple = true;
		}
		
		function is_many() {
			return $this->multiple;
		}
		
		function unique() {
			$this->unique = true;
		}
		
		function field_name() {
			return $this->field;
		}
		
		function field_label() {
			return $this->label;
		}
		
		function required() {
			$this->required = true;
		}
		
		function is_required() {
			return $this->required;
		}
		
		function table() {
			$this->table = true;
		}
		
		function is_table() {
			return $this->table;
		}
		
		function locked() {
			$this->locked = true;
		}
		
		function is_locked() {
			return $this->locked;
		}
		
	}
	
?>