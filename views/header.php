<!DOCTYPE html>
<html>
<head>
	<title>Some title</title>

	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/style.css">
</head>
<body>

<?php 
	$directoryURI = $_SERVER['REQUEST_URI'];
	$path = parse_url($directoryURI, PHP_URL_PATH);
	$components = explode('/', $path);
	$second_part = $components[2];
?>

<header>
	<ul class="nav nav-pills pull-right">
    <li class="<?php if ($second_part == "" || $second_part == "index") {echo "active"; } else  {echo "noactive";}?>"><a href="<?php echo URL; ?>index">Home</a></li>
    <li class="<?php if ($second_part == "help") {echo "active"; } else  {echo "noactive";}?>"><a href="<?php echo URL; ?>help">Help</a></li>
    <li class="<?php if ($second_part == "login") {echo "active"; } else  {echo "noactive";}?>"><a href="<?php echo URL; ?>login">Login</a></li>
  </ul>

	<h3><a href="<?php echo URL ?>">eShop</a></h3>
</header>

<div id="container">