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
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<title>Library (<?php echo $library['total_results'] ?>)</title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/colorbox.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
		a {
			display: inline-block;
		}
		ul li div p {
			color: white;
		}
		.checked {
			position: absolute;
			z-index: 2;
			left: 10px;
			top: 10px;
			height: 280px;
			width: 180px;
			opacity:0.5;
			filter:alpha(opacity=50);
		}
		.library {
			margin: 0;
			padding: 0;
		}
		.library li {
			position: relative;
			float: left;
			height: 330px;
			list-style: none;
			margin: 10px;
		}
		.library div {
			position: relative;
			width: 200px;
			margin: 0 auto;
		}
		.library img.poster {
			height: 300px;
			width: 200px;
		}
		.library a.details img {
			position: absolute;
			z-index: 3;
			height: 25px;
			width: 25px;
			bottom: 40px;
			right: 10px;
		}
		.library .add {
			position: absolute;
			z-index: 3;
			top: 10px;
			left: 10px;
		}
		.library .remove {
			position: absolute;
			z-index: 3;
			top: 10px;
			right: 10px;
		}
		.library p {
			font-size: 12px;
			margin: 0.5em 0;
			text-align: center;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		#library-search {
			float: right;
		}
		#library-search input {
			border: 1px solid #15539A;
			-moz-border-radius: 15px;
			border-radius: 15px;
			padding: 5px;
		}
		#msg {
			top: 25px;
		}
		#watch-list {
			text-align: center;
			background-color: #A7A7A7;
			position: fixed;
			left: 10%;
			width: 80%;
			height: 170px;
			bottom: -145px;
			border: 1px solid black;
			border-bottom: none;
			-moz-border-radius: 15px;
			border-top-right-radius: 15px;
			border-top-left-radius: 15px;
			z-index: 6;
		}
		#watch-list a {
			display: inline-block;
			position: relative;
			margin: 10px;
		}
		#watch-list a img:first-child {
			height: 130px;
			width: 92px;
			/*z-index: 7;*/
		}
		#watch-list a img:last-child {
			position: absolute;
			top: 5px;
			right: 5px;
			/*z-index: 8;*/
		}
		#drawer-handle {
			text-align: right;
			height: 25px;
			width: 100%;
			background-color: gray;
			-moz-border-radius: 15px;
			border-top-right-radius: 15px;
			border-top-left-radius: 15px;
		}
		#drawer-handle img {
			margin: 5px 15px;
			cursor: pointer;
		}
	</style>
</head>
<body id="index" onload="">
	
	<div id="container" <?php echo ($watch_list != FALSE) ? 'style="margin-bottom:200px"' : '' ?>>
	
		<a href="index.php" style="float:left">Home</a>
		
	<?php if (!isset($data['error'])): ?>
			
		<?php if (!isset($library['library']) || count($library['library']) == 0 || !$library['library']): ?>
		
			<div style="font-size:1.5em; color:white; text-align:center; clear:left">No movies found</div>
		
		<?php else: ?>
			
			<form method="get" action="index.php" id="library-search">
				<input type="hidden" name="type" value="Lib">
				<input type="text" name="search" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : '' ?>" placeholder="Search library">
			</form>
			
			<?php if ($show_pagination): ?>
				<div style="text-align:center">
					<?php for ($i=1; $i<=$total_pages; $i++): ?>
						<?php if (!isset($_GET['search'])): ?>
							<a href="index.php?library&p=<?php echo $i ?>"><?php echo $i . ' ' ?></a>
						<?php else: ?>
							<a href="index.php?type=Lib&search=<?php echo $_GET['search'] ?>&p=<?php echo $i ?>"><?php echo $i . ' ' ?></a>
						<?php endif ?>
					<?php endfor ?>
				</div>
			<?php endif ?>
			
			<div style="clear:both"></div>
		
			<ul class="library">
			<?php foreach ($library['library'] as $key => $movie): ?>
				<li id="<?php echo $movie['id'] ?>" title="<?php echo $movie['title'] ?>">
					<div>
						<a href="index.php?library&id=<?php echo $movie['id'] . (isset($_GET['p']) ? '&p='.$_GET['p'] : '') ?>">
							<img class="poster" src="<?php echo $movie['poster_path'] ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>">
						</a>
						<p><?php echo $movie['title'] . ((strlen($movie['release_date']) > 0) ? ' (' . substr($movie['release_date'], 0, 4) . ')' : '') ?></p>
					</div>
					
					<a class="details" href="index.php?summary&id=<?php echo $movie['id'] ?>">
						<img src="assets/img/icons/infobox.png" title="Show details">
					</a>
					
					<img class="link add" src="assets/img/icons/dialog-apply.png" title="Add to watch options" alt="Add">
					<img class="link remove" src="assets/img/icons/dialog-close.png" title="Mark for removal" alt="Remove">
				</li>
			<?php endforeach ?>
			</ul>
			
			<div style="clear:both"></div>
		
			<?php if ($library['total_pages'] > 1): ?>
			
				<?php if (get_get('p') > 1): ?>
					<?php if (!isset($_GET['search'])): ?>
						<a href="index.php?library&p=<?php echo $_GET['p']-1 ?>" style="float:left">
					<?php else: ?>
						<a href="index.php?type=Lib&search=<?php echo $_GET['search'] ?>&p=<?php echo $_GET['p']-1 ?>" style="float:left">
					<?php endif ?>
						
						<button type="button">Previous</button>
					</a>
				<?php endif ?>
			
				<?php if (!isset($_GET['p']) || $_GET['p'] < $library['total_pages']): ?>
				
					<?php if (!isset($_GET['search'])): ?>
						<a href="index.php?library&p=<?php echo (isset($_GET['p'])) ? $_GET['p']+1 : 2 ?>" style="float:right">
					<?php else: ?>
						<a href="index.php?type=Lib&search=<?php echo $_GET['search'] ?>&p=<?php echo (isset($_GET['p'])) ? $_GET['p']+1 : 2 ?>" style="float:right">
					<?php endif ?>
					
						<button type="button">Next</button>
					</a>
				<?php endif ?>
				
			<?php endif ?>
			
			<form action="index.php?library<?php echo (isset($_GET['p'])) ? '&p='.$_GET['p'] : '' ?>" method="post" accept-charset="utf-8" style="text-align:center">
				<input type="submit" name="remove" value="Remove">
			</form>
	
			<div class="preload">
				<img src="assets/img/icons/red_x.png" alt="">
			</div>
				
		<?php endif ?>
		
		<div id="msg" <?php echo (!isset($data['message'])) ? 'style="display:none"' : '' ?>>
			<?php echo (isset($data['message'])) ? $data['message'] : '' ?>
		</div>
		
	<?php else: ?>
		
		<div id="msg"><?php echo $data['message'] ?></div>
		<div style="clear:both"></div>
		
	<?php endif ?>
	</div>


	<?php // The watchlist ?>
	<?php if (!isset($data['error'])): ?>

		<div id="watch-list" data-position="<?php echo (!$watch_list) ? 'closed' : 'open' ?>" <?php echo ($watch_list != FALSE) ? 'style="bottom:0"' : '' ?>>
			<div id="drawer-handle">
				<img src="assets/img/icons/minimize.png">
			</div>

			<?php if ($watch_list != FALSE): ?>
				<?php foreach ($watch_list as $key => $movie): ?>
					<a href="index.php?library&id=<?php echo $movie['id'] ?>" data-watchlist-id="<?php echo $movie['id'] ?>">
						<img class="watchlist-icon" src="<?php echo $movie['poster_path'] ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>">
						<img class="watchlist-remove" src="assets/img/icons/dialog-close.png" title="Remove from watch list" alt="Remove">
					</a>
				<?php endforeach ?>
			<?php endif ?>
		</div>

	<?php endif ?>

