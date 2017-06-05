<div class="row">
	<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
		<h2><?php echo $title; ?></h2>

		<div class="offert-box">
			<form action="<?php lang_url('strony/wyslij_oferte');?>" method="post">
			<div class="form-group"><?php echo $select_category; ?></div>
				<div class="form-group"><?php echo $select_type; ?></div>
				<div class="form-group"><?php echo $select_location; ?></div>
				<div class="form-group">
					<input type="text" class="form-control" name="dane[imie]" placeholder="<?=$texts['imie']; ?>" />
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="dane[email]" placeholder="<?=$texts['email']; ?>" />
				</div>
				<div class="form-group">
					<input type="text" class="form-control" name="dane[telefon]" placeholder="<?=$texts['telefon']; ?>" />
				</div>
				<div class="sep20"></div>
				<div class="form-group">
					<textarea class="form-control" name="dane[wiadomosc]" placeholder="<?=$texts['wiadomosc'];?>"></textarea>
				</div>
				<div class="form-group row-no-padding">
					<div class="col-xs-2">
						<img class="img-responsive" src="<?php echo $_SESSION['captcha']['image_src']; ?>" />
					</div>
					<div class="col-xs-9 col-xs-offset-1">
						<input type="text" class="form-control" name="captcha" placeholder="Przepisz kod z obrazka" />
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary"><?=strtoupper($texts['wyslij']); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>