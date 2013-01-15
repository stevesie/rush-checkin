<?php
	class Peoples_View extends Default_Layout {
		
		function home() {
			$this->top_layout();
			$this->add_css();
			
			$this->t('<h2>Welcome '.Core::user()->get('first_name').' '.Core::user()->get('last_name').'</h2>');
			
			$this->t('<br/><p>'.silk_icon('add').' To create a new thread, please send an email to <a href="mailto:threads@dkecornell.com">threads@dkecornell.com</a>; this will also email all '.$this->a('internal site users','peoples/users').'.</p>');
			
			$this->t('<br/><p><strong>Recent Threads:</strong></p><br/>');
			
			$this->t('<ul>');
			
			$threads = $this->c->model()->get_threads();
			foreach($threads as $thread) {
				$messages = $thread->get_messages();
				$this->t('<li>'.$this->a(htmlentities($thread->get('title')),'peoples/thread/'.$thread->id()).' - '.sizeof($messages).' Message(s)</li>');
			}
			
			$this->t('</ul><br/>');
			
			$this->bottom_layout();
			$this->output();
		}
		
		function show_thread($id) {
			$this->top_layout();
			$this->add_css();
			
			$thread = new Peoples_Thread_Object();
			$thread->load($id);
			$messages = $thread->get_messages();
			
			foreach($messages as $message) {
				$author = $message->get_author();
				$this->t('<h2>'.$author->get('first_name').' '.$author->get('last_name').' says on '.$message->get('created').'...</h2>');
				$this->t('<br/>');
				$this->t('<p>'.str_replace("\n",'<br/>',$message->get('content')).'</p><br/>');
			}
			
			$this->bottom_layout();
			$this->output();
		}
		
		function users() {
			$this->top_layout();
			$this->add_css();
			
			$this->t('<h2>Website Users</h2><br/>');
			
			$this->t('<ul>');
			
			$users = $this->c->model()->get_users();
			foreach($users as $user) {
				$this->t('<li>'.$user->get('first_name').' '.$user->get('last_name').': '.$user->get('email').'</li>');
			}
			
			$this->t('</ul><br/>');
			
			$this->bottom_layout();
			$this->output();
		}
		
	}
?>