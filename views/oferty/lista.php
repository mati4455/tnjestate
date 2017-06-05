<div class="row">
	<div class="search-align col-xs-12 col-sm-6 col-md-6 col-lg-7">
		<?php if (!preg_match('#[0-9]#',$_GET['action'])) echo $types_menu; ?>

		<div class="sep20"></div>
		<h2><?=$title;?></h2>
		<div class="results">
		<?php
			echo $czyPuste;

			foreach ($oferty as $k => $v) {
				echo $oferta->getListElement($v, $kat);
			}
		?>
		</div>
		<?
			echo $nawigacja;
		?>
	</div>
	<div class="col-sm-1"></div>

	<?php echo $search_form; ?> 
</div>