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
		
		$page = (!get_get('page') || !is_numeric(get_get('page'))) ? 1 : get_get('page'); // Get the page
		
		$data = $collectem->search(get_get('type'), get_get('search'), $page)->getData();
		
		$movies = $data['movie_info']; // Get just the movie info
		$sizes = $collectem->getImgSizes('poster');
		debug($movies,'P');
		// Display search results
		include('views/search_results.php');
	}
	
	
	// User selected an item
	elseif (isset($_POST['add']))
	{
		include('controllers/database.php');
		
		debug($_POST['selected'],'P');
		
		$collectem = new Collectem();
		$tmdb = $collectem->getTMDB();
		$img_url = $tmdb->getImageURL();

		foreach ($_POST['selected'] as $movie)
		{
			$m = $tmdb->movieInfo($movie);
						
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
		
		include 'views/search_form.php';
	}
	
	
	// Display summary
	elseif (isset($_GET['summary']) && isset($_GET['id']))
	{
		$collectem = new Collectem();
		$tmdb = $collectem->getTMDB();

		$movie = $tmdb->movieInfo(get_get('id'));
		$img_url = $tmdb->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		include('views/summary.php');
	}
	
	
	// Display details
	elseif (isset($_GET['library']) && isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$collectem = new Collectem();
		$tmdb = $collectem->getTMDB();

		$movie = $tmdb->movieInfo(get_get('id'));
		$movie['trailer'] = $tmdb->movieTrailer(get_get('id'));
		debug($movie['trailer'],'C');
		$img_url = $tmdb->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		$show_homepage = (strlen($movie['homepage']) > 0);
		
		include('views/detail.php');
	}
	
	
	// Display library
	elseif (isset($_GET['library']))
	{
		include_once('controllers/database.php');

		$database = new Database();

		// User selected an item to remove
		if (isset($_POST['remove']))
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
		$from = (isset($_GET['p'])) ? $_GET['p']*20-20 : 0;
		
		$library = $database->getLibrary($from, 20);
		
		$collectem = new Collectem();
		$tmdb = $collectem->getTMDB();
		
		$img_url = $tmdb->getImageURL();
		$sizes = $collectem->getImgSizes('poster');
		
		include('views/library.php');
	}
	
	
	// Display search page
	else
	{
		include('views/search_form.php');
	}
	
		
?>