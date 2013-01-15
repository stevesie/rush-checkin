<?php
	function silk_icon($location, $title = "", $css_attr = "") {
		return '<img '.$css_attr.' class="silk_icon" src="'.Core::get_pref("URL_ROOT").'ext/silk_icons/icons/'.$location.'.png" title="'.$title.'" alt="'.$title.'" />';
	}
?>