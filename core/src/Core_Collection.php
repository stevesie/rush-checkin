<?php
	class Core_Collection {
		
		private $objects = array();
		private $table;
		private $class_name;
		private $where = array();
		private $order = array();
		private $limit;
		
		function __construct($table, $class) {
			$this->table = $table;
			$this->class_name = $class;
		}
		
		function get_objects() {
			return $this->objects;
		}
		
		function add_where($string) {
			$this->where[] = $string;
		}
		
		function add_order($string) {
			$this->order[] = $string;
		}
		
		function set_limit($num) {
			$this->limit = $num;
		}
		
		function build($q = false) {
			if(!$q) {
				$q = "SELECT * FROM ".$this->table;
				if(sizeof($this->where)) {
					$q .= " WHERE ";
					foreach($this->where as $clause)
						$q .= $clause." AND ";
					$q = substr($q, 0, strlen($q) - 5);
				}
				if(sizeof($this->order)) {
					$q .= " ORDER BY ";
					foreach($this->order as $clause)
						$q .= $clause.", ";
					$q = substr($q, 0, strlen($q) - 2);
				}
				if($this->limit)
					$q .= " LIMIT ".$this->limit;
			}
			$class = $this->class_name;
			$result = Core::db()->query($q);
			while($row = $result->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				$object = new $class();
				$object->bind_row($row);
				$this->objects[] = $object;
			}
		}
	}
?>