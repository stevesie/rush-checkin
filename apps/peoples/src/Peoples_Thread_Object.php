<?php
	class Peoples_Thread_Object extends Core_Object {
		
		protected $table_name = 'peoples_threads';
		protected $app_id = 'peoples';
		
		function get_messages() {
			$messages = new Core_Collection('peoples_threads_posts','Peoples_Thread_Post_Object');
			$messages->add_where('thread_id = '.$this->id());
			$messages->build();
			return $messages->get_objects();
		}
		
	}
	class Peoples_Thread_Post_Object extends Core_Object {
		
		protected $table_name = 'peoples_threads_posts';
		protected $app_id = 'peoples';
		
		function get_author() {
			$user = new User_Object();
			$user->load($this->get('author'));
			return $user;
		}
	}
?>