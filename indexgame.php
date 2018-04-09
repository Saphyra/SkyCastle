<?php
	if(isset($_SESSION["conn"])) session_unset();
	include("connection.php");
	include("beleptarolo.php");
?>
<HTML>
	<HEAD>
		<TITLE>
			Üdvözöljük a SkyCastle oldalán!
		</TITLE>
	</HEAD>
<BODY bgcolor='lightblue'>
	<TABLE border='1' align='center' width='70%'>
		<?php head(3); ?>
		<?php bejelentkezes(3, "Bejelentkezés:"); ?>
		<?php $reg = "Regisztráció:"; regisztracio($reg); ?>
	</TABLE>
</BODY>
</HTML>