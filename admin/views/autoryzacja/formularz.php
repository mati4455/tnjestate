
<div class="col-xs-12">
	<form class="form-signin" action="<?php url('autoryzacja/zaloguj');?>" method="POST">
		<div class="center"><img class="p100" src="/assets/img/logo.png" /></div>
		<div class="sep20"></div>
		<input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
		<label for="login" class="sr-only">Login</label>
		<input type="text" name="login" id="login" class="form-control first " value="<?php echo $_POST['login']; ?>" placeholder="Login" required autofocus>
		<label for="password" class="sr-only">Hasło</label>
		<input type="password" name="password" id="password" class="form-control last" placeholder="Hasło">
		<button onclick="formhash(this.form, this.form.password);" name="send" class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>
	</form>
</div>
