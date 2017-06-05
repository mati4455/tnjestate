<div class="search-align home-box col-xs-12 col-sm-7 col-md-7 col-lg-8">
	<div class="col-md-10 col-md-offset-1 ">
		<a href="<? lang_url('oferty/sprzedaz'); ?>">
			<div class="piktogram dynamic-hover spr"><div><div></div></div>
				<h3><?=mb_strtoupper($texts['sprzedaz'], 'UTF-8'); ?></h3>
				<img alt="sprzedaz" src="<?php echo url('assets/img/home/sprzedaz.jpg'); ?>" />
			</div>
		</a>
		<a href="<? lang_url('oferty/wynajem'); ?>">
			<div class="piktogram wyn"><div><div></div></div>
			<h3><?=strtoupper($texts['wynajem']); ?></h3>
				<img alt="wynajem" src="<?php echo url('assets/img/home/wynajem.jpg'); ?>" />
			</div>
		</a>
		<a href="<? lang_url('oferty/specjalne'); ?>">
			<div class="piktogram kom"><div><div></div></div>
			<h3><?=strtoupper($texts['tylkounas']); ?></h3>
				<img alt="tylko u nas" src="<?php echo url('assets/img/home/tylkounas.jpg'); ?>" />
			</div>
		</a>
	</div>
	
</div>	

<?php echo $search_form; ?> 
