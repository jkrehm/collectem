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

	if (!isset($_SESSION)) exit('No direct script access is permitted.');
 ?>
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title><?php echo $movie['title'] ?></title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/colorbox.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
		.details {
			width: 50%;
		}
		.details ul {
			list-style-type: none;
			margin-left: 0;
		}
		.details ul li {
			padding: 5px 0;
		}
		.poster {
			float: right;
			display: inline-block;
		}
		#container {
			width: 70%;
		}
	</style>
</head>
<body id="detail" onload="">
	
	<div id="container">
		
		<a href="index.php?library<?php echo (isset($_GET['p'])) ? '&p='.$_GET['p'] : '' ?>">Back</a>
		
	<?php if (!isset($data['error'])): ?>
		
		<img class="poster" src="<?php echo (isset($movie['poster_path'])) ? $img_url . $sizes[3] . $movie['poster_path'] : 'assets/img/empty_img.png' ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>"/>
		<div class="details">
			<ul>
				<li style="font-size:1.5em">
					<?php echo (($show_homepage) ? '<a href="'.$movie['homepage'].'">' : '') . $movie['title'] . (($show_homepage) ? '</a>' : '') . ' ' ?>
					<a href="http://www.imdb.com/title/<?php echo $movie['imdb_id'] ?>/" style="font-size:0.5em">(imdb)</a>
				</li>
				<li style="font-size:0.8em; font-style:italic; padding-top:0"><?php echo $movie['tagline'] ?></li>
				<li>Release Date: <?php echo date('m/d/Y', strtotime($movie['release_date'])) ?></li>
				<li>Rating: <?php echo $movie['vote_average'] ?> (<?php echo $movie['vote_count'] ?> votes)</li>
				<li>Runtime: <?php echo $movie['runtime'] ?> minutes</li>
				<li>
					<?php echo $movie['overview'] ?>
					<?php if (isset($movie['trailer']['youtube']) && !empty($movie['trailer']['youtube'])): ?>
						<a href="http://www.youtube.com/watch?v=<?php echo $movie['trailer']['youtube'][0]['source'] ?>" style="font-size:0.75em"> (trailer)</a>
					<?php endif ?>
				</li>
			</ul>
		</div>
		<div style="clear:both"></div>
		
		<?php if (isset($data['message'])): ?>
			<div id="msg"><?php echo $data['message'] ?></div>
		<?php endif ?>
		
	<?php else: ?>
		
		<div id="msg"><?php echo $data['message'] ?></div>
		
	<?php endif ?>
	
	</div>
</body>
</html>