</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/jquery.colorbox-min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/collectem.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Center the images
		$('.library').css('padding-left',(($(window).width()*0.8-20)%220)/2);

		// Hide the message box
		var t=setTimeout("hide_msg()",3500);
		
		// Toggle the red X on/off
		$('.remove').toggle(
			function() {
				//$(this).parent().addClass('selected');
				$(this).parent().append('<img src="assets/img/icons/red_x.png" class="checked">');
				$('form').append('<input type="hidden" name="selected[]" value="'+$(this).parent().attr('id')+'" id="selected_'+$(this).parent().attr('id')+'">');
			},
			function() {
				//$(this).parent().removeClass('selected');
				$(this).parent().find('.checked').remove();
				$('#selected_'+$(this).parent().attr('id')).remove();
			}
		);
		
		// Enable summary box
		$('.details').colorbox({
			innerWidth: '400px',
			onComplete: function() { 
		       $(this).colorbox.resize();
			}
		});

		// Add movie to watch list
		$('.add').click(function() {
			var id = $(this).parent().attr('id');

			$.post(
				'index.php',
				{
					'id': id,
					'add_watchlist': 'Y'
				},
				function(data) {
					if (data.status != 'Success') {
						$('#msg').text(data.status).fadeIn();
						var t=setTimeout("hide_msg()",3500);
					}
					else {
						$('#watch-list').append(
							$('\
								<a href="index.php?library&id='+data.movie.id+'" data-watchlist-id="'+data.movie.id+'">\
									<img class="watchlist-icon" src="'+data.movie.poster_path+'" title="'+data.movie.title+'" alt="'+data.movie.title+'">\
									<img class="watchlist-remove" src="assets/img/icons/dialog-close.png" title="Remove from watch list" alt="Remove">\
								</a>'
							).hide().fadeIn()
						);
						toggleWatchList('closed');
					}
				},
				'json'
			);
		});

		// Remove movie from watch list
		$(document).on('click', 'a .watchlist-remove', function(e) {
			e.preventDefault();
			var movie = $(this).parent();
			var id = movie.data('watchlist-id');

			$.post(
				'index.php',
				{
					'id': id,
					'remove_watchlist': 'Y'
				},
				function(data) {
					if (data != 'Success') {
						$('#msg').text(data).fadeIn();
						var t=setTimeout("hide_msg()",3500);
					}
					else {
						// Remove the watchlist poster
						movie.fadeOut(function() {
							$(this).remove();
						});
					}
				},
				'html'
			)
		});

		// Show/hide the watch list
		function toggleWatchList(position) {
			var bottom = (position == 'open') ? -145 : 0;
			position = (position == 'open') ? 'closed' : 'open';
			
			$('#watch-list').animate({'bottom': bottom});
			$('#container').animate({'margin-bottom': bottom+200});
			

			var top = $(window).scrollTop();
			var w_height = $(window).height();
			var h_height = $('html').height();
			if (top+w_height >= h_height)
				$('html,body').animate({scrollTop: $('html').height()}, 'slow');
			
			$('#watch-list').data('position',position);
		}

		$('#drawer-handle').click(function() {
			var position = $('#watch-list').data('position');
			toggleWatchList(position);
		});
	});
</script>
</html>