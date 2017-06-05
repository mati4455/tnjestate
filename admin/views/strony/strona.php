<div>
	<h1>Zarządzanie stroną</h1>
	<form action="<?php url('strony/zapisz'); ?>" method="post">

	<?php 
		$basic = $strona[0];
		?>
		<input type="hidden" name="dane[id]" value="<?=$basic->strona_id; ?>" />

		<div class="form-group">
			<label>URL link</label>
			<input placeholder="short tag" class="form-control" name="dane[short]" value="<?=$basic->short; ?>" />
		</div>

		<div class="form-group">
			<label>Klasy CSS</label>
			<input placeholder="klasy css" class="form-control" name="dane[klasa]" value="<?=$basic->klasa; ?>" />
		</div>

		<div class="form-group">
			<label>Dane językow (kliknij, aby rozwinąć):</label>
		</div>

		<?php foreach ($strona as $key => $value) {
			$lang = $value->language_code;
			?><a href="#" class="prevent" data-toggle="collapse" data-target="#strona_<?=$lang;?>"><h3>Dane dla języka <?=$lang; ?></h3></a>

			<div id="strona_<?=$lang;?>" class="collapse">
				<input type="hidden" name="dane[<?=$lang;?>][id]" value="<?=$value->id; ?>" />
				<div class="form-group">
					<label>Tytuł strony</label>
					<input placeholder="tytuł" class="form-control" name="dane[<?=$lang;?>][title]" value="<?=$value->title; ?>" />
				</div>

				<div class="form-group">
					<label>Słowa kluczowe</label>
					<input placeholder="słowa kluczowe" class="form-control" name="dane[<?=$lang;?>][keywords]" value="<?=$value->keywords; ?>" />
				</div>

				<div class="form-group">
					<label>Opis (dla wyszukiwarek)</label>
					<input placeholder="opis (wyszukiwarki)" class="form-control" name="dane[<?=$lang;?>][description]" value="<?=$value->description; ?>" />
				</div>

				<div class="form-group">
					<label>Zawartość strony</label>
					<textarea class="ckeditor" class="form-control" name="dane[<?=$lang;?>][tresc]">
						<?=$value->tresc; ?>
					</textarea>
				</div>
			</div>
			<?
		}
	?>
		<div class="form-group center">
			<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>zapisz wprowadzone zmiany</button>
		</div>
	</form>

</div>