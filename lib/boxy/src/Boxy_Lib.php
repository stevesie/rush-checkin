<?php
	class Boxy_Lib {
		
		/**
		 * @stevesie.ext = boxy
		 */
		
		function __construct($view) {
			$view->add_file_css('ext/boxy/src/stylesheets/boxy.css');
			$view->add_url_js('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',true);
			$view->add_file_js('ext/boxy/src/javascripts/jquery.boxy.js',true);
		}
	}
?>