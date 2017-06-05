<h2><?php echo $title; ?></h2>

	<form class="form-horizontal" id="oferta_form" action="<?php url('oferty/zapisz');?>" method="POST">
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	
	<div class="panel panel-default">
   		<div class="panel-heading" role="tab" id="h1">
		<h4 class="panel-title">
       	<a data-toggle="collapse" data-parent="#accordion" href="#podstawowe" aria-expanded="true" aria-controls="podstawowe">
        	Dane postawowe oferty
        </a>
      	</h4>
    	</div>

    	<div id="podstawowe" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="h1">
		<div class="panel-body">
			<input type="hidden" name="dane[id]" value="<?php echo $dane->id; ?>" />

			<div class="form-group">
				<label for="rodzaj_id" class="col-xs-12 col-sm-4 col-md-3 control-label">Rodzaj</label>
				<div class="col-xs-12 col-md-5 col-sm-6">
					<select name="dane[rodzaj_id]" id="rodzaj_id" class="form-control" required autofocus>
						<option value="" >Wybierz</option>
						<?php foreach ($categories as $key => $value) {
							$tmp = $dane->rodzaj_id == $value->id ? 'selected' : '';		
							echo '<option value="'.$value->id.'" '.$tmp.'>'.$value->title.'</option>';				
						} ?>
					</select>
				</div>
			</div>

			<?php $checked = $dane->specjalna == 1 ? 'checked' : ''; ?>
			<div class="form-group">
				<label for="specjalna" class="col-xs-12 col-sm-4 col-md-3 control-label">Tylko u nas</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="checkbox" name="dane[specjalna]" id="specjalna" value="1" <?php echo $checked; ?>></div>
			</div>	

			<div class="form-group">
				<label for="typ_id" class="col-xs-12 col-sm-4 col-md-3 control-label">Typ</label>
				<div class="col-xs-12 col-md-5 col-sm-6">
					<select name="dane[typ_id]" id="typ_id" class="form-control" required>
						<option value="" >Wybierz</option>
						<?php foreach ($types as $key => $value) { 
							$tmp = ($dane->typ_id == $value->id) ? 'selected' : '';		
							echo '<option value="'.$value->id.'" '.$tmp.'>'.$value->title.'</option>';				
						} ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="lokalizacja_id" class="col-xs-12 col-sm-4 col-md-3 control-label">Lokalizacja</label>
				<div class="col-xs-12 col-md-5 col-sm-6">
					<select name="dane[lokalizacja_id]" id="lokalizacja_id" class="form-control" required>
						<option value="" >Wybierz</option>
						<?php foreach ($locations as $key => $value) { 
							$tmp = ($dane->lokalizacja_id == $value->id) ? 'selected' : '';		
							echo '<option value="'.$value->id.'" '.$tmp.'>'.$value->title.'</option>';				
						} ?>
					</select>
				</div>
			</div>	

			<div class="form-group">
				<label for="ulica" class="col-xs-12 col-sm-4 col-md-3 control-label">Ulica</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[ulica]" id="ulica" class="form-control" value="<?php echo $dane->ulica; ?>" placeholder="Ulica"></div>
			</div>	

			<div class="form-group">
				<label for="agent_id" class="col-xs-12 col-sm-4 col-md-3 control-label">Agent</label>
				<div class="col-xs-12 col-md-5 col-sm-6">
					<select name="dane[agent_id]" id="agent_id" class="form-control" required>
						<option value="" >Wybierz</option>
						<?php foreach ($agents as $key => $value) { 
							$tmp = ($dane->agent_id == $value->id) ? 'selected' : '';		
							echo '<option value="'.$value->id.'" '.$tmp.'>'.$value->imie_nazwisko.'</option>';				
						} ?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label for="pokoje" class="col-xs-12 col-sm-4 col-md-3 control-label">Ilość pokoi</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[pokoje]" id="pokoje" class="form-control " value="<?php echo $dane->pokoje; ?>" placeholder="Ilość pokoi" required></div>
			</div>	

			<div class="form-group">
				<label for="pietro" class="col-xs-12 col-sm-4 col-md-3 control-label">Piętro</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[pietro]" id="pietro" class="form-control " value="<?php echo $dane->pietro; ?>" placeholder="Piętro"></div>
			</div>	

			<div class="form-group">
				<label for="pieter" class="col-xs-12 col-sm-4 col-md-3 control-label">Pięter</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[pieter]" id="pieter" class="form-control " value="<?php echo $dane->pieter; ?>" placeholder="Ilość pięter w budynku"></div>
			</div>	

			<div class="form-group">
				<label for="metraz" class="col-xs-12 col-sm-4 col-md-3 control-label">Metraż</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[metraz]" id="metraz" class="form-control " value="<?php echo $dane->metraz; ?>" placeholder="Metraż" required></div>
			</div>	

			<div class="form-group">
				<label for="cena" class="col-xs-12 col-sm-4 col-md-3 control-label">Cena</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[cena]" id="cena" class="form-control " value="<?php echo $dane->cena; ?>" placeholder="Cena"></div>
			</div>	

			<div class="form-group">
				<label for="licencja" class="col-xs-12 col-sm-4 col-md-3 control-label">Nr licencji</label>
				<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="dane[licencja]" id="cena" class="form-control" value="<?php echo $dane->licencja; ?>" placeholder="Nr licencja"></div>
			</div>	
		</div>
   		</div>
	</div>

	<?php
		foreach ($langs as $k => $v) { ?>
			<div class="panel panel-default">
    		<div class="panel-heading" role="tab" id="h<?echo $v;?>">
      		<h4 class="panel-title">
        	<a data-toggle="collapse" data-parent="#accordion" href="#jezyk_<?echo $v;?>" aria-expanded="true" aria-controls="jezyk_<?echo $v;?>">
          		Opis oferty (<?echo $v;?>)
        	</a></h4></div>

    		<div id="jezyk_<?echo $v;?>" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="h<?echo $v;?>">
			<div class="panel-body">

				<input type="hidden" name="lang[<?=$v;?>][id]" value="<?php echo $lang[$v]->id; ?>" />

				<div class="form-group">
					<label for="title" class="col-xs-12 col-sm-4 col-md-3 control-label">Tytuł oferty</label>
					<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="lang[<?=$v;?>][title]"id="title" class="form-control first" value="<?php echo $lang[$v]->title; ?>" placeholder="Tytuł oferty" ></div>
				</div>

				<div class="form-group">
					<label for="keywords" class="col-xs-12 col-sm-4 col-md-3 control-label">Słowa kluczowe</label>
					<div class="col-xs-12 col-md-5 col-sm-6"><input type="text" name="lang[<?=$v;?>][keywords]" id="keywords" class="form-control first" value="<?php echo $lang[$v]->keywords; ?>" placeholder="Słowa kluczowe"></div>
				</div>

				<div class="form-group">
					<label for="tresc" class="col-xs-12 col-sm-4 col-md-3 control-label">Opis oferty</label>
					<div class="col-xs-12 col-md-5 col-sm-6">
						<textarea rows="6" name="lang[<?=$v;?>][tresc]" id="tresc" class="form-control" placeholder="Wprowadź opis do oferty"><?php echo $lang[$v]->tresc; ?></textarea>
					</div>
				</div>

			</div></div></div>
		<? } 
	?>
	<div class="form-group center" id="saveGroup">
		<div class="col-xs-12 col-sm-offset-4 col-md-offset-3 col-sm-6 col-md-5">
			<?php if (!empty($dane->id)) { ?>
			<a data-confirm="Czy usunąć wybraną ofertę?" href="<?php url('oferty/usunOferte/' . $dane->id); ?>" class="confirm btn btn-md btn-danger">
			<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>usuń ofertę</a>
			<? } ?>
			<button	name="send" id="send" class="btn btn-md btn-success" type="submit">zapisz ofertę</button>
		</div>
	</div>
	</form>



	<?php if (!empty($img_form)) { ?>
	<div class="panel panel-default">
    		<div class="panel-heading" role="tab" id="himg">
      		<h4 class="panel-title">
        	<a data-toggle="collapse" data-parent="#accordion" class="addHash" href="#zdjecia" aria-expanded="true" aria-controls="zdjecia">
          		Zarządzanie zdjęciami
        	</a></h4></div>

    		<div id="zdjecia" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="himg">
			<div class="panel-body">
			<h4 class="b center">Przed dodaniem zdjęć ZAPISZ OFERTĘ</h4>
			<?php echo $img_form; ?>

			<div class="center margin-top-20">Aby móc zarządzać nowo dodanymi zdjęciami musisz <a href="" class="u">przeładować stronę</a>
			<div></div>UWAGA! Strona zostanie automatycznie przeładowana po dodaniu wszystkich plików!</div>
			<div class="sep10"></div>

			<?php if (!empty($images)) { ?>
			<form id="galeria_kolejnosc">	
				<h3 class="center">Przeciągaj i upuszczaj zdjęcia, aby je uporządkować</h3>

				<h4 class="center b margin-top-20">Uwaga! Pierwsze zdjęcie w galerii to miniaturka oferty widoczna na liście ofert!</h4>

				<ul class="galeria" id="galeria_sort">
					<?php
					$id = $_GET['id'];
					$i = 1;
					foreach ($images as $k => $v) {
						?>
						<li class="zdjecie" id="item-<?php echo $v->id; ?>">
							<img src="<?php echo IMAGES_URL . 'min/' . ( ($id + 1) % 100 ) . '/' . $v->url; ?>" />
							<a class="deleteImg" data-id="item-<?php echo $v->id; ?>" data-confirm="Czy na pewno chcesz usunąć to zdjęcie?" href="<?php url('oferty/usunZdjecie/' . $v->id); ?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
						</li>
						<?
					}
					?>
				</ul>
				<div class="sep20"></div>
				<div class="center">
					<button type="button" class="btn btn-success" id="zapiszKolejnosc">zapisz kolejność galerii</button>
				</div>
			</form>
		<? } ?>

		</div></div></div>

	<? } ?>

</div>