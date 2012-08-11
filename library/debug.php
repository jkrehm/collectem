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
	// Debug function to display passed variable
	function debug($item, $view='')
	{
		if (isset($_GET['debug']) || isset($_POST['debug']))
		{
			if ($view == 'C')
			{
				include_once('ChromePhp.php');
				chromephp::log($item);
			} elseif ($view == 'P') {
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			} else {
				include_once('ChromePhp.php');
				chromephp::log($item);
				echo '<pre>';
				print_r($item);
				echo '</pre>';
			}
		}
	}
?>