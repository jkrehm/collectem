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
	if (!isset($_SESSION)) exit('No direct script access is permitted.');

	class Config
	{
		private $_cfg;
		
		public function __construct()
		{			
			// Get configuration
			if (!file_exists('assets/json/config.json'))
			{
				header('Location: index.php?config');
			}
			else {
				$this->_cfg = json_decode(file_get_contents('assets/json/config.json'));
			}
		}
		
		public function getLibrary()
		{
			return $this->_cfg->library;
		}
		
		public function getDatabase()
		{
			return $this->_cfg->database;
		}
		
		public function dbType($type)
		{
			return ($this->_cfg->database->type == $type);
		}
	}
?>