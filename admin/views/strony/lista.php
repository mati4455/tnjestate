<div>
	<h1>Lista stron statycznych</h1>

	<ul class="lista">
<?php
	foreach ($strony as $key => $value) {
		?><li><a href="<?php url('strony/edytuj/' . $value->strona_id); ?>"><?php echo $value->title; ?></a></li><?
	}
?>

</div>