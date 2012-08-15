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
	<title>Configuration</title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
		div {
			padding: 3px 0;
		}
		fieldset {
			margin: 10px 0;
		}
		label {
			width: 150px;
			float: left;
		}
		#container {
			width: 50%;
		}
		#msg {
			width: 20%;
			top: 15px;
			right: 7px;
			left: auto;
			padding: 5px 5px;
		}
	</style>
</head>
<body id="configuration" onload="">

	<div id="container">
		<form action="index.php" method="post">
			<fieldset>
				<legend>API Key(s)</legend>

				<div>
					<label for="apis[tmdb]" title="API key used to search themoviedb.org's database.">The Movie Database (TMDB)</label>
					<input type="text" id="apis[tmdb]" name="apis[tmdb]" value="<?php echo $config->apis->tmdb ?>" title="API key used to search themoviedb.org's database.">
					<a href="http://help.themoviedb.org/kb/general/how-do-i-register-for-an-api-key" style="font-size:small; margin-left:10px" title="Get information on how to obtain an API key from themoviedb.org." target="_blank">
						Register
						<img src="assets/img/icons/new_window.png" alt="Opens in new window" title="Opens in new window">
					</a>

					<input type="hidden" id="apis[upc]" name="apis[upc]" value="<?php echo $config->apis->upc ?>">
				</div>
			</fieldset>

			<fieldset style="position:relative">
				<legend>Database</legend>

				<div>
					<label for="database[type]">Database Type</label>
					<select id="database[type]" name="database[type]">
						<option value="M" <?php echo ($config->database->type == 'M') ? 'selected="selected"' : '' ?>>MySQL</option>
						<option value="S" <?php echo ($config->database->type == 'S') ? 'selected="selected"' : '' ?>>SQLite</option>
					</select>
				</div>
				<div>
					<label for="database[server]">Server address</label>
					<input type="text" id="database[server]" name="database[server]" value="<?php echo $config->database->server ?>">
				</div>
				<div>
					<label for="database[port]">Server port</label>
					<input type="tel" id="database[port]" name="database[port]" value="<?php echo $config->database->port ?>">
				</div>
				<div>
					<label for="database[username]">Username</label>
					<input type="text" id="database[username]" name="database[username]" value="<?php echo $config->database->username ?>">
				</div>
				<div>
					<label for="database[password]">Password</label>
					<input type="password" id="database[password]" name="database[password]" value="<?php echo $config->database->password ?>">
				</div>
				<div>
					<label for="database[database]">Database name</label>
					<input type="text" id="database[database]" name="database[database]" value="<?php echo $config->database->database ?>">
				</div>

				<div style="padding:10px 0 0 150px">
					<button id="db_test" title="Test the database connection parameters.">Test</button>
					<img id="loader" src="assets/img/icons/ajax-loader.gif" alt="Loader" style="display:none; margin-left:10px">
				</div>

				<div id="msg" style="display:none"></div>
			</fieldset>

			<fieldset>
				<legend>Library</legend>

				<div>
					<label for="library[per_page]" title="The number of movies that will show per page in the library.">Movies per Page</label>
					<input type="text" id="library[per_page]" name="library[per_page]" value="<?php echo $config->library->per_page ?>"  title="The number of movies that will show per page in the library.">
				</div>
				<div>
					<label for="library[pagination]" title="The minimum number of pages that must exist before pagination begins to show.">Minimum pages before paginating</label>
					<input type="text" id="library[pagination]" name="library[pagination]" value="<?php echo $config->library->pagination ?>" title="The minimum number of pages that must exist before pagination begins to show.">
				</div>
			</fieldset>

			<div style="text-align:center">
				<input type="submit" value="Save">
				<a href="index.php"><button id="cancel">Cancel</button></a>
			</div>

			<input type="hidden" id="config" name="config">
		</form>
	</div>

	<div class="preload">
		<img src="assets/img/icons/ajax-loader.gif">
	</div>
</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/collectem.js" charset="utf-8"></script>
<script type="text/javascript">
	// Returns if MySQL is selected as the database type
	function MySQL() {
		return ($('#database\\[type\\] option:selected').val() == 'M');
	}

	$(document).ready(function() {
		// Hide the MySQL configuration if using SQLite
		$('[id^="database"][id!="database\\[type\\]"]').parent().toggle(MySQL());

		$('#database\\[type\\]').change(function() {
			$('[id^="database"][id!="database\\[type]"]').parent().slideToggle(MySQL());
		});


		// Test database connection
		$('#db_test').click(function(e) {
			e.preventDefault();

			// Temporarily change hidden field so test is run instead of save
			$('#config').attr('name','test_db');
			var data = $('form').serializeArray();
			$('#config').attr('name','config');

			// Send the test request
			$.ajax({
				type: 'POST',
				url: 'index.php',
				data: data,
				dataType: 'html',
				beforeSend: function() {
					$('#loader').fadeIn();
				},
				success: function(data) {					
					if (data == 'true') {
						$('#msg').text('Success').css('background-color','#C3F895');
					}
					else {
						$('#msg').text('Failed').css('background-color','#FF3D3D');
					}
					$('#msg').fadeIn(function() {
						$('#loader').fadeOut();
					});
					var t=setTimeout("hide_msg()",3500);
				}
			});
		});

		// Since buttons in forms will often submit them, prevent the default behavior and redirect to the intended URL
		$('#cancel').click(function(e) {
			e.preventDefault();
			window.location = $(this).parent().attr('href');
		});
	});
</script>
</html>