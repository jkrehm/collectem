<?php
	if (!isset($_SESSION)) session_start();
	include_once($_SESSION['url'].'/library/debug.php');
	
	class Database
	{
		private $_db;
		
		public function __construct()
		{
			$this->_db = new SQLite3($_SESSION['url'].'/assets/db/collectem.db', SQLITE3_OPEN_READWRITE);
			
			if (!$this->_db)
			{
				// Handle error
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
				$stmt_srch->bindValue(1, $search, SQLITE3_STRING);
				$stmt_srch->bindValue(2, $count, SQLITE3_INTEGER);
				$stmt_srch->bindValue(3, $start, SQLITE3_INTEGER);
				
				$stmt_cnt = $this->_db->prepare('SELECT COUNT(*) FROM collection WHERE title LIKE ?');
				$stmt_cnt->bindValue(1, $search, SQLITE3_STRING);
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
			return mysql_error();
		}
	}
?>