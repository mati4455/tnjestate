<h2>Zgłoś błąd</h2>
<div>
	<form class="form-horizontal" id="film_form" action="<?php lang_url('strona/zglos-blad/wyslij');?>" method="POST">
		<div class="form-group">
			<label for="email" class="col-xs-12 col-sm-4 col-md-3 control-label">Email</label>
			<div class="col-xs-12 col-md-5 col-sm-6"><input type="email" name="email" id="email" class="form-control last" value="<?php echo $_POST['email']; ?>" placeholder="Email" required></div>
		</div>

		<div class="form-group">
			<label for="opis" class="col-xs-12 col-sm-4 col-md-3 control-label">Opis błędu</label>
			<div class="col-xs-12 col-md-5 col-sm-6">
				<textarea rows="6" required name="opis" id="opis" class="form-control" placeholder="Wprowadź opis błędu"></textarea>
			</div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-4 col-md-3 right"><img class="margin-bottom-10" width="100%" src="<?php echo $_SESSION['captcha']['image_src']; ?>" /></div>
			<div class="col-xs-12 col-sm-6 col-md-5"><input type="text" class="form-control last" name="captcha" placeholder="Przepisz kod z obrazka" /></div>
		</div>

		<div class="form-group">
			<div class="col-xs-12 col-sm-offset-4 col-md-offset-3 col-sm-6 col-md-5">
				<button	name="send" id="send" class="btn btn-lg btn-primary btn-block" type="submit">wyślij</button>
			</div>
		</div>                            
	</form>
</div>