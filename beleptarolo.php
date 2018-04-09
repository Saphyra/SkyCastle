<?php
	include("connection.php");
	function regisztracio($regtext)
	{
		print "
			<TR>
				<TD align='center' colspan='3'>
					<H1>$regtext</H1>
				</TD>
			</TR>
			<FORM method='POST' action='regisztracio.php'>
			<TR>
				<TD align='center'>
					<H3>Felhasználónév: <INPUT type='text' name='felhasznalonev'></H3>
				</TD>
				<TD align ='center'>
					<H3>Jelszó: <INPUT type='password' name='jelszo1'></H3>
				</TD>
				<TD align ='center'>
					<H3>Jelszó újra: <INPUT type='password' name='jelszo2'></H3>
				</TD>
			</TR>
			<TR>
				<TD align='center' colspan='3'>
					<INPUT type='submit' name='submit' value='Regisztráció'>
				</TD>
			</TR>
			</FORM>
		";
	}
	
	function head($colspan)
	{
		print "
			<TR>
				<TD align='center' colspan='$colspan'>
					<IMG src='udv.png' width='100%'>
				</TD>
			</TR>
		";
	}
	
	function bejelentkezes($colspan, $text)
	{
		print"
			<TR>
				<TD align='center' colspan='$colspan'>
					<H1>$text</H1>
				</TD>
			</TR>
			<FORM method='POST' action='loginset.php'>
			<TR>
				<TD align='center'>
					<H3>Felhasználónév: <INPUT type='text' name='username'></H3>
				</TD>
				<TD align='center'>
					<H3>Jelszó: <INPUT type='password' name='password'></H3>
				</TD>
				<TD align='center'>
					<INPUT type='submit' name='submit' value='Bejelentkezés'>
				</TD>
			</TR>
			</FORM>
		";		
	}
	
?>