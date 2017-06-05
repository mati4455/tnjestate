<div class="row">
	<div class="col-xs-12 col-sm-9 col-md-10"><h2><?php echo $tytul; ?></h2></div>
</div>

<div class="results">
<?php
	echo $czyPuste;

	foreach ($oferty as $k => $v) {
		echo $oferta->getListElement($v, $kat);
	}
?>
</div>

<?
	echo !empty($nawigacja) ? $nawigacja : '';
	
?>

