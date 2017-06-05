<h1>Formularz osiedla</h1>

<div class="col-xs-10 col-xs-offset-1">
	<form action="<?php url('lokalizacje/osiedle/zapisz'); ?>" method="post">
		<input type="hidden" name="osiedle[id]" value="<?php echo $osiedle->id; ?>" />
		<div class="form-group">
			<label for="city">Nazwa miejscowości</label> 
			<div>
				<select name="osiedle[city]" id="city" class="form-control first">
					<option value="0">Wybierz miejscowość</option>
					<?php 
						foreach ($miasta as $key => $value) {
							$sel = $value->city_id == $osiedle->city_id ? 'selected' : '';
							echo '<option '.$sel.' value="' . $value->city_id .'">' . $value->miasto . '</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="district">Nazwa osiedla</label>
			<div><input type="text" name="osiedle[district]" id="district" class="form-control last" value="<?php echo $osiedle->osiedle; ?>" placeholder="Nazwa osiedla" required autofocus></div>
		</div>
		<div class="form-group btn-group center">
			<?php if (!empty($osiedle->id)) { ?>
			<button type="button" data-confirm="Czy usunąć wybrane osiedle i oferty powiązane?" data-id="<?php echo $osiedle->id; ?>" data-type="osiedle" class="delete btn btn-danger">
			<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>usuń osiedle</button>
			<? } ?>
			<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>zapisz wprowadzone zmiany</button>
		</div>
	</form>
</div>