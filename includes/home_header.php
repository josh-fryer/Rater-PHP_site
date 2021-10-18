<!doctype html>
<html lang="en" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		<?php
			if(!isset($page_title))	{
				echo 'Rater'; 
			} else {
				echo $page_title;
			}
		?>
	</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
	<link rel="stylesheet" href="includes/style.css">
</head>

<body>
	<main>
		<nav class="topnav">
				<ul>
					<li><a href="index.php">Rater</a></li>
					<li><a href="search.php">Search</a></li>
					<li><a href="profile.php">Profile</a></li>
					<li><a href="logout.php">Logout</a></li>
				</ul>
		</nav>

		<aside class="left_addbar"></aside>
		<div class="content">
											
<!-- end of includes\header.html -->
<!--	</div>
		<aside class="right_addbar"></aside>
		<footer class="footer">
			<div class="row">
				<small>&copy; Copyright <?php echo date("Y"); ?> | Rater | 123 West St, Sheffield | S12 3JDZ |</small>
			</div>
		</footer>
	</main>
</body>
</html>-->	