<?php
	// Debug function to display passed variable
	function debug($item, $view='')
	{
		if (isset($_GET['debug']))
		{
			if ($view == 'C')
			{
				include_once('ChromePhp.php');
				chromephp::log($item);
			} elseif ($view == 'P') {
				echo '<pre>'.print_r($item).'</pre>';
			} else {
				include_once('ChromePhp.php');
				chromephp::log($item);
				echo '<pre>'.print_r($item).'</pre>';
			}
		}
	}
?>