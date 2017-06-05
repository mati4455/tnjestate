<div>
	<form class="form-signin" action="<?php url('konto/zapisz-haslo');?>" method="POST">
		<h2 class="form-signin-heading">Ustaw hasło</h2>
		<p id="inf" class="bg-primary"></p>
		<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
		<label for="password">Hasło</label>
		<input type="password" name="password" id="password" class="form-control first">
		<label for="confirmpwd">Powtórz</label>
		<input type="password" name="confirmpwd" id="confirmpwd" class="form-control last">

		<button onclick="formhash2(this.form, this.form.password, this.form.confirmpwd);" name="send" class="btn btn-lg btn-primary btn-block" type="button">Zapisz hasło</button>
	</form>
</div>