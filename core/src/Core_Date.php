<?php
	class Core_Date extends Core_Data {
		protected $field_type = 'DATETIME';
		private $year = false;
		private $month = false;
		private $day = false;
		private $hour = false;
		private $minute = false;
		private $day = false;
		
		function form_node($value = '') {
			return '<input name="'.htmlentities($this->field_name()).'" value="'.$value.'" />';
		}
		
		function year() { $this->year = true; }
		function month() { $this->month = true; }
		function day() { $this->day = true; }
		function hour() { $this->hour = true; }
		function minute() { $this->minute = true; }
		function second() { $this->second = true; }
		
		function determine_type() {
			if($this->year && !$this->month && !$this->day && !$this->hour && !$this->minute && !$this->day) {
				$this->field_type = 'INT(4)';
				return;
			}
			if(!$this->year && $this->month && !$this->day && !$this->hour && !$this->minute && !$this->second ||
			   !$this->year && !$this->month && $this->day && !$this->hour && !$this->minute && !$this->second ||
			   !$this->year && !$this->month && !$this->day && $this->hour && !$this->minute && !$this->second ||
			   !$this->year && !$this->month && !$this->day && !$this->hour && $this->minute && !$this->second ||
			   !$this->year && !$this->month && !$this->day && !$this->hour && !$this->minute && $this->second) {
				$this->field_type = 'INT(2)';
				return;
			}
			if(!$this->hour && !$this->minute && !$this->second) {
				$this->field_type = 'DATE';
				return;
			}
			if(!$this->year && !$this->month && !$this->day) {
				$this->field_type = 'TIME';
				return;
			}
			$this->field_type = 'DATETIME';
		}
	}
?>