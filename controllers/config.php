<?php
	
	class Config
	{
		private $_cfg;
		
		public function __construct()
		{
			// Get configuration
			if (!file_exists('config.xml'))
			{
				// handle error
			} else {
				$this->_cfg = simplexml_load_file('config.xml');
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