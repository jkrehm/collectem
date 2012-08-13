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
 ?><!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title>Collect'em</title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
		button {
			width: 45%;
			padding: 5px;
		}
		form {
			text-align: center;
			display: block;
			width: 50%;
			background-color: #2B9BF1;
			border: 2px solid #15539A;
			-moz-box-shadow: 10px 10px 5px #888;
			-webkit-box-shadow: 10px 10px 5px #888;
			box-shadow: 10px 10px 5px #888;
			-moz-border-radius: 15px;
			border-radius: 15px;
			margin: 50px auto;
			padding: 10px;
		}
		form #search_term {
			display: block;
			font-size: 1.5em;
			width: 90%;
			border: 2px solid #585858;
			-moz-border-radius: 15px;
			border-radius: 15px;
			margin: 10px auto;
			padding: 5px 10px;
		}
		h1 {
			margin: 0;
		}
		input[type="submit"] {
			width: 45%;
			padding: 5px;
		}
		#container {
			background: none;
			border: none;
			-moz-box-shadow: none;
			-webkit-box-shadow: none;
			box-shadow: none;
		}
	</style>
</head>
<body id="index" onload="">
	<a href="index.php?config"><img id="config" src="assets/img/icons/gear.png"></a>

	<div id="container">
	<?php if (!isset($data['error'])): ?>

		<h1>Collect'em</h1>

		<form action="index.php" method="get" accept-charset="utf-8">
			<p>
				<!--
				<label for="search_type_title">Title</label>
				<input type="radio" name="type" value="Title" id="search_type_title" checked="checked">
			
				<label for="search_type_upc">UPC</label>
				<input type="radio" name="type" value="UPC" id="search_type_upc">
				-->

				<input type="hidden" name="type" value="Title" id="search_type_title">
			</p>
			<p>
				<label for="search_term" style="display:none">Movie Title</label>
				<input type="text" name="search" value="" id="search_term" placeholder="Movie Title">
			</p>
			<input type="submit" value="Search">&nbsp;
			<a href="index.php?library"><button type="button">View Library</button></a>
		</form>
		
		<?php if (isset($data['message'])): ?>
			<div id="msg"><?php echo $data['message'] ?></div>
		<?php endif ?>
		
	<?php else: ?>
	
		<div><?php echo $data['message'] ?></div>
			
	<?php endif ?>
	</div>

	<a href="http://jonathan.rehm.me" id="copyright">Created by Jonathan Rehm</a>
</body>	
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" charset="utf-8"></script>	
<script type="text/javascript" src="assets/js/collectem.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Hide the message box
		var t=setTimeout("hide_msg()",3500);
	});
</script>
</html>