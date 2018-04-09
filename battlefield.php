<?php
	include("connection.php");
	include("battlefieldtarolo.php");
	
	$id = $_SESSION["karakterazonosito"];
	if(!$eletlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM battle WHERE karakterazonosito='$id'")) die("Lekérés sikertelen");
	if(!$elet = mysqli_num_rows($eletlekeres)) header("location:meghaltal.php");
	
	
	if(!$enemylekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='1' or enemy='mob' ")) die("Elleek lekérése sikertelen");
	if(!$sorszam = mysqli_num_rows($enemylekeres))
	{
		$gyozelem = "<CENTER><H1>Győzelem!<A href='startpage.php'>VISSZA</A></H1></CENTER>";
	}
	
	if(!$battlelekeres = mysqli_query($_SESSION["conn"], "SELECT lastattack FROM battle WHERE karakterazonosito='$id'")) die("lastattack lekérése sikertelen");
	foreach($battletomb = mysqli_fetch_row($battlelekeres) as $lastattack);
	
	if(!$alliedszamlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM npcs WHERE enemy='0'")) die("barátok számának lekérése siekrtelen");
	$alliedszam = mysqli_num_rows($alliedszamlekeres);
	if(!$enemyszamlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM npcs WHERE enemy='1'")) die("barátok számának lekérése siekrtelen");
	$enemyszam = mysqli_num_rows($enemyszamlekeres);
	if(!$mobszamlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM npcs WHERE enemy='mob'")) die("barátok számának lekérése siekrtelen");
	$mobszam = mysqli_num_rows($mobszamlekeres);
?>
<HTML>
	<HEAD>
		<TITLE>SkyCastle - Az űr háborúja</TITLE>
	</HEAD>
<BODY bgcolor='lightblue'>
	<?php
		if(!$lastattack and $_SESSION["gamemodeget"])
		{
			$gamemodeget = $_SESSION["gamemodeget"];
			print "
				<FORM method='POST' action='databaseclean.php'>
				<CENTER><H1>Minijáték indít: <INPUT type='submit' name='minigame' value='$gamemodeget'></H1></CENTER>
				</FORM>
			";
		}
	?>
	<FORM method='POST' action='shell.php'>
	<?php if(isset($gyozelem)) print $gyozelem; ?>
	<TABLE align='center'>
		<TR><TD><INPUT type='submit' name='submit' value='Következő kör!'></TD></TR>
		<TR><TD align='center'><INPUT type='checkbox' name='tamadas' value='1' checked='checked'>Lövés</TD></TR>
	</TABLE>
	<TABLE align='center'>
	
		<TR>
			<TD valign='top'>
				<CENTER><H1>Játékos:</H1></CENTER>
				<?php usertable($_SESSION["karakterazonosito"]); ?>
			</TD>
			<TD valign='top'>
				<TABLE>
					<TR>
						<TD align='center'><H1>Szövetségesek:</H1></TD>
					</TR>
					<?php npctable(0, $_SESSION["karakterazonosito"]); ?>
				</TABLE>
			</TD>
			<TD valign='top'>
				<TABLE>
					<TR>
						<TD align='center'><H1>Ellenségek:</H1></TD>
					</TR>
					<?php 
						if($enemyszam) npctable(1, $_SESSION["karakterazonosito"]);
						if($mobszam) mobtable($_SESSION["karakterazonosito"]);
					?>
				</TABLE>
			</TD>
		</TR>
	</TABLE>
	<HR>
	<TABLE align='center'><TR><TD><INPUT type='submit' name='submit' value='Következő kör!'></TD></TR></TABLE>
	</FORM>
	<?php
		if(isset($_SESSION["log"]))
		{
			 $log = $_SESSION["log"];
			foreach($log as $logbejegyzes) print $logbejegyzes."<BR>";
			unset($_SESSION["log"]);
		}
	?>
		
		
		
			
			
			

</BODY>
</HTML>