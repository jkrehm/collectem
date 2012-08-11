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
	class Database
	{
		private $_db;
		
		public function __construct($testing=FALSE, $config=NULL)
		{
			// User must have SELECT, UPDATE, INSERT, and DELETE access. VIEW access may be required in the future.
			if (!$testing)
			{
				if (!file_exists('assets/json/config.json'))
				{
					header('Location: index.php?config');
				}
				else {
					$config = json_decode(file_get_contents('assets/json/config.json'));
				}
			}
			else {
				// Don't show errors if the connection is being tested
				error_reporting(0);
			}

			$cfg = $config->database;
			
			$this->_db = new mysqli($cfg->server, $cfg->username, $cfg->password, $cfg->database, $cfg->port);
			
			if ($this->_db->connect_errno)
			{
				// handle error
				if ($testing) {
					echo 'false';
				}
				else {
					$cfg->password = '***';
					die('<br>Failed to connect using the following configuration: <pre>'.print_r($cfg, TRUE).'</pre>');
				}
			}
			elseif ($testing) {
				echo 'true';
			}
		}
		
				
		private function _fetch($result)
		{
			$array = array();
			
			if($result instanceof mysqli_stmt)
			{
				$result->store_result();
				
				$variables = array();
				$data = array();
				$meta = $result->result_metadata();
				
				while($field = $meta->fetch_field())
					$variables[] = &$data[$field->name]; // pass by reference
				
				call_user_func_array(array($result, 'bind_result'), $variables);
				
				$i=0;
				while($result->fetch())
				{
					$array[$i] = array();
					foreach($data as $k=>$v)
						$array[$i][$k] = $v;
					$i++;
				}
			}
			elseif($result instanceof mysqli_result)
			{
				while($row = $result->fetch_assoc())
					$array[] = $row;
			}
			
			return $array;
		}
		
		
		public function insertMovie($data)
		{
			$fields = '';
			$vals = '';
			$arr_superfluous = array(
				'A ',
				'An ',
				'The '
			);
			
			foreach ($data as $key => $value)
			{
				$fields .= $key . ',';
				$vals .= "'" . mysql_real_escape_string(stripslashes($value)) . "',";
				
				if ($key == 'title')
				{
					$found = FALSE;
					foreach ($arr_superfluous as $word)
					{
						if (substr($value, 0, strlen($word)) == $word)
						{
							$found = TRUE;
							$fields .= 'search,';
							$vals .= "'" . mysql_real_escape_string(stripslashes(substr($value, strlen($word)))) . "',";
							break;
						}
					}
					
					if (!$found)
					{
						$fields .= 'search,';
						$vals .= "'" . mysql_real_escape_string(stripslashes($value)) . "',";
					}
				}
			}
			$fields = substr($fields, 0, strlen($fields)-1);
			$vals = substr($vals, 0, strlen($vals)-1);
			
			return $this->_db->query("REPLACE INTO collection ($fields) VALUES ($vals)");
		}
		
		
		public function removeMovie($data)
		{
			$ids = '';
			foreach ($data as $id)
			{
				$ids .= "'" . mysql_real_escape_string(stripslashes($id)) . "',";
			}
			$ids = substr($ids, 0, strlen($ids)-1);
			
			return $this->_db->query("DELETE FROM collection WHERE id IN ($ids)");
		}
		
		
		public function getLibrary($start, $count, $search=FALSE)
		{
			$stmt_srch = $this->_db->stmt_init();
			$stmt_cnt = $this->_db->stmt_init();
			
			if (!$search)
			{
				$stmt_srch->prepare('SELECT * FROM collection ORDER BY search ASC LIMIT ?, ?');
				$stmt_srch->bind_param('ii', $start, $count);
				
				$stmt_cnt->prepare('SELECT COUNT(*) FROM collection');
			}
			else
			{
				$search = '%' . mysql_real_escape_string(str_replace(' ','%',$search)) . '%';
				$stmt_srch->prepare('SELECT * FROM collection WHERE title LIKE ? ORDER BY search ASC LIMIT ?, ?');
				$stmt_srch->bind_param('sii', $search, $start, $count);
				
				$stmt_cnt->prepare('SELECT COUNT(*) FROM collection WHERE title LIKE ?');
				$stmt_cnt->bind_param('s', $search);
			}
			
			// Loop through and get all returned rows
			$stmt_srch->execute();
			$return['library'] = $this->_fetch($stmt_srch);
			
			$stmt_cnt->execute();
			$stmt_cnt->bind_result($total);
			$stmt_cnt->fetch();
			$return['total_results'] = $total;
			$return['total_pages'] = ceil($total/$count);
			
			return $return;
		}
		
		
		public function getError()
		{
			return mysql_error();
		}
	}
?>