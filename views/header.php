<!DOCTYPE html>
<html lang="en">
<head>
	<title>Demo</title>
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/style.css">
	<script type="text/javascript" src="<?php echo URL; ?>public/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/bootstrap.js"></script>
	<script type="text/javascript" src="<?php echo URL; ?>public/js/bootstrap.min.js"></script>
	
	<script>
	</script>
</head>
<body>

<div id="wrapper">
	<header>
		<div class="cart">
			<strong>Your shopping cart</strong>

			<div class="items">
				<?php echo Controller::showCart(); ?><?php ?>
			</div>
		</div>

		<h3><a href="<?php echo URL ?>">eShop</a></h3>	
	</header>

	<div id="container">