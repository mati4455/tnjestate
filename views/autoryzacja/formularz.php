
<div>
	<form class="form-signin" action="<?php url('autoryzacja/zaloguj');?>" method="POST">
		<h2 class="form-signin-heading">Zaloguj się</h2>
		<input type="hidden" name="backurl" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
		<label for="login" class="sr-only">Login</label>
		<input type="text" name="login" id="login" class="form-control first " value="<?php echo $_POST['login']; ?>" placeholder="Login" required autofocus>
		<label for="password" class="sr-only">Hasło</label>
		<input type="password" name="password" id="password" class="form-control last" placeholder="Hasło">
		<div class="margin-bottom-10"><a href="/autoryzacja/przypomnijHaslo">Odzyskaj hasło</a></div>
		<button onclick="formhash(this.form, this.form.password);" name="send" class="btn btn-lg btn-primary btn-block" type="submit">Zaloguj</button>
	</form>
</div>
