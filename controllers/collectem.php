<?php

	class Collectem
	{
		private $_path;
		private $_data;
		private $_parms;
		private $_tmdb;
		private $_search_val;
		private $_search_page;
		const upc_key = 'bbef0d39300f88da751a1b799b25b14daa6e7fcb';
		const tmdb_key = '9e6d6fd0c929169a985e709141dab305';
		
		function __construct()
		{
			if (!isset($_SESSION)) session_start();
			
			$this->path = $_SESSION['url'];
			
			require_once("$this->path/library/tmdb_v3.php");
			include_once("$this->path/library/debug.php");
	
			$this->_tmdb = new TMDBv3(self::tmdb_key);
		}
		
		
		public function search($type='title', $search_val='Clueless', $page=1)
		{
			$this->_search_val = $search_val;
			$this->_search_page = $page;
			
			eval('$dummy = $this->search'.$type.'("'.$search_val.'");');
			$this->_data['movie_info']['img_url'] = $this->_tmdb->getImageURL();
			$this->_data['movie_info']['img_sizes'] = $this->_tmdb->getImageSizes();
			
			return $this;
		}
		
		
		private function searchUPC()
		{
			require_once('XML/RPC.php');
			
			$client = new XML_RPC_Client('/xmlrpc', 'http://www.upcdatabase.com');
			
			$params = array( new XML_RPC_Value( array(
				'rpc_key' => new XML_RPC_Value(self::upc_key, 'string'),
				'upc' => new XML_RPC_Value($this->_search_val, 'string'),
				), 'struct'));
			
			// Construct the XML-RPC request.  Substitute your chosen method name
			$msg = new XML_RPC_Message('lookup', $params);
			
			//Actually have the client send the message to the server.  Save response.
			$resp = $client->send($msg);
			
			//If there was a problem sending the message, the resp will be false
			if (!$resp)
			{
				$this->_data['error'] = TRUE;
				$this->_data['message'] = 'Communication error: ' . $client->errstr;
			}
			else 
			{
				if ($resp->faultCode() != FALSE)
				{
					$this->_data['error'] = TRUE;
					$this->_data['message'] = 'Fault Code: ' . $resp->faultCode() . "\n" . 'Fault Reason: ' . $resp->faultString() . "\n";
				}
				else
				{
					$resp_val = $resp->value();
					$this->_data['upc_info'] = XML_RPC_decode($resp_val); // Decode the value, into an array.
				}
				
				if ($this->_data['upc_info']['status'] == 'fail')
				{
					$this->_data['error'] = TRUE;
					$this->_data['message'] = $this->_data['upc_info']['message'];
				}
				else
				{
					// Search only the first three words
					// $descr = explode(' ', $this->_data->upc_info->description);
					// array_splice($descr, 3);
					// $descr = implode(' ', $descr);
					
					// Clear out some of the nonsense that makes TMDb return no results
					$arr_search = array(
						'/ \(.*?\).*/',
						'/ Ultimate.*/',
						'/ Extended.*/',
						'/ 2-Disc.*/'
					);
					$this->_search_val = preg_replace($arr_search, '', $this->_data['upc_info']['description']);
					
					$this->_data['movie_info'] = $this->_tmdb->searchMovie($this->_search_val, $this->_search_page);
				}
			}
			
			return $this;
		}
		
		
		private function searchTitle()
		{
			$this->_data['movie_info'] = $this->_tmdb->searchMovie($this->_search_val, $this->_search_page);
			
			return $this;
		}
		
		
		public function getData()
		{
			return $this->_data;
		}
		
		
		public function getPage($search_page)
		{
			$this->_search_page = $search_page;
			$this->_data['movie_info'] = $this->_tmdb->searchMovie($this->_search_val, $this->_search_page);
			
			return $this;
		}
		
		
		// public function getNextPage()
		// {
		// 	$this->_search_page++;
		// 	$this->_data['movie_info'] = $this->_tmdb->searchMovie($this->_search_val, $this->_search_page);
		// 	
		// 	return $this;
		// }
		// 
		// 
		// public function getPreviousPage()
		// {
		// 	$this->_search_page--;
		// 	$this->_data['movie_info'] = $this->_tmdb->searchMovie($this->_search_val, $this->_search_page);
		// 	
		// 	return $this;
		// }
		
		
		public function getPageNumber()
		{
			return $this->_search_page;
		}
		
		
		public function getImgSizes($type)
		{
			$sizes = $this->_tmdb->getImageSizes();
			return $sizes[$type.'_sizes'];
		}
		
		
		public function getSearchVal()
		{
			return $this->_search_val;
		}
		
		
		public function getTMDB()
		{
			return $this->_tmdb;
		}
	}



?>