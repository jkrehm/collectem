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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title>Movie Summary</title>
	<style type="text/css" media="screen">
		.poster {
			text-align: center;
			display: block;
			margin: 0 auto 10px auto;
		}
	</style>
</head>
<body id="movie_summary" onload="">
	<div>
		<img class="poster" src="<?php echo $img_url . $sizes[2] . $movie['poster_path'] ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>"/>
		<p class="poster"><?php echo $movie['title'] ?></p>
		<p class="poster"><?php echo date('m/d/Y',strtotime($movie['release_date'])) ?></p>
		<p><?php echo $movie['overview'] ?></p>
	</div>
</body>
</html>
