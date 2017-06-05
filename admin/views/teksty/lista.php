<h1><?php echo $title; ?></h1>

<div class="col-xs-12">
	<form action="<?php url('teksty/zapisz'); ?>" method="post">

		<table class="table table-bordered table-form" id="text_form">
		<tr data-offset-top="50" class="b affix">
			<th>słowo kluczowe</th>
			<th>polski</th>
			<th>angielski</th>
		</tr>

		<?php
			foreach ($teksty as $k => $v) {
				echo '
				<tr>
					<td><input type="hidden" name="dane['.$k.'][id]" value="'.$v->id.'" />
						<input type="text" name="dane['.$k.'][short]" value="'.$v->short.'" /></td>
					<td><input type="text" name="dane['.$k.'][pl]" value="'.$v->t_pl.'" /></td>
					<td><input type="text" name="dane['.$k.'][en]" value="'.$v->t_en.'" /></td>
				</tr>';
			}
		?>

		</table>

		<div class="btn-group box-center">
			<button type="button" id="addText" class="btn btn-info"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> dodaj nowy</button>
			<button type="submit" class="btn btn-success">zapisz wprowadzone zmiany</button>
		</div>

	</form>
</div>