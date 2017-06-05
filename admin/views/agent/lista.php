<h1>Lista agentów</h1>

<ul class="lista">
<?php
	foreach ($dane as $key => $value) {
		echo '<li><a href="'.get_url('agent/edytuj/' . $value->id).'">' . $value->imie_nazwisko . '</a></li>';
	}
?>
</ul>