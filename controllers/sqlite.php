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
	
	class Database
	{
		private $_db;
		
		public function __construct($testing=FALSE)
		{
			$this->_db = new SQLite3($_SESSION['url'].'/assets/db/collectem.db', SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE);
			
			if (!$this->_db)
			{
				// Handle error
			}
			else {
				// Check if the COLLECTION record is present. If not, create it.
				$results = $this->_db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='collection'");
				if (empty($results)) 
				{
					// Get the SQL statement from file
					$sql = file_get_contents('assets/sql/SQLite.sql');
					$results = $this->_db->exec($sql);

					// Output the result of the test, if the connection is being tested
					if ($testing) echo ($results) ? 'true' : 'false';

					if (!$results)
					{
						// Handle error
					}
				}
				elseif ($testing) {
					// Database exists and the COLLECTION record is present, so test was successful
					 echo 'true';
				}
			}
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
				$vals .= "'" . SQLite3::escapeString(stripslashes($value)) . "',";
				
				if ($key == 'title')
				{
					$found = FALSE;
					foreach ($arr_superfluous as $word)
					{
						if (substr($value, 0, strlen($word)) == $word)
						{
							$found = TRUE;
							$fields .= 'search,';
							$vals .= "'" . SQLite3::escapeString(stripslashes(substr($value, strlen($word)))) . "',";
							break;
						}
					}
					
					if (!$found)
					{
						$fields .= 'search,';
						$vals .= "'" . SQLite3::escapeString(stripslashes($value)) . "',";
					}
				}
			}
			$fields = substr($fields, 0, strlen($fields)-1);
			$vals = substr($vals, 0, strlen($vals)-1);
			
			return $this->_db->exec("INSERT OR REPLACE INTO collection ($fields) VALUES ($vals)");
		}
		
		
		public function removeMovie($data)
		{
			$ids = '';
			foreach ($data as $id)
			{
				$ids .= "'" . SQLite3::escapeString(stripslashes($id)) . "',";
			}
			$ids = substr($ids, 0, strlen($ids)-1);
			
			return $this->_db->exec("DELETE FROM collection WHERE id IN ($ids)");
		}
		
		
		public function getLibrary($start, $count, $search=FALSE)
		{
			if (!$search)
			{
				$stmt_srch = $this->_db->prepare('SELECT * FROM collection ORDER BY search ASC LIMIT ? OFFSET ?');
				$stmt_srch->bindValue(1, $count, SQLITE3_INTEGER);
				$stmt_srch->bindValue(2, $start, SQLITE3_INTEGER);
				
				$stmt_cnt = $this->_db->prepare('SELECT COUNT(*) FROM collection');
			}
			else
			{
				$search = '%' . SQLite3::escapeString(str_replace(' ','%',$search)) . '%';
				$stmt_srch = $this->_db->prepare('SELECT * FROM collection WHERE title LIKE ? ORDER BY search ASC LIMIT ? OFFSET ?');
				$stmt_srch->bindValue(1, $search, SQLITE3_TEXT);
				$stmt_srch->bindValue(2, $count, SQLITE3_INTEGER);
				$stmt_srch->bindValue(3, $start, SQLITE3_INTEGER);
				
				$stmt_cnt = $this->_db->prepare('SELECT COUNT(*) FROM collection WHERE title LIKE ?');
				$stmt_cnt->bindValue(1, $search, SQLITE3_TEXT);
			}
			
			// Loop through and get all of the results (fetchArray returns FALSE if nothing's returned)
			$results = $stmt_srch->execute();
			while (($row = $results->fetchArray(SQLITE3_ASSOC)) != FALSE)
			{
				$return['library'][] = $row;
			}
			
			$total = $stmt_cnt->execute();
			$total = $total->fetchArray();
			$return['total_results'] = $total[0];
			$return['total_pages'] = ceil($total[0]/$count);
			
			return $return;
		}
		
		
		public function getError()
		{
			return SQLite3::lastErrorMsg();
		}
	}
?>