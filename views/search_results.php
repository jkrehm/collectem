<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title>Search: <?php echo get_get('search') ?></title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/colorbox.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
		ul li div p {
			color: white;
		}
		.results {
			margin: 0;
			padding: 0;
		}
		.results li {
			position: relative;
			float: left;
			height: 330px;
			list-style: none;
			margin: 10px;
		}
		.results div {
			position: relative;
			width: 200px;
			margin: 0 auto;
		}
		.results img.poster {
			height: 300px;
			width: 200px;
		}
		.results a.details img {
			position: absolute;
			z-index: 3;
			height: 30px;
			width: 30px;
			bottom: 35px;
			right: 10px;
		}
		.results p {
			font-size: 12px;
			margin: 0.5em 0;
			text-align: center;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		li.selected div {
			outline: 1px solid black;
		}
		.checked {
			z-index: 2;
			position: absolute;
			left: 10px;
			top: 10px;
			height: 280px;
			width: 180px;
			opacity:0.5;
			filter:alpha(opacity=50);
		}
		#container {
			background-color: #2B9BF1;
			border: 2px solid #15539A;
			-moz-box-shadow: 10px 10px 5px #888;
			-webkit-box-shadow: 10px 10px 5px #888;
			box-shadow: 10px 10px 5px #888;
			-moz-border-radius: 15px;
			border-radius: 15px;
			padding: 20px;
		}
	</style>
</head>
<body id="index" onload="">
	
	<div id="container">
	
		<a href="index.php" style="float:left">Home</a>
		
	<?php if (!isset($data['error'])): ?>
			
		<?php if (!isset($movies['results']) || count($movies['results']) == 0): ?>
		
			<div style="font-size:1.5em; color:white; text-align:center">No movies found</div>
		
		<?php else: ?>
		
			<div style="color:white; float:right">Movies: <?php echo $movies['total_results'] ?></div>
			
			<?php if ($show_pagination): ?>
				<div style="text-align:center">
					<?php for ($i=1; $i<=$movies['total_pages']; $i++): ?>
						<a href="index.php?type=Title&search=<?php echo urlencode($collectem->getSearchVal()) ?>&page=<?php echo $i ?>"><?php echo $i . ' ' ?></a>
					<?php endfor ?>
				</div>
			<?php else: ?>
				<div style="clear:both"></div>
			<?php endif ?>
			
			<ul class="results">
			<?php foreach ($movies['results'] as $key => $movie): ?>
				<li title="<?php echo $movie['title'] ?>">
					<div id="<?php echo $movie['id'] ?>">
						<img class="poster" src="<?php echo (strlen($movie['poster_path']) > 0) ? $movies['img_url'] . $sizes[2] . $movie['poster_path'] : 'assets/img/empty_img.png' ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>"/>
						<p><?php echo $movie['title'] . ((strlen($movie['release_date']) > 0) ? ' (' . substr($movie['release_date'], 0, 4) . ')' : '') ?></p>
					</div>
					
					<a class="details" href="index.php?summary&id=<?php echo $movie['id'] ?>">
						<img class="details" src="assets/img/icons/infobox_info_icon.png" title="Show details"/>
					</a>
				</li>
			<?php endforeach ?>
			</ul>
			
			<div style="clear:both"></div>
		
			<?php if ($movies['total_pages'] > 1): ?>
			
				<?php if ($collectem->getPageNumber() > 1): ?>
					<a href="index.php?type=Title&search=<?php echo urlencode($collectem->getSearchVal()) ?>&page=<?php echo $collectem->getPageNumber() - 1 ?>" style="float:left">
						<button type="button">Previous</button>
					</a>
				<?php endif ?>
			
				<?php if ($collectem->getPageNumber() < $movies['total_pages']): ?>
					<a href="index.php?type=Title&search=<?php echo urlencode($collectem->getSearchVal()) ?>&page=<?php echo $collectem->getPageNumber() + 1 ?>" style="float:right">
						<button type="button">Next</button>
					</a>
				<?php endif ?>

			<?php endif ?>
		
			<form action="index.php" method="post" accept-charset="utf-8" style="text-align:center">
				<input type="submit" name="add" value="Add">
			</form>

			<div class="preload">
				<img src="assets/img/icons/green_checkmark.png" alt=""/>
			<div>
		
		<?php endif ?>
		
		<?php if (isset($data['message'])): ?>
			<div id="msg"><?php echo $data['message'] ?></div>
		<?php endif ?>
		
	<?php else: ?>
		
		<div id="msg"><?php echo $data['message'] ?></div>
		
	<?php endif ?>
	</div>
	
</body>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/jquery.colorbox-min.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Center the images
		$('ul').css('padding-left',(($(window).width()*0.8-20)%220)/2);
		
		// Toggle the green checkmark on/off
		$('.results div').toggle(
			function() {
				$(this).addClass('selected');
				$(this).append('<img src="assets/img/icons/green_checkmark.png" class="checked"/>');
				$('form').append('<input type="hidden" name="selected[]" value="'+this.id+'" id="selected_'+this.id+'">');
			},
			function() {
				$(this).removeClass('selected');
				$(this).find('.checked').remove();
				$('#selected_'+this.id).remove();
			}
		);
		
		// Enable summary box
		$('.details').colorbox({
			innerWidth: '400px',
			onComplete: function() { 
		       $(this).colorbox.resize();
			}
		});
	});
</script>

</html>