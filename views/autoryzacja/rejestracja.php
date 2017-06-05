<div>
	<form class="form-horizontal" action="<?php url('autoryzacja/rejestruj');?>" method="POST">
		<h2 class="form-horizontal-heading center">Zarejestruj się</h2>
		
		<div class="form-group">
			<label for="login" class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3 control-label">Login</label>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="text" name="login" id="login" class="form-control first" value="<?php echo $_POST['login']; ?>" placeholder="Login" required autofocus></div>
		</div>

		<div class="form-group">
			<label for="email" class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3 control-label">Email</label>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="email" name="email" id="email" class="form-control last" value="<?php echo $_POST['email']; ?>" placeholder="Email" required></div>
		</div>

		<div class="form-group">
			<label for="datau" class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3 control-label">Data urodzenia<span class="smallSub">dd-mm-rrrr</span></label>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="date" name="datau" id="datau" class="form-control first" value="<?php echo $_POST['datau']; ?>" placeholder="dd-mm-rrrr" required></div>
		</div>

		<div class="form-group">
			<label for="password" class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3 control-label">Hasło</label>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="password" name="password" id="password" class="form-control" placeholder="Hasło"></div>
		</div>

		<div class="form-group">
			<label for="confirmpwd" class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-3 col-md-offset-3 control-label">Powtórz hasło</label>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="password" name="confirmpwd" id="confirmpwd" class="form-control" placeholder="Powtórz hasło"></div>
	 	</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-offset-3 col-sm-3 right"><img class="margin-bottom-10" width="100%" src="<?php echo $_SESSION['captcha']['image_src']; ?>" /></div>
			<div class="col-xs-12 col-md-3 col-sm-4"><input type="text" class="form-control last" name="captcha" value="" placeholder="Przepisz kod z obrazka" /></div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-offset-6 col-sm-3">

				<button onclick="return regformhash(this.form, this.form.login, this.form.email, this.form.password, this.form.confirmpwd, this.form.captcha);" 
					name="send" class="btn btn-lg btn-primary btn-block" type="submit">Zarejestruj</button>
			</div>
		</div>                            
	</form>
</div>