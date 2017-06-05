<div>
	<form class="form-signin" action="<?php url('autoryzacja/odzyskaj');?>" method="POST">
		<h2 class="form-signin-heading">Odzyskiwanie hasła</h2>
		<label for="email" class="sr-only">E-mail</label>
		<input type="email" name="email" id="email" class="form-control margin-bottom-10" value="<?php echo $_POST['email']; ?>" placeholder="E-mail" required autofocus>
		
		<button name="send" class="btn btn-lg btn-primary btn-block" type="submit">Odzyskaj</button>
	</form>
</div>