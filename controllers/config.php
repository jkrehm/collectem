<?php
	
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