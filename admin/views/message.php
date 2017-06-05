<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>e-lokalo - informacja</title>
		<style>
			.information { 
				text-align: center;
				font-size: 30px;
				color: #06F;
				padding: 20px;
			}
			.information h2 {
				margin: 10px auto;
				padding: 0px;
				color: #000;
			}
			.center {
				text-align: center;
			}
		</style>
	</head>
	<body>	
		<div class="information">
			<h2>Komunikat</h2>
			<span><?php echo $message; ?></span>
		</div>
		<div class="center">
			<a href="<?php echo __SITE_PATH; ?>">Powrót do strony głównej</a>
		</div>
	</body>
</html>