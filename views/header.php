<?php
	if (!isSet($subtitle)) $subtitle = $title; 
	$c_title = empty($title) ? $texts['#TYTUL_STRONY#'] : $title . ' | ' . $texts['#TYTUL_STRONY#'];

	$c_description = empty($description) ? $texts['#OPIS_STRONY#'] :
		Core::getTitle(str_replace(Array("\n", '<br>', '<br />'), ' ', $description), 180);

	$c_keywords = empty($keywords) ? $texts['#SLOWA_KLUCZOWE#'] : $keywords;
	
?>

<!doctype html>
<html lang="<?php echo LANG; ?>">
<head>
	<meta charset="UTF-8">
	<meta name="author" content="" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	
	<title><?php echo $c_title; ?> </title>
	
	<meta name="description" content="<?php echo $c_description; ?>" />
	<meta name="keywords" content="<?php echo $c_keywords; ?>" />

	<link href="<?php echo get_url('assets/img/logo.png'); ?>" rel="icon" type="image/x-icon" />
	<?php echo $styles; ?>	
</head>
<body>
	<div id="body">

		<div id="content" class="container">			
			<div id="header">
				<div class="row relative">
					<div class="col-xs-12">
						<div class="menu-langs">
							<?php
								foreach ($langs as $k => $v) {
									$sel = LANG == $v ? 'active' : '';
									$prefix = $v == DEFAULT_LANG ? '' : '/' . $v ;
									$link = str_replace('en/', '', $_SERVER['PATH_INFO']);
									echo '<a rel="nofollow" class="dynamic-hover '.$v.' '.$sel.'" href="'. URL_WEBSITE . $prefix . $link . '"></a>';
								}
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-2">
						<a class="block logo_link" href="<?php echo lang_url(); ?>">
							<img alt="logo" src="<?php url('assets/img/logo.png'); ?>" />
						</a>
					</div>
				</div> 
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-7">
						<?php if (!Core::isHome()) Core::print_menu($menu_top, 'menu-top', true); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12" id="komunikat"><?php Core::showMessage($message); ?></div>
			</div>

			<div class="margin-top-30"></div>
