<h1>Formularz miejscowości</h1>

<div class="col-xs-10 col-xs-offset-1"> 
	<form action="<?php url('lokalizacje/miasto/zapisz'); ?>" method="post">
		<input type="hidden" name="miasto[id]" value="<?php echo $miasto->city_id; ?>" />
		<div class="form-group">
			<label for="miasto">Nazwa miejscowości</label>
			<div><input type="text" name="miasto[nazwa]" id="miasto" class="form-control last" value="<?php echo $miasto->miasto; ?>" placeholder="Nazwa miejscowości" required autofocus></div>
		</div>
		<div class="form-group btn-group center">
			<?php if (!empty($miasto->city_id)) { ?>
			<button type="button" data-confirm="Czy usunąć wybraną miejscowość, jej osiedla i oferty powiązane?" data-id="<?php echo $miasto->city_id; ?>" data-type="miasto" class="delete btn btn-danger">
			<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>usuń miejscowość</button>
			<? } ?>
			<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>zapisz wprowadzone zmiany</button>
		</div>
	</form>
</div>