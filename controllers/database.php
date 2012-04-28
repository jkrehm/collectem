<?php
	class Database
	{
		private $_db;
		
		public function __construct()
		{
			if (!file_exists('config.xml'))
			{
				// handle error
			} else {
				// User must have SELECT, UPDATE, INSERT, and DELETE access. VIEW access may be required in the future.
				$config = simplexml_load_file('config.xml');
				$cfg = $config->database;
			}
			
			$this->_db = new mysqli($cfg->server, $cfg->username, $cfg->password, $cfg->database);
			
			if ($this->_db->connect_errno)
			{
				// handle error
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
		
		
		public function getLibrary($start, $count)
		{	
			$stmt = $this->_db->stmt_init();
			$stmt->prepare('SELECT * FROM collection ORDER BY search ASC LIMIT ?, ?');
			$stmt->bind_param('ii', $start, $count);
			$stmt->execute();
			$return['library'] = $this->_fetch($stmt);
			
			$res = $this->_db->query('SELECT COUNT(*) FROM collection');
			$total = $res->fetch_row();
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