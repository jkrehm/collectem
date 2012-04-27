<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
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
			margin: 10% auto;
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
	
	<div id="container">
	
	<?php if (!isset($data['error'])): ?>

		<form action="index.php" method="get" accept-charset="utf-8">
			<p>
				<label for="search_type_title">Title</label>
				<input type="radio" name="type" value="Title" id="search_type_title" checked="checked">
			
				<label for="search_type_upc">UPC</label>
				<input type="radio" name="type" value="UPC" id="search_type_upc">
			</p>
			<p>
				<label for="search_term" style="display:none">Title or UPC</label>
				<input type="text" name="search" value="" id="search_term" placeholder="Title or UPC">
			</p>
			<!-- <div class="hidden-submit"><input type="submit" value="Search" tabindex="-1"></div> -->
			<input type="submit" value="Search" tabindex="-1">
			<a href="index.php?library"><button type="button">View Library</button></a>
		</form>
		
		<?php if (isset($data['message'])): ?>
			<div id="msg"><?php echo $data['message'] ?></div>
		<?php endif ?>
		
	<?php else: ?>
	
		<div><?php echo $data['message'] ?></div>
			
	<?php endif ?>
	</div>
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