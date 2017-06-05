<h1>Formularz agenta</h1>

<form action="<?php url('agent/zapisz'); ?>" method="post">

	<input type="hidden" name="agent[id]" value="<? echo $dane->id; ?>" />

	<div class="form-group">
		<label for="imie_nazwisko">Imię i nazwisko</label>
		<input type="text" name="agent[imie_nazwisko]" id="imie_nazwisko" value="<?php echo $dane->imie_nazwisko; ?>" class="form-control"  />
	</div>

	<div class="form-group">
		<label for="telefon">Telefon</label>
		<input type="text" name="agent[telefon]" id="telefon" value="<?php echo $dane->telefon; ?>" class="form-control" />
	</div>

	<div class="form-group btn-group center">
		<?php if (!empty($dane->id)) { ?>
		<a href="<?php url('agent/usun/' . $dane->id); ?>" data-confirm="Czy usunąć wybranego agenta?" class="confirm btn btn-danger">
		<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>usuń agenta</a>
		<? } ?>
		<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>zapisz wprowadzone zmiany</button>
	</div>

</form>
