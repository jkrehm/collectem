<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
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
		<p class="poster"><?php echo $movie['release_date'] ?></p>
		<p><?php echo $movie['overview'] ?></p>
	</div>
</body>
</html>
