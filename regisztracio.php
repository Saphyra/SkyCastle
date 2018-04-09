<?php
	include("connection.php");
	include("beleptarolo.php");
	if(!$_POST["felhasznalonev"] or !$_POST["jelszo1"] or !$_POST["jelszo2"]) $text = "Adatok megadása sikertelen.";
	$felhasznalonevadott = $_POST["felhasznalonev"];
	
	if(!$lekeres = mysqli_query($_SESSION["conn"], "SELECT username FROM users WHERE username='$felhasznalonevadott'")) die("A felhasználónév lekérése sikertelen");
	$felhasznalonev = 1;
	while($leker = mysqli_fetch_row($lekeres))
	{
		foreach($leker as $ertek)
		{
			$felhasznalonev = $ertek;
		}
	}
	if($felhasznalonevadott == $felhasznalonev) $text = "A felhasználónév foglalt! Válasszon másikat!";
	
	if($_POST["jelszo1"] != $_POST["jelszo2"]) $text = "A jelszavak nem egyeznek. Írja be őket újra!";
	
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
		regisztracio("$text");
		print "
			</TABLE>
			</BODY>
			</HTML>
		";
	}
	
	if(!isset($text))
	{
		$username = $_POST["felhasznalonev"];
		$password = $_POST["jelszo1"];
		print "
			<HTML>
				<HEAD>
					<TITLE>
						Kaszt kiválasztása
					</TITLE>
				</HEAD>
			<BODY bgcolor='lightblue'>
				<A href='indexgame.php'><CENTER><H1>Vissza</H1></CENTER></A>
				<TABLE border='1' align='center' width='70%'>
		";
		head(4);
		print "
			<TR>
				<TD align='center' colspan='4'>
					<H1>Válassza ki, melyik kaszttal szeretne játszani!</H1>
				</TD>
			</TR>
			<TR>
				<TD align='center'>
				<FORM method='POST' action='karaktergenerate.php'>
					<INPUT type='hidden' name='username' value='$username'>
					<INPUT type='hidden' name='password' value='$password'>
					<INPUT type='hidden' name='kaszt' value='warrior'>
					<INPUT type='submit' name='warrior' value='Warrior'>
				</FORM>
				</TD>
				<TD align='center'>
				<FORM method='POST' action='karaktergenerate.php'>
					<INPUT type='hidden' name='username' value='$username'>
					<INPUT type='hidden' name='password' value='$password'>
					<INPUT type='hidden' name='kaszt' value='mage'>
					<INPUT type='submit' name='mage' value='Mágus'>
				</FORM>
				</TD>
				<TD align='center'>
				<FORM method='POST' action='karaktergenerate.php'>
					<INPUT type='hidden' name='username' value='$username'>
					<INPUT type='hidden' name='password' value='$password'>
					<INPUT type='hidden' name='kaszt' value='paladin'>
					<INPUT type='submit' name='paladin' value='Paladin'>
				</FORM>
				</TD>
				<TD align='center'>
				<FORM method='POST' action='karaktergenerate.php'>
					<INPUT type='hidden' name='username' value='$username'>
					<INPUT type='hidden' name='password' value='$password'>
					<INPUT type='hidden' name='kaszt' value='healer'>
					<INPUT type='submit' name='healer' value='Healer'>
				</FORM>
				</TD>
			</FORM>
			</TR>
		";
		print "
			</TABLE>
			</BODY>
			</HTML>
		";
	}
	
		
?>