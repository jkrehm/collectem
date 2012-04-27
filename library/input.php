<?php
	// If a POST value is set, then returns the value. Otherwise, returns FALSE
	function get_post($str_field)
	{
		if (!isset($_POST[$str_field]))
		{
			return FALSE;
		} else {
			return $_POST[$str_field];
		}
	}
	
	
	// If a GET value is set, then returns the value. Otherwise, returns FALSE
	function get_get($str_field)
	{
		if (!isset($_GET[$str_field]))
		{
			return FALSE;
		} else {
			return $_GET[$str_field];
		}
	}
	
	
	// If a SESSION value is set, then returns the value. Otherwise, returns FALSE
	function get_session($str_field)
	{
		if (!isset($_SESSION[$str_field]))
		{
			return FALSE;
		} else {
			return $_SESSION[$str_field];
		}
	}
?>