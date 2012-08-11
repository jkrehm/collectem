<?php
	if (!isset($_SESSION)) session_start();
	
	$_SESSION['url'] = str_replace('/index.php','',$_SERVER['SCRIPT_FILENAME']);
	$path = $_SESSION['url'];
	
	include_once('library/input.php');
	include_once('controllers/collectem.php');
	include_once('controllers/config.php');
	include_once('library/debug.php');
	
	
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
		
		$collectem = new Collectem();
		
		$img_url = $collectem->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
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
	
	
	// Display search page
	else
	{
		include('views/search_form.php');
	}
	
		
?>