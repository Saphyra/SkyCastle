<?php
	include("connection.php");
	
	function mobtable($id)
	{
		print "
			<TR>
				<TD>
		";
		if(!$karakterlekerdezes = mysqli_query($_SESSION["conn"], "SELECT * FROM npcs WHERE enemy='mob' ORDER BY kulcs DESC")) die("Mobok beolvasása sikertelen");
		
		if(!$celpontlekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE karakterazonosito='$id'")) die("Játékos célpontjának lekérése sikertelen");
		foreach($celponttomb = mysqli_fetch_row($celpontlekeres) as $targeted);
		
		while($karaktertomb = mysqli_fetch_assoc($karakterlekerdezes))
		{
			foreach($karaktertomb as $nev=>$ertek) $$nev = $ertek;
			
			print "<TABLE border='1' align='center' width='390'>";			
			
			$hpszazalek = $actualhp/$maxhp*200;
			settype($hpszazalek, "integer");
			$hplost = 200-$hpszazalek;
			$hp = $hpszazalek/2;
			
			$shieldszazalek = $actualshield/$maxshield*200;
			settype($shieldszazalek, "integer");
			$shieldlost = 200-$shieldszazalek;
			$shield = $shieldszazalek/2;
			
			$checked = "";
			
			if($targeted == $karakterazonosito) $checked = "checked='checked'";
			
			$celpont = "";
			if(!$targetlekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE karakterazonosito='$karakterazonosito'")) die("Karakter célpontjának lekérése sikertelen");
			foreach($targettomb = mysqli_fetch_row($targetlekeres) as $target)
			if($target) $celpont = "Célpont: $target";
			if($activefelszereles) $activefelszereles = "Agresszív";
			else $activefelszereles = "Békés";
			print "
				<TR>
					<TD align='center'>
				";
				if($enemy) print "<INPUT type='radio' $checked name='target' value='$karakterazonosito'>";
					
				print "
				$karakterazonosito
				 </TD>
					<TD align='center'>Lvl $karakterszint $kaszt ($activefelszereles)</TD>
					<TD align='center'>$celpont</TD>
				</TR>
				<TR>
					<TD align='right'>Életerő:</TD>
					<TD colspan='2'><IMG src='pixelhp.bmp' height='10' width='$hpszazalek'><IMG src='pixelblack.bmp' height='10' width='$hplost'> ($hp%)</TD>
				</TR>
				<TR>
					<TD align='right'>Pajzs:</TD>
					<TD colspan='2'><IMG src='pixelshield.bmp' height='10' width='$shieldszazalek'><IMG src='pixelblack.bmp' height='10' width='$shieldlost'> ($shield%)</TD>
				</TR>
				<TR>
					<TD align='center' colspan='3'>Sebzés: $actualdmg</TD>
				</TR>
			";
			if($activeability0 or $activeability1 or $hppotactive or $shieldpotactive or $manapotactive or $ishactive or $empactive or $pldactive)
			{
				print "
					<TR>
						<TD align='center' colspan='3'>Aktív:</TD>
					</TR>
					<TR>
						<TD colspan='3' align='justify'>
				";
				if($activeability0)
				{
					if(!$abilitynevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("Képességnév lekérése sikertelen");
					foreach($abilitynevtomb = mysqli_fetch_row($abilitynevlekeres) as $ability0);
					print "$ability0 ($activeability0)";
				}
				
				if($activeability1)
				{
					if(!$abilitynevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("Képességnév lekérése sikertelen");
					foreach($abilitynevtomb = mysqli_fetch_row($abilitynevlekeres) as $ability1);
					print "$ability1 ($activeability1)";
				}
				if($hppotactive) print "Életerőpoti ($hppotactive) ";
				if($shieldpotactive) print "Pajzspoti ($shieldpotactive) ";
				if($manapotactive) print "Manapoti ($manapotactive) ";
				if($ishactive) print "ISH ";
				if($empactive) print "EMP ";
				if($pldactive) print "PLD ($pldactive) ";
			}
			print "</TABLE>";
		}
		print "
				</TD>
			</TR>
		";
	}
	
	function npctable($enemy, $id)
	{
		print "
			<TR>
				<TD>
		";
		if(!$karakterlekerdezes = mysqli_query($_SESSION["conn"], "SELECT * FROM npcs WHERE enemy='$enemy' ORDER BY kulcs DESC")) die("Karakterek beolvasása sikertelen");
		
		if(!$celpontlekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE karakterazonosito='$id'")) die("Játékos célpontjának lekérése sikertelen");
		foreach($celponttomb = mysqli_fetch_row($celpontlekeres) as $targeted);
		
		while($karaktertomb = mysqli_fetch_assoc($karakterlekerdezes))
		{
			foreach($karaktertomb as $nev=>$ertek) $$nev = $ertek;
			
			if(!$ammolekeres = mysqli_query($_SESSION["conn"], "SELECT ammo, disability FROM battle WHERE karakterazonosito='$karakterazonosito'")) die("Lőszer lekérése sikertelen");
			foreach($ammotomb = mysqli_fetch_assoc($ammolekeres) as $name=>$ertek) $$name = $ertek;
			print "<TABLE border='1' align='center' width='390'>";			
			
			$hpszazalek = $actualhp/$maxhp*200;
			settype($hpszazalek, "integer");
			$hplost = 200-$hpszazalek;
			$hp = $hpszazalek/2;
			
			$shieldszazalek = $actualshield/$maxshield*200;
			settype($shieldszazalek, "integer");
			$shieldlost = 200-$shieldszazalek;
			$shield = $shieldszazalek/2;
			
			$manaszazalek = $actualmana/$maxmana*200;
			settype($manaszazalek, "integer");
			$manalost = 200-$manaszazalek;
			$mana = $manaszazalek/2;
			
			
			$alca = "NEM";
			if($cloaked) $alca = "IGEN";
			
			$checked = "";
			
			if($targeted == $karakterazonosito) $checked = "checked='checked'";
			
			$celpont = "";
			if(!$targetlekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE karakterazonosito='$karakterazonosito'")) die("Karakter célpontjának lekérése sikertelen");
			foreach($targettomb = mysqli_fetch_row($targetlekeres) as $target)
			if($target) $celpont = "Célpont: $target";
			print "
				<TR>
					<TD align='center'>
				";
				if($enemy) print "<INPUT type='radio' $checked name='target' value='$karakterazonosito'>";
					
				print "
				$karakterazonosito
				 </TD>
					<TD align='center'>Lvl $karakterszint $kaszt ($activefelszereles)</TD>
					<TD align='center'>$celpont</TD>
				</TR>
				<TR>
					<TD align='right'>Életerő:</TD>
					<TD colspan='2'><IMG src='pixelhp.bmp' height='10' width='$hpszazalek'><IMG src='pixelblack.bmp' height='10' width='$hplost'> ($hp%)</TD>
				</TR>
				<TR>
					<TD align='right'>Pajzs:</TD>
					<TD colspan='2'><IMG src='pixelshield.bmp' height='10' width='$shieldszazalek'><IMG src='pixelblack.bmp' height='10' width='$shieldlost'> ($shield%)</TD>
				</TR>
				<TR>
					<TD align='right'>Mana:</TD>
					<TD colspan='2'><IMG src='pixelmana.bmp' height='10' width='$manaszazalek'><IMG src='pixelblack.bmp' height='10' width='$manalost'> ($mana%)</TD>
				</TR>
				<TR>
					<TD align='center'>Álcázott: $alca</TD>
					<TD align='center' colspan='2'>Sebzés: $actualdmg (lőszer: $ammo)</TD>
				</TR>
			";
			
			if($activeability0 or $activeability1 or $hppotactive or $shieldpotactive or $manapotactive or $ishactive or $empactive or $pldactive)
			{
				print "
					<TR>
						<TD align='center' colspan='3'>Aktív:</TD>
					</TR>
					<TR>
						<TD colspan='3' align='justify'>
				";
				if($activeability0)
				{
					if(!$abilitynevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("Képességnév lekérése sikertelen");
					foreach($abilitynevtomb = mysqli_fetch_row($abilitynevlekeres) as $ability0);
					print "$ability0 ($activeability0)";
				}
				
				if($activeability1)
				{
					if(!$abilitynevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("Képességnév lekérése sikertelen");
					foreach($abilitynevtomb = mysqli_fetch_row($abilitynevlekeres) as $ability1);
					print "$ability1 ($activeability1)";
				}
				if($hppotactive) print "Életerőpoti ($hppotactive) ";
				if($shieldpotactive) print "Pajzspoti ($shieldpotactive) ";
				if($manapotactive) print "Manapoti ($manapotactive) ";
				if($ishactive) print "ISH ";
				if($empactive) print "EMP ";
				if($pldactive) print "PLD ($pldactive) ";
				if($disability) print "Disability ($disability) ";
			}
			print "</TD></TR></TABLE><BR><BR>";
		}
		print "
					</TD>
			</TR>
		";
	}
	
	function usertable($id)
	{
		print "
			<TABLE border='1' width='530'>
		";
		if(!$userlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM users WHERE karakterazonosito='$id'")) die("Karakteradatok lekérése sikertelen");
		$adatok = mysqli_fetch_assoc($userlekeres);
		foreach($adatok as $name=>$ertek)
		{
			$$name = $ertek;
		}
		if(!$battlelekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM battle WHERE karakterazonosito='$id'")) die("Karakteradatok lekérése sikertelen");
		$battleadatok = mysqli_fetch_assoc($battlelekeres);
		foreach($battleadatok as $name=>$ertek)
		{
			$$name = $ertek;
		}

		print "
			<TR>
				<TD align='center'>$username ($karakterazonosito)</TD>
				<TD align='center' colspan='2'>Lvl $karakterszint $kaszt</TD>
				<TD align='center'>$actualmoney</TD>
			</TR>
		";
		
		$hpszazalek = $actualhp/$maxhp*200;
		settype($hpszazalek, "integer");
		$hplost = 200-$hpszazalek;
		$hp = $hpszazalek/2;
		print "
			<TR>
				<TD align='right'>Életerő:</TD>
				<TD colspan='2'><IMG src='pixelhp.bmp' height='10' width='$hpszazalek'><IMG src='pixelblack.bmp' height='10' width='$hplost'> ($hp%)</TD>
				<TD align='center'>$maxhp/$actualhp</TD>
			</TR>
		";
		
		$shieldszazalek = $actualshield/$maxshield*200;
		settype($shieldszazalek, "integer");
		$shieldlost = 200-$shieldszazalek;
		$shield = $shieldszazalek/2;
		print "
			<TR>
				<TD align='right'>Pajzs:</TD>
				<TD colspan='2'><IMG src='pixelshield.bmp' height='10' width='$shieldszazalek'><IMG src='pixelblack.bmp' height='10' width='$shieldlost'> ($shield%)</TD>
				<TD align='center'>$maxshield/$actualshield</TD>
			</TR>
		";
		
		$manaszazalek = $actualmana/$maxmana*200;
		settype($manaszazalek, "integer");
		$manalost = 200-$manaszazalek;
		$mana = $manaszazalek/2;
		print "
			<TR>
				<TD align='right'>Mana:</TD>
				<TD colspan='2'><IMG src='pixelmana.bmp' height='10' width='$manaszazalek'><IMG src='pixelblack.bmp' height='10' width='$manalost'> ($mana%)</TD>
				<TD align='center'>$maxmana/$actualmana</TD>
			</TR>
		";
		$alcazott = "";
		if($cloaked) $alcazott = "Álcázva: IGEN";
		if(!$cloaked and $cloaknum) $alcazott = "Álcázás: <INPUT type='checkbox' name='cloaked' value='1'>";
		print "
			<TR>
				<TD align='center'>Álcák: $cloaknum $alcazott</TD>
				<TD align='center'>Penetráció: $ertekpenet</TD>
				<TD colspan='2' align='center'>Sebzés: $actualdmg</TD>
			</TR>
		";
		
		if($szintpassziv)
		{
			if(!$passzivnevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='passziv' AND ownerkaszt='$kaszt'")) die("Passzív név lekérdezése sikertelen");
			foreach($passzivtomb = mysqli_fetch_row($passzivnevlekeres) as $passziv) $passziv = "$passziv ($szintpassziv)";
		}
		if(!$szintpassziv) $passziv = "Nincs kifejlesztve";
		
		if($szintability0)
		{
			if(!$ability0nevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='ability0' AND ownerkaszt='$kaszt'")) die("Ability0 név lekérése sikertelen");
			foreach($ability0tomb = mysqli_fetch_row($ability0nevlekeres) as $ability0nev);
			if(!$activeability0)
			{
				if($reloadability0) $ability0 = "$ability0nev ($szintability0): (Újratöltés: $reloadability0)";
				if(!$reloadability0) $ability0 = "$ability0nev ($szintability0): <INPUT type='checkbox' name='ability0' value='activeability0'>";
			}
			if($activeability0) $ability0 = "$ability0nev ($szintability0) aktív: $activeability0";
		}
		if($disability) $ability0 = "Disability ($disability)";
		if(!$szintability0) $ability0 = "Nincs kifejlesztve";
		
		if($szintability1)
		{
			if(!$ability1nevlekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='ability1' AND ownerkaszt='$kaszt'")) die("ability1 név lekérése sikertelen");
			foreach($ability1tomb = mysqli_fetch_row($ability1nevlekeres) as $ability1nev);
			if(!$activeability1)
			{
				if($reloadability1) $ability1 = "$ability1nev ($szintability1): (Újratöltés: $reloadability1)";
				if(!$reloadability1) $ability1 = "$ability1nev ($szintability1): <INPUT type='checkbox' name='ability1' value='activeability1'>";
			}
			if($activeability1) $ability1 = "$ability1nev aktív ($szintability1): $activeability1";
		}
		if($disability) $ability1 = "Disability ($disability)";
		if(!$szintability1) $ability1 = "Nincs kifejlesztve";
		print "
			<TR>
				<TD align='right'>Képességek</TD>
				<TD align='center'>$passziv</TD>
				<TD align='center'>$ability0</TD>
				<TD align='center'>$ability1</TD>
			</TR>
		";
		
		$x1checked = "";
		$x2checked = "";
		$x3checked = "";
		$x4checked = "";
		
		if(!$lekeres = mysqli_query($_SESSION["conn"], "SELECT ammo FROM battle WHERE karakterazonosito='$id'"));
		foreach($ammotomb = mysqli_fetch_row($lekeres) as $ammo);
		
		switch($ammo)
		{
			case "x2": $x2checked = "checked='checked'"; break;
			case "x3": $x3checked = "checked='checked'"; break;
			case "x4": $x4checked = "checked='checked'"; break;
			default: $x1checked = "checked='checked'";
		}
		
		print "
			<TR>
				<TD valign='top'>Lőszerek:<BR>
					<INPUT type='radio' name='loszer' $x1checked value='x1'>x1<BR>
		";
		
		if($x2num) print "<INPUT type='radio' name='loszer' $x2checked value='x2'>x2 ($x2num)<BR>";
		if($x3num) print "<INPUT type='radio' name='loszer' $x3checked value='x3'>x3 ($x3num)<BR>";
		if($x4num) print "<INPUT type='radio' name='loszer' $x4checked value='x4'>x4 ($x4num)<BR>";
		
		$pot = 0;
		$hppoti = "";
		if($hppotnum or $hppotreload or $hppotactive)
		{
			if($hppotnum and !$hppotreload and !$hppotactive) $hppoti = "<INPUT type='checkbox' name='hppotactive' value='5'>Életerőpoti ($hppotnum)<BR>";
			if($hppotreload) $hppoti = "Életerőpoti (Újratöltés: $hppotreload)<BR>";
			if($hppotactive) $hppoti = "Életerőpoti (Aktív: $hppotactive)<BR>";
			if($disability) $hppoti = "Disability ($disability)";
			$pot = 1;
		}
		
		$shieldpoti = "";
		if($shieldpotnum or $shieldpotreload or $shieldpotactive)
		{
			if($shieldpotnum and !$shieldpotreload and !$shieldpotactive) $shieldpoti = "<INPUT type='checkbox' name='shieldpotactive' value='5'>Pajzspoti ($shieldpotnum)<BR>";
			if($shieldpotreload) $shieldpoti = "Pajzspoti (Újratöltés: $shieldpotreload)<BR>";
			if($shieldpotactive) $shieldpoti = "Pajzspoti (Aktív: $shieldpotactive)<BR>";
			if($disability) $shieldpoti = "Disability ($disability)";
			$pot = 1;
		}
		
		$manapoti = "";
		if($manapotnum or $manapotreload or $manapotactive)
		{
			if($manapotnum and !$manapotreload and !$manapotactive) $manapoti = "<INPUT type='checkbox' name='manapotactive' value='5'>Manapoti ($manapotnum)<BR>";
			if($manapotreload) $manapoti = "Manapoti (Újratöltés: $manapotreload)<BR>";
			if($manapotactive) $manapoti = "Manapoti (Aktív: $manapotactive)<BR>";
			if($disability) $manapoti = "Disability ($disability)";
			$pot = 1;
		}
		$poti = "";
		if(!$pot) $poti = "Nincs elérhető poti";
		
		print "
				</TD>
				<TD valign='top'>
					Potik:<BR>
					$hppoti
					$shieldpoti
					$manapoti
					$poti
				</TD>
		";
		
		$extr = 0;
		$extra = "";
		
		$ish = "";
		if($ishnum or $ishreload)
		{
			if($ishnum and !$ishreload) $ish = "<INPUT type='checkbox' name='ishreload' value='20'>ISH ($ishnum)<BR>";
			if($ishreload) $ish = "ISH (Újratöltés: $ishreload)<BR>";
			if($disability) $ish = "Disability ($disability)";
			$extr = 1;
		}
		
		$emp = "";
		if($empnum or $empreload)
		{
			if($empnum and !$empreload) $emp = "<INPUT type='checkbox' name='empreload' value='20'>EMP ($empnum)<BR>";
			if($empreload) $emp = "EMP (Újratöltés: $empreload)<BR>";
			if($disability) $emp = "Disability ($disability)";
			$extr = 1;
		}
		
		$pld = "";
		if($pldnum or $pldreload or $pldactive)
		{
			if($pldnum and !$pldreload and !$pldactive) $pld = "<INPUT type='checkbox' name='pldactive' value='5'>PLD ($pldnum)<BR>";
			if($pldreload) $pld = "PLD (Újratöltés: $pldreload)<BR>";
			if($pldactive) $pld = "PLD (Aktív: $pldactive)<BR>";
			if($disability) $pld = "Disability ($disability)";
			$extr = 1;
		}
		
		if(!$extr) $extra = "Nincs elérhető extra";
		
		print "
			<TD valign='top'>
				Extrák:<BR>
				$ish
				$emp
				$pld
				$extra
			</TD>
		";
		
		print "<TD valign='top'>Felszerelések:<BR>";
		
		$felszerelestomb = array("alap", "pajzs", "pancel", "kotszer", "phalanx", "kard", "manakristaly");
		foreach($felszerelestomb as $felszereles)
		{
			$checked = "";
			if($activefelszereles == $felszereles) $checked = "checked='checked'";
			if($$felszereles) print "<INPUT type='radio' $checked name='activefelszereles' value='$felszereles'>$felszereles<BR>";
		}
		print "</TR>";
		
		if($hpboosterrounds or $shieldboosterrounds or $manaboosterrounds or $dmgboosterrounds or $attackboosterrounds or $accurboosterrounds)
		{
			
			print "
				<TR>
					<TD align='center' colspan='4'>
						<TABLE width='100%'>
							<TR>
								<TD rowspan='2'>Boosterek:</TD>
								<TD align='center'>Életerőbooster ($hpboosterrounds)</TD>
								<TD align='center'>Pajzsbooster ($shieldboosterrounds)</TD>
								<TD align='center'>Manabooster ($manaboosterrounds)</TD>
							</TR>
							<TR>
								<TD align='center'>Sebzésbooster ($dmgboosterrounds)</TD>
								<TD align='center'>Attackbooster ($attackboosterrounds)</TD>
								<TD align='center'>Jelölésbooster ($accurboosterrounds)</TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			";
			
		}
		
		print "</TABLE>";
	}
?>