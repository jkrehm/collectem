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
	if (!isset($_SESSION)) session_start();
	
	$_SESSION['url'] = str_replace('/index.php','',$_SERVER['SCRIPT_FILENAME']);
	$path = $_SESSION['url'];
	
	include_once('library/input.php');
	include_once('controllers/collectem.php');
	include_once('controllers/config.php');
	include_once('library/debug.php');



	function getWatchList($database, $img_url, $sizes)
	{
		// Get watch list data (if it exists)
		$watch_list = get_session('watch_list');

		if ($watch_list != FALSE)
		{
			$watch_list = $database->getLibrary(0, 5, FALSE, $watch_list);

			// If the selected movies aren't part of the library currently, then clear the watch list
			if (!isset($watch_list['library']))
			{
				$watch_list = FALSE;
				unset($_SESSION['watch_list']);
			}
			else {
				// Pare down the fields and build the images
				$temp = array();
				foreach ($watch_list['library'] as $key => $movie)
				{
					// Conditionally set the poster to empty, if no path is found
					if (empty($movie['poster_path']))
					{
						$poster_path = 'assets/img/empty_img.png';
					}
					else {
						$poster_path = $img_url.$sizes[0].$movie['poster_path'];
					}

					$temp[$key] = array(
						'id' => $movie['id'],
						'title' => $movie['title'],
						'poster_path' => $poster_path
					);
				}
				$watch_list = $temp; // Overwrite what was there
			}
		}

		return $watch_list;
	}

	
	
	if (isset($_GET['config']))
	{
		// Get configuration
		if (!file_exists('assets/json/config.json'))
		{
			$config = json_decode(file_get_contents('assets/json/default.json'));
		}
		else {
			$config = json_decode(file_get_contents('assets/json/config.json'));
		}

		include('views/config.php');
	}


	elseif (isset($_POST['config']))
	{
		unset($_POST['config']); // Don't put the flag into the config

		$config = json_encode($_POST);
		$return = file_put_contents('assets/json/config.json', $config);

		if (!$return)
		{
			$data['message'] = 'Configuration could not be saved.';
		} else {
			$data['message'] = 'Configuration saved.';
		}

		include('views/search_form.php');
	}



	// User searched for an item
	elseif (isset($_GET['search']))
	{	
		$collectem = new Collectem();
		$cfg = new Config();
		
		// Invalid search type
		if (!in_array(get_get('type'), array('Lib','Title','UPC')))
		{
			$data['error'] = TRUE;
			$data['message'] = 'Invalid search type. Please use the radio buttons.';
			
			// Display search results
			include('views/search_results.php');
		}


		// Searched for a movie on the internet
		elseif (in_array(get_get('type'), array('Title','UPC')))
		{
			$page = (!get_get('p') || !is_numeric(get_get('p'))) ? 1 : get_get('p'); // Get the page
		
			$data = $collectem->search(get_get('type'), get_get('search'), $page)->getData();
				
			$movies = $data['movie_info']; // Get just the movie info
			$sizes = $collectem->getImgSizes('poster');
			
			// Create pagination
			if (isset($movies['total_pages']))
			{
				$show_pagination = ($movies['total_pages'] > $cfg->getLibrary()->pagination);
			} else {
				$show_pagination = FALSE;
			}
			
			// Display search results
			include('views/search_results.php');
		}


		// Searched for a movie in the library
		else
		{
			$cfg = new Config();
		
			if ($cfg->dbType('M'))
			{
				include_once('controllers/database.php');
			} else {
				include_once('controllers/sqlite.php');
			}
			
			$database = new Database();
			
			// Show the library
			$from = (isset($_GET['p'])) ? $_GET['p'] * $cfg->getLibrary()->per_page - $cfg->getLibrary()->per_page : 0;
			
			$library = $database->getLibrary($from, $cfg->getLibrary()->per_page, get_get('search'));
			
			// Create pagination
			if (isset($library['total_results']))
			{
				$total_pages = ceil($library['total_results'] / $cfg->getLibrary()->per_page);
				$show_pagination = ($total_pages > $cfg->getLibrary()->pagination);
			} else {
				$show_pagination = FALSE;
			}
			
			$collectem = new Collectem();
			
			$img_url = $collectem->getImageURL();
			$sizes = $collectem->getImgSizes('poster');


			// Set the movie poster paths
			foreach ($library['library'] as $key => $movie)
			{
				$library['library'][$key]['poster_path'] = (!empty($movie['poster_path'])) ? $img_url . $sizes[2] . $movie['poster_path'] : 'assets/img/empty_img.png';
			}
			unset($movie);


			// Get watch list data
			$watch_list = getWatchList($database, $img_url, $sizes);

			include('views/library.php');
		}
	}
	

	
	// User selected an item
	elseif (isset($_POST['add']))
	{
		$cfg = new Config();
		
		if ($cfg->dbType('M'))
		{
			include_once('controllers/database.php');
		} else {
			include_once('controllers/sqlite.php');
		}
		
		$collectem = new Collectem();
		$img_url = $collectem->getImageURL();
		
		if (isset($_POST['selected']))
		{
			foreach ($_POST['selected'] as $movie)
			{
				$m = $collectem->movieInfo($movie);
							
				$insert_data = array(
					'id'=>$m['id'],
					'title'=>$m['title'],
					'tagline'=>$m['tagline'],
					'overview'=>$m['overview'],
					'poster_path'=>$m['poster_path'],
					'release_date'=>$m['release_date'],
					'imdb_id'=>$m['imdb_id']
				);
				
				$database = new Database();
				$return = $database->insertMovie($insert_data);
				
				if ($return)
				{
					$data['message'] = 'Movie(s) successfully added to collection';
				} else {
					$data['error'] = TRUE;
					$data['message'] = 'Error while inserting movie: ' . $database->getError();
				}
			}
		}
		
		include 'views/search_form.php';
	}
	

	
	// Display summary
	elseif (isset($_GET['summary']) && isset($_GET['id']))
	{
		$collectem = new Collectem();
		
		$movie = $collectem->movieInfo(get_get('id'));
		$img_url = $collectem->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		include('views/summary.php');
	}
	

	
	// Display details
	elseif (isset($_GET['library']) && isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$collectem = new Collectem();
		
		$movie = $collectem->movieInfo(get_get('id'));
		$movie['trailer'] = $collectem->movieTrailer(get_get('id'));
		$img_url = $collectem->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		$show_homepage = (strlen($movie['homepage']) > 0);
		
		include('views/detail.php');
	}
	

	
	// Display library
	elseif (isset($_GET['library']))
	{
		$cfg = new Config();
		
		if ($cfg->dbType('M'))
		{
			include_once('controllers/database.php');
		} else {
			include_once('controllers/sqlite.php');
		}
		
		$database = new Database();

		// User selected an item to remove
		if (isset($_POST['remove']) && isset($_POST['selected']))
		{
			$return = $database->removeMovie($_POST['selected']);

			if ($return)
			{
				$data['message'] = 'Movie(s) successfully removed from collection';
			} else {
				$data['error'] = TRUE;
				$data['message'] = 'Error while removing movie: ' . $database->getError();
			}
		}
		
		// Show the library
		$from = (isset($_GET['p'])) ? $_GET['p'] * $cfg->getLibrary()->per_page - $cfg->getLibrary()->per_page : 0;
		
		$library = $database->getLibrary($from, $cfg->getLibrary()->per_page);

		// Create pagination
		if (isset($library['total_results']))
		{
			$total_pages = ceil($library['total_results'] / $cfg->getLibrary()->per_page);
			$show_pagination = ($total_pages > $cfg->getLibrary()->pagination);
		} else {
			$show_pagination = FALSE;
		}


		// Get data from TMDB
		$collectem = new Collectem();
		
		$img_url = $collectem->getImageURL();
		$sizes = $collectem->getImgSizes('poster');

		// Set the movie poster paths
		foreach ($library['library'] as $key => $movie)
		{
			$library['library'][$key]['poster_path'] = (!empty($movie['poster_path'])) ? $img_url . $sizes[2] . $movie['poster_path'] : 'assets/img/empty_img.png';
		}
		unset($movie);


		// Get watch list data
		$watch_list = getWatchList($database, $img_url, $sizes);

		
		include('views/library.php');
	}



	// Test the database connection (called from configuration screen)
	elseif (isset($_POST['test_db']))
	{
		if (isset($_POST['database']['type']) && $_POST['database']['type'] == 'M')
		{
			include_once('controllers/database.php');
		} else {
			include_once('controllers/sqlite.php');
		}

		// This might be a goofy way of making the $_POST array into the JSON object I want, but it works
		$config = json_decode(json_encode($_POST));
		$database = new Database($testing=TRUE, $config);
	}



	// Add selected movie to watch options list
	elseif (isset($_POST['add_watchlist']))
	{
		$id = get_post('id');
		$data = array();

		if (!is_numeric($id))
		{
			$data['status'] = 'Invalid movie ID';
		}
		elseif (isset($_SESSION['watch_list']) && count($_SESSION['watch_list']) > 4) {
			$data['status'] = 'Maximum number of movies selected';
		}
		elseif (isset($_SESSION['watch_list']) && in_array($id, $_SESSION['watch_list'])) {
			$data['status'] = 'Movie already in list';
		}
		else {
			// Add the movie to the watch list
			$_SESSION['watch_list'][$id] = $id;

			// Get the movie info
			$collectem = new Collectem();
		
			$movie = $collectem->movieInfo($id);
			$img_url = $collectem->getImageURL();
			$sizes = $collectem->getImgSizes('poster');

			// Conditionally set the poster to empty, if no path is found
			if (empty($movie['poster_path']))
			{
				$poster_path = 'assets/img/empty_img.png';
			}
			else {
				$poster_path = $img_url.$sizes[0].$movie['poster_path'];
			}

			$data['status'] = 'Success';
			$data['movie'] = array(
				'id' => $movie['id'],
				'title' => $movie['title'],
				'poster_path' => $poster_path
			);
		}

		echo json_encode($data);
	}



	// Remove selected movie to watch options list
	elseif (isset($_POST['remove_watchlist']))
	{
		$id = get_post('id');

		if (!is_numeric($id))
		{
			echo 'Fail';
		}
		else {
			unset($_SESSION['watch_list'][$id]);
			echo 'Success';
		}
	}



	// Display search page
	else
	{
		// Remove session data
		unset($_SESSION['watch_list']);

		include('views/search_form.php');
	}
	
		
?>