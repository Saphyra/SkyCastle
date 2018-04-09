<?php
	include("connection.php");
	if(isset($_POST["submit"]))
	{
		if($_POST["submit"] == "Adatok megváltoztatása")
		{
			if(!$_POST["regifelhasznalonev"] or !$_POST["regijelszo"] or !$_POST["ujfelhasznalonev"] or !$_POST["ujjelszo1"] or !$_POST["ujjelszo2"])
			{
				print "
					<HTML>
						<HEAD>
							<TITLE>Adatok megadása sikertelen</TITLE>
						</HEAD>
					<BODY bgcolor='lightblue'>
						<CENTER><H1>Adatok megadása sikertelen!</H1></CENTER>
				";
				adatbekeres();
			}
			elseif($_POST["ujjelszo1"] != $_POST["ujjelszo2"])
			{
				print "
					<HTML>
						<HEAD>
							<TITLE>Adatok megadása sikertelen</TITLE>
						</HEAD>
					<BODY bgcolor='lightblue'>
						<CENTER><H1>Új jelszavak nem egyeznek!</H1></CENTER>
				";
				adatbekeres();
			}
			else adatvaltoztat($_SESSION["karakterazonosito"], $_POST["regifelhasznalonev"], $_POST["regijelszo"], $_POST["ujfelhasznalonev"], $_POST["ujjelszo1"], $_POST["ujjelszo2"]);
		}
		if($_POST["submit"] == "Karakter törlése") karaktertorles($_SESSION["karakterazonosito"], $_POST["regifelhasznalonev"], $_POST["regijelszo"]);
	}
	if(!isset($_POST["submit"]))
	{
		print "
			<HTML>
				<HEAD>
					<TITLE>Adatok megadása sikertelen</TITLE>
				</HEAD>
			<BODY bgcolor='lightblue'>
		";
		adatbekeres();
	}
	
	function adatbekeres()
	{
		print "

				<A href='startpage.php'><H2>Vissza</H2></A>
				<TABLE border='1' align='center' width='60%'>
					<FORM method='POST' action='beallitasok.php'>
					<TR>
						<TD align='center'colspan='3'><H1>Felhasználónév és jelszó megváltoztatása</H1></TD>
					</TR>
					<TR>
						<TD colspan='2' align='center'>Régi felhasználónév:<INPUT type='text' name='regifelhasznalonev'></TD>
						<TD align='center'>Régi jelszó:<INPUT type='password' name='regijelszo'></TD>
					</TR>
					<TR>
						<TD>Új felhasználónév:<INPUT type='text' name='ujfelhasznalonev'></TD>
						<TD>Új jelszó:<INPUT type='password' name='ujjelszo1'></TD>
						<TD>Új jelszó újra:<INPUT type='password' name='ujjelszo2'></TD>
					</TR>
					<TR>
						<TD align='center' colspan='3'><INPUT type='submit' name='submit' value='Adatok megváltoztatása'></TD>
					</TR>
					</FORM>
				</TABLE>
				<BR>
				<TABLE align='center' border='1' width='60%'>
					<FORM method='POST' action='beallitasok.php'>
					<TR>
						<TD colspan='2' align='center'><H1>Karakter törlése</H1></TD>
					</TR>
					<TR>
						<TD width='50%' align='center'>Felhasználónév:<INPUT type='text' name='regifelhasznalonev'></TD>
						<TD width='50%' align='center'>Jelszó:<INPUT type='password' name='regijelszo'></TD>
					</TR>
					<TR>
						<TD align='center' colspan='2'><INPUT type='submit' name='submit' value='Karakter törlése'></TD>
					</TR>
					</FORM>
				</TABLE>
			</BODY>
			</HTML>
		";
	}
	
	function adatvaltoztat($id, $regifelhasznalonev, $regijelszo, $ujfelhasznalonev, $ujjelszo1, $ujjelszo2)
	{
		if(!$felhasznalonevlekeres = mysqli_query($_SESSION["conn"], "SELECT username, password FROM users WHERE karakterazonosito='$id'")) die("Felhasználónév és jelszó lekérése sikertelen");
		$useradatok = mysqli_fetch_assoc($felhasznalonevlekeres);

		$felhasznalonev = $useradatok["username"];
		$jelszo = $useradatok["password"];
		
		if($regifelhasznalonev != $felhasznalonev or $regijelszo!= $jelszo)
		{
			print "
				<HTML>
					<HEAD>
						<TITLE>Adatok megadása sikertelen</TITLE>
					</HEAD>
				<BODY bgcolor='lightblue'>
					<CENTER><H1>Felhasználónév és jelszó kombinációja ismeretlen!</H1></CENTER>
			";
			adatbekeres();
			return 1;
		}
		
		if($update = mysqli_query($_SESSION["conn"], "UPDATE users SET username='$ujfelhasznalonev', password='$ujjelszo1' WHERE karakterazonosito='$id'"))
		{
			print "
					<HTML>
						<HEAD>
							<TITLE>Adatok megváltoztatása sikeres</TITLE>
						</HEAD>
					<BODY bgcolor='lightblue'>
						<A href='startpage.php'><H2>Vissza</H2></A>
						<CENTER><H1>Adatok megváltoztatása sikeres!</H1></CENTER>
					</BODY>
					</HTML>
			";
		}
	}
	
	function karaktertorles($id, $regifelhasznalonev, $regijelszo)
	{
		if(!$felhasznalonevlekeres = mysqli_query($_SESSION["conn"], "SELECT username, password FROM users WHERE karakterazonosito='$id'")) die("Felhasználónév és jelszó lekérése sikertelen");
		$useradatok = mysqli_fetch_assoc($felhasznalonevlekeres);

		$felhasznalonev = $useradatok["username"];
		$jelszo = $useradatok["password"];
		
		if($regifelhasznalonev != $felhasznalonev or $regijelszo!= $jelszo)
		{
			print "
				<HTML>
					<HEAD>
						<TITLE>Adatok megadása sikertelen</TITLE>
					</HEAD>
				<BODY bgcolor='lightblue'>
					<CENTER><H1>Felhasználónév és jelszó kombinációja ismeretlen!</H1></CENTER>
			";
			adatbekeres();
			return 1;
		}
			if(!$torol = mysqli_query($_SESSION["conn"], "DELETE FROM users WHERE karakterazonosito='$id'")) die("Felhasználó törlése sikertelen");
			else header("location:indexgame.php");
	}
?>