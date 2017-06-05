			
		
			<div class="row footer">
				<div class="footer_resizer col-xs-12">
					<?php $d = (date('Y') == 2015) ? '' : '-'.date('Y'); ?>
				<div class="sep30"></div>
					<div id="copyright" class="left">
						<div class="pull-left"><?php Core::print_menu($menu_bottom, 'menu-footer'); ?></div>
						<div class="pull-right phone"><span class="orange glyphicon glyphicon-earphone"></span><?php echo $texts['#TELEFON#']; ?></div>
						<div class="clearboth"></div>
						<?php // <div class="floatright">Created by <a href="mailto:mateuszpacholec@gmail.com">Mateusz Pacholec</a></div> ?>
					</div>		
				</div>
			</div>
		</div>
		
		<?php if ($_COOKIE['cookies'] != 1): ?>
			<div class="container" id="cookies"><div class="row">
				<p><?php echo $texts['#COOKIES_TEXT#']; ?></p>
				<div class="nav">
					<a href="/cookies/zgoda" id="cookies_accept">Zgadzam się</a>
					<a href="/cookies/wychodze">Wychodzę</a>
				</div>
			</div></div>
		<?php endif; ?>

		<div class="hr bottom-line"></div>

		<div class="container margin-top-10 margin-bottom-10">
			<div class="row">
				<div class="col-xs-12 right">
					Copyright 2015<?php echo $d . ' - ' . $texts['copyright']; ?>
				</div>
			</div>
		</div>

	</div>


	
	<!-- scripts -->
	<?php //echo $styles; ?>	
	<?php echo $javascripts; ?>
	<?php echo $google_analytics; ?>
</body>
</html>
