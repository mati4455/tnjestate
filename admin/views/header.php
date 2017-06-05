<!doctype html>
<html lang="pl">
<head>
	<meta name="author" content="" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	
	<title><?php if (!isSet($subtitle)) $subtitle = $title; if (empty($title)) echo TYTUL_STRONY; else echo $title . ' - ' . TYTUL_STRONY; ?></title>
	<meta name="description" content="<?php echo empty($opis) ? DEFAULT_DESCRIPTION : str_replace( Array("\n", '<br />', '<br>'), ' ', $opis); ?>">

	<link href="/assets/img/logo.png" rel="icon" type="image/x-icon" />
	<?php echo $styles; ?>	
</head>
<body>
	<div id="body">
		<!-- fixed divs -->
		<div id="hidden"></div>
		<div id="info"></div>
		<div id="progress"></div>
		<div id="box_podglad"></div>
		<div class="modal fade" id="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
				</div>
			</div>
		</div>
		<!-- end of fixed divs -->

		<div id="content" class="container">	
		<?php if (logged === true): ?>			
			<div class="row"><div class="col-xs-12">
				<nav id="menu_nav" class="navbar navbar-default navbar-fixed-top center">
				  <div class="container">
				  	<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
					  	</button>
				 		<a class="visible-xs navbar-brand" href="#">Menu</a>
					</div>
					<div class="collapse navbar-collapse" id="navigation">
						<ul class="nav navbar-nav">	
							<li><a href="/"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> TNJ</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Oferty <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">			
									<li><a href="<?php url('oferty'); ?>">Lista ofert</a></li>	
									<li><a href="<?php url('oferty/dodaj'); ?>">Dodaj nową</a></li>
								</ul>
							</li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Agenci <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php url('agent'); ?>">Lista agentów</a>	
									<li><a href="<?php url('agent/nowy'); ?>">Dodaj nowego</a></li>
								</ul>
							</li>

							<li><a href="<?php url('strony'); ?>">Strony statyczne</a></li>

							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Lokalizacje <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php url('lokalizacje'); ?>">Lista lokalizacji</a>	
									<li><a href="<?php url('lokalizacje/miasto'); ?>">Dodaj miasto</a></li>
									<li><a href="<?php url('lokalizacje/osiedle'); ?>">Dodaj osiedle</a></li>
								</ul>
							</li>

							<li><a href="<?php url('teksty'); ?>">Teksty</a></li>

							<li class="dropdown last">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Ustawienia <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="<?php url('konto/haslo'); ?>">Zmiana hasła</a></li>
									<li><a href="<?php url('ustawienia/tlo'); ?>">Zmiana tła</a></li>
								</ul>
							</li>
						</ul>
						<div class="pull-right" style="line-height: 50px;">
							<div class="static inline-block">Witaj <span class="b"><?php echo Session::get('user_id'); ?></span></div>
							<a class="lastElement" href="<?php echo url('autoryzacja/wyloguj'); ?>">Wyloguj</a>
						</div>
					 </div>
					<!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav> 
			</div></div>
			<?php endif; ?>


			<div class="row">
				<div class="col-xs-12" id="komunikat"><?php Core::showMessage($message); ?></div>
			</div>

			<div class="row margin-top-30">
				<?php if (logged) { echo $search_form; ?>

				<div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">

				<? } ?>