<?php
	// Debug function to display passed variable
	function debug($item, $view='C')
	{
		if (isset($_GET['debug']))
		{
			if ($view == 'C')
			{
				include_once('ChromePhp.php');
				chromephp::log($item);
			} else {
				echo '<pre>'.print_r($item).'</pre>';
			}
		}
	}
?>