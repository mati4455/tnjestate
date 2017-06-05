				<?php if (logged) { ?></div><? } ?>
			</div>
		
			<div class="row footer">
				<div class="col-xs-12">
					<?php $d = (date('Y') == 2015) ? '' : '-'.date('Y'); ?>
					<div id="copyright" class="left">
						<div class="center">Copyright 2015<?php echo $d; ?> - Wszelkie prawa zastrzeżone</div>
						<?php // <div class="floatright">Created by <a href="mailto:mateuszpacholec@gmail.com">Mateusz Pacholec</a></div> ?>
					</div>					
				</div>
			</div>
		</div>
	</div>

	<!-- scripts -->
	<?php //echo $styles; ?>	
	<?php echo $javascripts; ?>
</body>
</html>
