<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/variables-functions.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/language.php";

?><footer>
	<p>
	<?php echo '&copy; '
			 . date("Y",time()).' '
			 .'<a href="https://'.$lang['domain'][$l].'">'.$lang['domain'][$l].'</a>'
			 .'<a href="/Impressum/">Impressum/ Kontakt</a>'
			 .'<a href="/datenschutzerklärung">Datenschutzerklärung</a>';
	?>
	</p>
	
	
</footer>
