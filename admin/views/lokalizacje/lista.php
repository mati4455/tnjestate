<h1><?php echo $title; ?></h1>

<div class="col-xs-10 col-xs-offset-1">
	<div class="margin-top-20 margin-bottom-20">Kliknij na wybraną lokalizację, aby ją edytować</div>
	<?php 
		$last = '';
		foreach ($dane as $key => $value) {
			$city_url = get_url('lokalizacje/miasto/' . $value->city_id);
			$district_url = get_url('lokalizacje/osiedle/' . $value->id);
			if ($value->miasto != $last) {
				$last = $value->miasto;
				echo '<h3 class="margin-top-30"><a href="'.$city_url.'">' . $last . '</a></h3>';
			}
			$nazwa = empty($value->osiedle) ? '(bez osiedla - sama miejscowość)' : $value->osiedle;
			if (!empty($value->id)) echo '<div><a href="'.$district_url.'">' . $nazwa . '</a></div>';
		}
	?>

</div>