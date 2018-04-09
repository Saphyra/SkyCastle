<?php
	include("connection.php");
	include("interfacetarolo.php");
?>
<HTML>
	<HEAD>
		<TITLE>SkyCastle - Bolt</TITLE>
	</HEAD>
<BODY bgcolor='lightblue'>
	<?php head($_SESSION["karakterazonosito"], $_SESSION["username"]); ?>
	<CENTER><H2><A href='startpage.php'>Vissza</A></H2></CENTER>

<?php 
	if($_SESSION["vasarlas"])
	{
		print "<CENTER><H1>A vásárlás sikeres!</H1></CENTER>";
		$_SESSION["vasarlas"] = 0;
	}
	
	$id = $_SESSION["karakterazonosito"];
	
	if(!$amoneyleker = mysqli_query($_SESSION["conn"], "SELECT actualmoney FROM users WHERE karakterazonosito='$id'")) die("Játékos pénzének lekérése sikertelen");
	foreach($money = mysqli_fetch_row($amoneyleker) as $actualmoney)

	shop($actualmoney, $_SESSION["karakterazonosito"], $_SESSION["kaszt"]);
?>
<CENTER><H2><A href='startpage.php'>Vissza</A></H2></CENTER>

</BODY>
</HTML>