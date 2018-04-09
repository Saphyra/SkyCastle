<?php
	include ("connection.php");
	include("karaktergenerate.php");
	include("mobgenerate.php");
	$id = $_SESSION["karakterazonosito"];
	if(!$tablaurites = mysqli_query($_SESSION["conn"], "TRUNCATE npcs")) die("Táblák ürítése sikertelen");
	if(!$tablaurites = mysqli_query($_SESSION["conn"], "TRUNCATE battle")) die("Táblák ürítése sikertelen");
	if(!$karakterfeltolt = mysqli_query($_SESSION["conn"], "INSERT INTO battle (karakterazonosito, target, attack, ammo) VALUES ('$id', '0', '0', 'x1')")) die("Karakter feltöltése sikertelen");
	unset($_SESSION["log"]);
	
	
	if(!isset($_POST["minigame"]))
	{
		$_SESSION["gamemode"] = "normal";
		$_SESSION["gamemodeget"] = 0;
	}
	if(isset($_POST["minigame"]))
	{
		$_SESSION["gamemode"] = $_POST["minigame"];
		$_SESSION["gamesession"] = 0;
		$_SESSION["gamemodeget"] = 0;
	}
	
	header("Location:battlefield.php");
?>