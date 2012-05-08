<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
	<title>Library (<?php echo $library['total_results'] ?>)</title>
	<link rel="stylesheet" href="assets/css/normalize.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/colorbox.css" type="text/css" media="screen" charset="utf-8">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css" media="screen" charset="utf-8">
	<style type="text/css" media="screen">
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
			height: 30px;
			width: 30px;
			bottom: 40px;
			right: 10px;
		}
		.library .remove {
			position: absolute;
			z-index: 3;
			height: 30px;
			width: 30px;
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
	</style>
<script type="text/javascript" charset="utf-8">
	
</script>
</head>
<body id="index" onload="">
	
	<div id="container">
	
		<a href="index.php" style="float:left">Home</a>
		
	<?php if (!isset($data['error'])): ?>
			
		<?php if (!isset($library['library']) || count($library['library']) == 0 || !$library['library']): ?>
		
			<div style="font-size:1.5em; color:white; text-align:center; clear:left">No movies found</div>
		
		<?php else: ?>
			
			<form method="get" action="index.php" id="library-search">
				<input type="hidden" name="type" value="Lib" />
				<input type="text" name="search" value="<?php echo (isset($_GET['search'])) ? $_GET['search'] : '' ?>" placeholder="Search library"/>
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
							<img class="poster" src="<?php echo (strlen($movie['poster_path']) > 0) ? $img_url . $sizes[2] . $movie['poster_path'] : 'assets/img/empty_img.png' ?>" title="<?php echo $movie['title'] ?>" alt="<?php echo $movie['title'] ?>"/>
						</a>
						<p><?php echo $movie['title'] . ((strlen($movie['release_date']) > 0) ? ' (' . substr($movie['release_date'], 0, 4) . ')' : '') ?></p>
					</div>
					
					<a class="details" href="index.php?summary&id=<?php echo $movie['id'] ?>">
						<img src="assets/img/icons/infobox_info_icon.png" title="Show details"/>
					</a>
					
					<img class="remove" src="assets/img/icons/red_x_corner.png" title="Mark for removal"/>
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
				<img src="assets/img/icons/red_x.png" alt=""/>
			</div>
				
		<?php endif ?>
		
		<?php if (isset($data['message'])): ?>
			<div id="msg"><?php echo $data['message'] ?></div>
		<?php endif ?>
		
	<?php else: ?>
		
		<div id="msg"><?php echo $data['message'] ?></div>
		<div style="clear:both"></div>
		
	<?php endif ?>
	</div>
</body>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/jquery.colorbox-min.js" charset="utf-8"></script>
<script type="text/javascript" src="assets/js/collectem.js" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Center the images
		$('ul').css('padding-left',(($(window).width()*0.8-20)%220)/2);

		// Hide the message box
		var t=setTimeout("hide_msg()",3500);
		
		// Toggle the red X on/off
		$('.remove').toggle(
			function() {
				//$(this).parent().addClass('selected');
				$(this).parent().append('<img src="assets/img/icons/red_x.png" class="checked"/>');
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
	});
</script>
</html>