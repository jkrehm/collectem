<?php
	if (!isset($_SESSION)) session_start();
	
	$_SESSION['url'] = str_replace('/index.php','',$_SERVER['SCRIPT_FILENAME']);
	$path = $_SESSION['url'];
	
	include_once('library/input.php');
	include_once('controllers/collectem.php');
	include_once('library/debug.php');
	
	
	// User searched for an item
	if (isset($_GET['search']))
	{	
		$collectem = new Collectem();
		
		// Get configuration
		if (!file_exists('config.xml'))
		{
			// handle error
		} else {
			$config = simplexml_load_file('config.xml');
			$cfg = $config->library;
		}
		
		if (!in_array(get_get('type'), array('Lib','Title','UPC')))
		{
			$data['error'] = TRUE;
			$data['message'] = 'Invalid search type. Please use the radio buttons.';
			
			// Display search results
			include('views/search_results.php');
		}
		elseif (in_array(get_get('type'), array('Title','UPC')))
		{
			$page = (!get_get('p') || !is_numeric(get_get('p'))) ? 1 : get_get('p'); // Get the page
		
			$data = $collectem->search(get_get('type'), get_get('search'), $page)->getData();
				
			$movies = $data['movie_info']; // Get just the movie info
			$sizes = $collectem->getImgSizes('poster');
			
			// Create pagination
			if (isset($movies['total_pages']))
			{
				$show_pagination = ($movies['total_pages'] > $cfg->pagination);
			} else {
				$show_pagination = FALSE;
			}
			
			// Display search results
			include('views/search_results.php');
		}
		else
		{
			include_once('controllers/database.php');
			
			$database = new Database();
			
			// Show the library
			$from = (isset($_GET['p'])) ? $_GET['p'] * $cfg->per_page - $cfg->per_page : 0;
			
			$library = $database->getLibrary($from, $cfg->per_page, get_get('search'));
			
			// Create pagination
			if (isset($library['total_results']))
			{
				$total_pages = ceil($library['total_results'] / $cfg->per_page);
				$show_pagination = ($total_pages > $cfg->pagination);
			} else {
				$show_pagination = FALSE;
			}
			
			$collectem = new Collectem();
			
			$img_url = $collectem->getImageURL();
			$sizes = $collectem->getImgSizes('poster');
			
			include('views/library.php');
		}
	}
	
	
	// User selected an item
	elseif (isset($_POST['add']))
	{
		include('controllers/database.php');
		
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
		include_once('controllers/database.php');
		
		// Get configuration
		if (!file_exists('config.xml'))
		{
			// handle error
		} else {
			$config = simplexml_load_file('config.xml');
			$cfg = $config->library;
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
		$from = (isset($_GET['p'])) ? $_GET['p'] * $cfg->per_page - $cfg->per_page : 0;
		
		$library = $database->getLibrary($from, $cfg->per_page);
		
		// Create pagination
		if (isset($library['total_results']))
		{
			$total_pages = ceil($library['total_results'] / $cfg->per_page);
			$show_pagination = ($total_pages > $cfg->pagination);
		} else {
			$show_pagination = FALSE;
		}
		
		$collectem = new Collectem();
		
		$img_url = $collectem->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		include('views/library.php');
	}
	
	
	// Display search page
	else
	{
		include('views/search_form.php');
	}
	
		
?>