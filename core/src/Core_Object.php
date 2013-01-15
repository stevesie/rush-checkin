<?php
	class Core_Object extends Core_Data {
		
		protected $table_name;
		private $class_name;
		private $id;
		private $data = array();
		private $fields = array(); //name and type of relation
		
		protected $field_type = 'INT(11)';
		private $string_length = 11;
		protected $app_id;
		protected $object = true;
		
		function table_name() {
			return $this->table_name;
		}
		
		function skeleton($class_name) {
			$this->class_name = $class_name;
		}
		
		function app_id() {
			return $this->app_id;
		}
		
		function add_field($data) {
			$this->fields[] = $data;
		}
		
		function get_fields() {
			return $this->fields;
		}
		
		function load($id) {
			$this->fetch_by_field('id',$id);
		}
		
		function bind_post($fields,$lowercase = false) {
			foreach($fields as $name) {
				if($lowercase)
					$this->set($name,strtolower(Core::get_post($name)));
				else
					$this->set($name,Core::get_post($name));
			}
		}
		
		//need to accomodate for multiple relationships
		function load_schema() {
			$q = "SHOW COLUMNS FROM ".$this->table_name;
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$field_name = $row['field'];
				if(strrpos($field_name,'_key') == 3)
					$this->keys[] = $field_name;
				else
					$this->fields[$field_name] = $row['type'];
			}
		}
		
		function fetch_by_field($field, $value) {
			$value = mysql_escape_string($value);
			$q = "SELECT * FROM ".$this->table_name." WHERE $field = '$value'";
			$result = Core::db()->query($q);
			$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
			if(!$row) return false;
			foreach($row as $key => $val) {
				if($key != "id")
					$this->data[$key] = $val;
				else
					$this->id = $val;
			}
			return true;
		}
		
		function make() {
			$q = "INSERT INTO ".$this->table_name." (";
			foreach($this->data as $key => $val) {
				$q .= "$key, ";
			}
			$q = substr($q, 0, strlen($q) - 2);
			$q .= ") VALUES (";
			foreach($this->data as $key => $val) {
				$val = mysql_escape_string($val);
				if($val != 'NOW()')
					$q .= "'$val', ";
				else
					$q .= "$val, ";
			}
			$q = substr($q, 0, strlen($q) - 2);
			$q .= ")";
			//echo $q;
			Core::db()->query($q);
		}
		
		function update() {
			$q = "UPDATE ".$this->table_name." SET ";
			foreach($this->data as $key => $val) {
				$val = mysql_escape_string($val);
				$q .= "$key = '$val', ";
			}
			$q = substr($q, 0, strlen($q) - 2);
			$q .= " WHERE id = ".$this->id;
			
			Core::db()->query($q);
		}
		
		function delete() {
			if(!$this->id) return;
			$q = "DELETE FROM ".$this->table_name." WHERE id = ".$this->id;
			Core::db()->query($q);
		}
		
		function id() {
			if($this->id)
				return $this->id;
			return 0;
		}
		
		function get($field) {
			return $this->data[$field];
		}
		
		function set($field, $value) {
			$this->data[$field] = $value;
		}
		
		function bind_row($row) {
			foreach($row as $key => $value) {
				if($key == 'id') {
					$this->id = $value;
					continue;
				}
				$this->set($key, $value);
			}
		}
	}
?>