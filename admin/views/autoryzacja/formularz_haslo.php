<div>
	<form class="form-signin" action="<?php url('autoryzacja/zapiszHaslo');?>" method="POST">
		<h2 class="form-signin-heading">Ustaw hasło</h2>
		<p id="inf" class="bg-primary"></p>
		<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
		<label for="password" class="sr-only">Hasło</label>
		<input type="password" name="password" id="password" class="form-control first" placeholder="Hasło" >
		<label for="confirmpwd" class="sr-only">Powtórz</label>
		<input type="password" name="confirmpwd" id="confirmpwd" class="form-control last" placeholder="Powtórz hasło" >

		<button onclick="formhash2(this.form, this.form.password, this.form.confirmpwd);" name="send" class="btn btn-lg btn-primary btn-block" type="button">Zapisz hasło</button>
	</form>
</div>