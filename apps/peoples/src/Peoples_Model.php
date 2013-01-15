<?php
	class Peoples_Model extends Core_Model {
		
		function handle_email($email) {
			
			// handle email
			$lines = explode("\n", $email);
			
			// empty vars
			$from = "";
			$subject = "";
			$headers = "";
			$message = "";
			$splittingheaders = true;
			
			
			for ($i=0; $i < count($lines); $i++) {
			    if ($splittingheaders) {
			        // this is a header
			        $headers .= $lines[$i]."\n";
			
			        // look out for special headers
			        if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
			            $subject = $matches[1];
			        }
			        if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
			            $from = $matches[1];
			        }
			    } else {
			        // not a header, but message
			        $message .= $lines[$i]."\n";
			    }
			
			    if (trim($lines[$i])=="") {
			        // empty line, header section has ended
			        $splittingheaders = false;
			    }
			}
			
			
			/*
			Core::include_ext('email_parser/MimeMailParser.class.php');
			$parser = new MimeMailParser();
			$parser->setText($email);
			
			$to = $Parser->getHeader('to');
			$from = $Parser->getHeader('from');
			$subject = $Parser->getHeader('subject');
			$text = $Parser->getMessageBody('text');
			$html = $Parser->getMessageBody('html');
			$attachments = $Parser->getAttachments();
		
			//$o = 'Got email: '.$email;
			*/
			
			//parse the from email
			$sender = explode(" ",$from);
			$sender = $sender[sizeof($sender)-1];
			$sender = substr($sender,1,strlen($sender)-2);
			
			//make sure this person is AUTHORIZED!!
			$user = new User_Object();
			if(!$user->fetch_by_field('email',$sender)) {
				$mail = new Mail_Lib();
				$mail->set_full_sender('noreply@dkecornell.com');
				$mail->add_public_receiver($sender,$sender);
				$mail->set_subject($subject);
				$mail->set_message('You are not authorized to send a message.');
				$mail->send();
				return;
			}else
				$user_id = $user->id();
			
			
			$mail = new Mail_Lib();
			$mail->set_full_sender($from);
			
			$users = $this->get_users();
			foreach($users as $user) {
				$mail->add_public_receiver($user->get('first_name').' '.$user->get('last_name'),$user->get('email'));
			}
			
			$mail->set_subject($subject);
			$mail->set_message($message);
			$mail->set_full_replyto('threads@dkecornell.com');
			
			$mail->send();
			
			//now save in the database
			$thread = new Peoples_Thread_Object();
			
			if(stripos($subject,'re: ') === 0) { //REPLY
				$subject = substr($subject, 4);
				if(!$thread->fetch_by_field('title',$subject)){
					$thread->set('title',$subject);
					$thread->make();
					$thread->fetch_by_field('title',$subject);
				}
			}else{
				$thread->set('title',$subject);
				$thread->make();
				$thread->fetch_by_field('title',$subject);
			}
			
			$thread_id = $thread->id();
			
			$post = new Peoples_Thread_Post_Object();
			$post->set('thread_id',$thread_id);
			$post->set('author',$user_id);
			$post->set('created','NOW()');
			$post->set('content',$message);
			$post->make();
			
			
			//lookup the 
		
			//mail('stevesie@stevesie.com','DKE Email!',$o);
		}
		
		function get_threads() {
			$users = new Core_Collection('peoples_threads','Peoples_Thread_Object');
			$users->build();
			return $users->get_objects();
		}
		
		function get_users() {
			$users = new Core_Collection('users','User_Object');
			$users->build();
			return $users->get_objects();
		}
		
	}
?>