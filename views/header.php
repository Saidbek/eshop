<!DOCTYPE html>
<html lang="en">
<head>
	<title>Online e-shop demo</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/style.css">
</head>
<body>

<div id="wrapper">
	<header>
		<div class="cart">
			<strong>Your shopping cart</strong>

			<div class="items">
				<?= Controller::showCart(); ?>
			</div>
		</div>

		<h3><a href="<?php echo URL ?>">eShop</a></h3>	
	</header>

	<div id="container">