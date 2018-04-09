<?php
	include("connection.php");
	include("beleptarolo.php");
	
	if(!isset($_SESSION["username"]) or !isset($_SESSION["password"]))
	{
		session_unset();
		header("Location:loginset.php");
	}
	if(isset($_SESSION["username"]) and isset($_SESSION["password"]))
	{
		$pwa = 0;
		$username = $_SESSION["username"];
		$password = $_SESSION["password"];
		if(!$passwordleker = mysqli_query($_SESSION["conn"], "SELECT password FROM users WHERE username='$username' AND password='$password'")) die("Jelszó lekérése sikertelen");
		while($jelszo = mysqli_fetch_row($passwordleker))
		{
			foreach($jelszo as $ertek)
			{
				$pwa = $ertek;
			}
		}
		if($pwa)
		{
			$_SESSION["password"] = "";
			if(!$idleker = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM users WHERE username='$username'")) die("Karakterazonosító lekérdezése sikertelen");
			foreach($id = mysqli_fetch_row($idleker) as $karakterazonosito)
			
			$_SESSION["karakterazonosito"] = $karakterazonosito;
			header("Location:startpage.php");
		}
		else
		{
			$text = "A felhasználónév és jelszó kombinációja ismeretlen";
			$_SESSION["password"] = "";
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
			head(1);
			
			print "
				<TR>
					<TD align='center'>
						<H3>$text</H3>
					</TD>
				</TR>
				<TR>
					<TD align='center'>
						<A href='indexgame.php'><H3>VISSZA</H3></A>
					</TD>
				</TR>
				</TABLE>
				</BODY>
				</HTML>
			";
		}
	}
	
?>