<?php
	include("connection.php");
	include("beleptarolo.php");
	if(!$_POST["username"] or !$_POST["password"]) $text = "Adja meg a belépési adatait!";
		if(isset($text))
		{
			print "
				<HTML>
					<HEAD>
						<TITLE>
							Regisztráció
						</TITLE>
					</HEAD>
				<BODY bgcolor='lightblue'>
					<A href='indexgame.php'><CENTER><H1>Vissza</H1></CENTER></A>
					<TABLE border='1' align='center' width='70%'>
			";
			head(3);
			bejelentkezes(3, "$text");
			print "
				</TABLE>
				</BODY>
				</HTML>
			";
		}
	if(isset($_POST["username"]) and isset($_POST["password"]) and !isset($text))
	{
		print "éljen";
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["password"] = $_POST["password"];
		header("Location:login.php");
	}
	
	
	
	
?>