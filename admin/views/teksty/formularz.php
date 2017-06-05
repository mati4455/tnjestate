<h1>Zarządzanie kategorią</h1>
<div>
	<form class="form-horizontal" action="<?php url('kategorie/zapisz');?>" method="POST">
		<input type="hidden" name="dane[id]" value="<?php echo $dane->id; ?>" />

		<div class="form-group">
			<label for="nazwa" class="col-xs-12 col-sm-4 col-md-3 control-label">Nazwa kategeorii</label>
			<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[nazwa]" id="nazwa" class="form-control first" value="<?php echo $dane->nazwa; ?>" placeholder="Nazwa kategorii" required autofocus></div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-offset-4 col-md-offset-3 col-sm-6 col-md-5">
				<button	name="send" id="send" class="btn btn-lg btn-primary btn-block" type="submit">zapisz zmiany</button>
			</div>
		</div>                            
	</form>
</div>