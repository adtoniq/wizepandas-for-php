<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php require_once('../src/wizepandas.php'); ?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
	<title>PHP test site</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<?php echo $wizepandas->getHeadCode(); ?>
</head>

<body style="height: 100%;">
	<div class="container" style="background: #e3e3e3; height: 100%;">
