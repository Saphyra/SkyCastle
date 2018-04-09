<?php
	include("connection.php");
	include("interfacetarolo.php");
	include("userkarbantarto.php");
	
	if(!isset($_SESSION["karakterazonosito"])) die("A karakter nem azonosítható");
	$_SESSION["vasarlas"] = 0;
	$id = $_SESSION["karakterazonosito"];
	
	if(isset($_POST["felsz"]))
	{
		$active = $_POST["felsz"];
		if(!$felszupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET activefelszereles='$active' WHERE karakterazonosito='$id'")) die("Felszerelésálasztás siekrtelen");
	}
	
	if(!$kasztleker = mysqli_query($_SESSION["conn"], "SELECT kaszt FROM users WHERE karakterazonosito='$id'")) die("Kaszt lekérése sikertelen");
	foreach($kaszttomb = mysqli_fetch_row($kasztleker) as $kaszt) $_SESSION["kaszt"] = $kaszt;
	
	if(!$actualleker = mysqli_query($_SESSION["conn"], "SELECT maxhp, actualhp, maxshield, actualshield, maxmana, actualmana, activeability0, reloadability0, activeability1, reloadability1, hppotactive, hppotreload, shieldpotactive, shieldpotreload, manapotactive, manapotreload, empreload, ishreload, pldactive, pldreload FROM users WHERE karakterazonosito='$id'")) die("Aktuális karakterállás lekérdezése sikertelen");
	foreach($actualtomb = mysqli_fetch_assoc($actualleker) as $name=>$ertek)
	{
		$actual["$name"] = $ertek;
	}
	karakterreset($id, $actual);
	karbantart($_SESSION["karakterazonosito"], $_SESSION["kaszt"], "users")
	
?>
<HTML>
	<HEAD>
		<TITLE>
			SkyCastle - Az űr háborúja
		</TITLE>
	</HEAD>
<BODY bgcolor='lightblue'>
	<?php head($_SESSION["karakterazonosito"], $_SESSION["username"]); ?>
	<?php user($_SESSION["karakterazonosito"]); ?>
	
	<CENTER><A href='shop.php'><H1>Bolt</H1></A></CENTER>
	<CENTER><A href='databaseclean.php'><H1>Irány a harctér!</H1></A></CENTER>
</BODY>
</HTML>