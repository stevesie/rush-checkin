<?php
	class Mail_Lib {
		private $subject;
		private $message;
		private $headers;
	
		private $sender;
		private $public_receivers;
		private $private_receivers;
		
		function set_subject($subject) {
			$this->subject = stripslashes($subject);
		}
		
		function set_message($message) {
			$this->message = $message;
		}
		
		function set_html() {
			$this->headers  = 'MIME-Version: 1.0'."\r\n";
			$this->headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		}
		
		function set_plain() {
			$this->headers = '';
		}
		
		function set_full_replyto($str) {
			$this->headers .= 'Reply-To: '.$str."\r\n";
		}
		
		function set_full_sender($str) {
			$this->sender = $str;
		}
		
		function set_sender($name, $email) {
			$this->sender = "$name <$email>";
		}
		
		function add_public_receiver($name, $email) {
			$this->public_receivers .= "$name <$email>, ";
		}
		
		function add_private_receiver($name, $email) {
			$this->private_receivers .= "$name <$email>, ";
		}
		
		function send() {
			//trim the last comma from both sets of receivers
			$public_recievers = $this->public_receivers;
			$private_recievers = $this->private_receivers;
			
			$public_recievers = substr($public_recievers, 0, strlen($public_recievers) - 2);
			$private_recievers = substr($private_recievers, 0, strlen($private_recievers) - 2);
			
			$headers = $this->headers;
			$headers .= 'From: '.$this->sender."\r\n";
			$headers .= 'Bcc: '.$this->private_receivers;
			
			if($this->public_receivers == '')
				$this->public_receivers = $this->sender;
				
			return mail($this->public_receivers, $this->subject, $this->message, $headers);
		}
	}
?>