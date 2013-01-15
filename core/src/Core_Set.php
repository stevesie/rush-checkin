<?php
	
	class Core_Set extends Core_Data {
		
		private $mutual_exclusion = true;
		private $values = array();
		private $labels = array();
		private $null = true; //can we have a null selection
		private $default = false;
		private $index = 1;
		
		function field_type() {
			$o = '';
			if($this->mutual_exclusion)
				$o = 'ENUM(';
			else
				$o = 'SET(';
			foreach($this->values as $val) {
				$o .= "'$val',";
			}
			if(sizeof($this->values) > 0)
				$o = substr($o,0,strlen($o)-1);
			$o .= ')';
			return $o;
		}
		
		function option($value, $label = false) {
			if(in_array($value, $this->values)) return;
			
			if(!$label) $label = $value;
			
			$this->values[$this->index] = $value;
			$this->labels[$this->index] = $label;
			$this->index++;
		}
		
		function not_mutex() {
			$this->mutual_exclusion = false;
		}
		
		function is_mutex() {
			return $this->mutual_exclusion;
		}
		
		function bind($table,$class,$label) {
		
			$labels = split(" ",$label);
			
			$coll = new Core_Collection($table,$class);	
			$coll->build();
			foreach($coll->get_objects() as $obj) {
				$id = $obj->id();
				$label = '';
				foreach($labels as $find) {
					$label .= $obj->get($find).' ';
				}
				$label = trim($label);
				
				$this->option($id,$label);
			}
			
		}
		
		function form_node($inject = '') {
			
			if($this->mutual_exclusion) {
			
				$o = '<select name="'.htmlentities($this->field_name()).'">';
				
				if(!$this->is_required())
					$o .= '<option value="">Select</option>';
				
				foreach($this->values as $index => $val) {
					$sel = '';
					if($this->default !== false && $index == $this->default) $sel = 'selected="selected" ';
					$o .= '<option '.$sel.'value="'.$this->values[$index].'">'.htmlentities($this->labels[$index]).'</option>';
				}
				
				$o .= '</select>';
				return $o;
			
			}else
			
				return '<input name="" />';
		
		}
		
		function add_yes_no($default = 'Yes') {
			$yes_index = $this->index;
			$this->option('Yes');
			$no_index = $this->index;
			$this->option('No');
			if($default == 'Yes') $this->default = $yes_index;
			else $this->default = $no_index;
		}
		
		function add_states() {
			$this->option('AL','Alabama');
			$this->option('AK','Alaska');
			$this->option('AS','American Samoa');
			$this->option('AZ','Arizona');
			$this->option('AR','Arkansas');
			$this->option('CA','California');
			$this->option('CO','Colorado');
			$this->option('CT','Connecticut');
			$this->option('DE','Delaware');
			$this->option('DC','District of Columbia');
			$this->option('FM','Federated States of Micronesia');
			$this->option('FL','Florida');
			$this->option('GA','Georgia');
			$this->option('GU','Guam');
			$this->option('HI','Hawaii');
			$this->option('ID','Idaho');
			$this->option('IL','Illinois');
			$this->option('IN','Indiana');
			$this->option('IA','Iowa');
			$this->option('KS','Kansas');
			$this->option('KY','Kentucky');
			$this->option('LA','Louisiana');
			$this->option('ME','Maine');
			$this->option('MH','Marshall Islands');
			$this->option('MD','Maryland');
			$this->option('MA','Massachusetts');
			$this->option('MI','Michigan');
			$this->option('MN','Minnesota');
			$this->option('MS','Mississippi');
			$this->option('MO','Missouri');
			$this->option('MT','Montana');
			$this->option('NE','Nebraska');
			$this->option('NV','Nevada');
			$this->option('NH','New Hampshire');
			$this->option('NJ','New Jersey');
			$this->option('NM','New Mexico');
			$this->option('NY','New York');
			$this->option('NC','North Carolina');
			$this->option('ND','North Dakota');
			$this->option('MP','Northern Mariana Islands');
			$this->option('OH','Ohio');
			$this->option('OK','Oklahoma');
			$this->option('OR','Oregon');
			$this->option('PW','Palau');
			$this->option('PA','Pennsylvania');
			$this->option('PR','Puerto Rico');
			$this->option('RI','Rhode Island');
			$this->option('SC','South Carolina');
			$this->option('SD','South Dakota');
			$this->option('TN','Tennessee');
			$this->option('TX','Texas');
			$this->option('UT','Utah');
			$this->option('VT','Vermont');
			$this->option('VI','Virgin Islands');
			$this->option('VA','Virginia');
			$this->option('WA','Washington');
			$this->option('WV','West Virginia');
			$this->option('WI','Wisconsin');
			$this->option('WY','Wyoming');
		}
	
	}
	
?>