<?php
/**
* @author Jonthan Rehm <jkrehm@gmail.com>
* @copyright 2012 Jonathan Rehm
* @link http://jonathan.rehm.me
* @license http://www.gnu.org/licenses/gpl.html
*
* This file is part of Collect'em.
*
* Collect'em is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Collect'em is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Collect'em.  If not, see <http://www.gnu.org/licenses/>.
*/
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