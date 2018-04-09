<?php
	include("connection.php");
	
	function karakterreset($id, $actual)
	{
		$actual["actualhp"] = $actual["maxhp"];
		$actual["actualshield"] = $actual["maxshield"];
		$actual["actualmana"] = $actual["maxmana"];
		$actual["activeability0"] = 0;
		$actual["reloadability0"] = 0;
		$actual["activeability1"] = 0;
		$actual["reloadability1"] = 0;
		$actual["hppotactive"] = 0;
		$actual["hppotreload"] = 0;
		$actual["shieldpotactive"] = 0;
		$actual["shieldpotreload"] = 0;
		$actual["manapotactive"] = 0;
		$actual["manapotreload"] = 0;
		$actual["empreload"] = 0;
		$actual["empactive"] = 0;
		$actual["ishreload"] = 0;
		$actual["ishactive"] = 0;
		$actual["pldactive"] = 0;
		$actual["pldreload"] = 0;
		
		$sqlset = "SET ";
		foreach($actual as $name=>$ertek)
		{
			$sqlset = "$sqlset" . "$name" . "='" . "$ertek" . "', ";
		}
		
		$sqlsethossz = strlen($sqlset)-2;
		$sqlset = substr($sqlset, 0, $sqlsethossz);
		$sql = "UPDATE users " . "$sqlset" . " WHERE karakterazonosito='$id'";
		
		if(!$update = mysqli_query($_SESSION["conn"], "$sql")) die("A frissítés sikertelen");
	}
	
	
	function vasarlasfeldolgoz($id, $item, $amount, $kaszt)
	{
		$item1 = $item;
		if($item == "szintpassziv" or $item == "szintability0" or $item == "szintability1")
		{
			$item1 = substr($item, 5);
			print $item1;
			if(!$abilitynamelekeres = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='$item1'")) die("épességnevek lekérése sikertelen");;
			foreach($abilitynametomb = mysqli_fetch_row($abilitynamelekeres) as $item1);
		}
		if(!$itemarlekeres = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE karaktermegf='$item1'")) die("Tárgy árának lekérése sikertelen");
		foreach($itemartomb = mysqli_fetch_row($itemarlekeres) as $itemar);

		$ertek = $amount*$itemar;
		
		if(!$aktuallekeres = mysqli_query($_SESSION["conn"], "SELECT $item FROM users WHERE karakterazonosito='$id'")) die("Aktuális itemek lekérése sikertelen");
		foreach($aktualtomb = mysqli_fetch_row($aktuallekeres) as $regiertek);

		$amount1 = $amount;
		if($item == "hpboosterrounds" or $item == "shieldboosterrounds" or $item == "manaboosterrounds" or $item == "dmgboosterrounds" or $item == "attackboosterrounds" or $item == "accurboosterrounds") $amount1 = $amount*500;
		$ujertek = $regiertek+$amount1;
		
		if(!$actualmoneyleker = mysqli_query($_SESSION["conn"], "SELECT actualmoney FROM users WHERE karakterazonosito='$id'")) die("Karakter vagyonának lekérése sikertelen");
		foreach($actualmoneytomb = mysqli_fetch_row($actualmoneyleker) as $actualmoney)
		
		$ujmoney = $actualmoney-$amount*$itemar;
		
		$sql ="UPDATE users SET $item='$ujertek', actualmoney='$ujmoney' WHERE karakterazonosito='$id'";
		if(!$itemfeltolt = mysqli_query($_SESSION["conn"], "$sql")) die("Adatok frissítése sikertelen");
		return 1;
	}
	
	
	function head($id, $username)
	{
		print"
			<TABLE>
				<TR>
					<TD><B>Felhasználónév:</B></TD>
					<TD>$username</TD>
					<TD><B>Karakterazonosító:</B></TD>
					<TD>$id</TD>
				</TR>
				<TR>
					<TD><A href='indexgame.php'>Kijelentkezés</A></TD>
					<TD><A href='beallitasok.php'>Beállítások</A></TD>
				</TR>
			</TABLE>
		";
	}
	
	function user($id)
	{
		if(!$adatok = mysqli_query($_SESSION["conn"], "SELECT * FROM users WHERE karakterazonosito='$id'")) die("Karakteradatok lekérése sikertelen");
		foreach($user = mysqli_fetch_assoc($adatok) as $name=>$ertek) $$name = $ertek;
		
		if(!$abilityname = mysqli_query($_SESSION["conn"], "SELECT passziv, ability0, ability1 FROM kasztok WHERE kasztnev='$kaszt'")) die("A képességnevek lekérése sikertelen");
		foreach($kepessegek = mysqli_fetch_assoc($abilityname) as $name=>$ertek) $$name = $ertek;
		
		$hpsz = $actualhp/$maxhp*100;
		$hpsza = 100-$hpsz;
		$shieldsz = $actualshield/$maxshield*100;
		$shieldsza = 100-$shieldsz;
		$manasz = $actualmana/$maxmana*100;
		$manasza = 100-$manasz;
		if($cloaked) $cloaked = "IGEN";
		if(!$cloaked) $cloaked = "NEM";
			$felhasznalonev = $_SESSION["username"];
			print "
				<TABLE align='center' border='1'>
					<TR>
						<TD align='center'><B>$felhasznalonev</B></TD>
						<TD align='center' colspan='2'><B>Szint:</B> $karakterszint, <B>Kaszt:</B> $kaszt</TD>
						<TD align='center'><B>Pénz:</B> $actualmoney</TD>
					</TR>
					<TR>
						<TD align='center'><B>Stat</B></TD>
						<TD align='center'><B>Maximum érték</B></TD>
						<TD align='center'><B>Aktuális érték</B></TD>
						<TD align='center'><B>%</B></TD>
					</TR>
					<TR>
						<TD align='right'><B>Életerő</B> szintje: $szinthp</TD>
						<TD align='center'>$maxhp</TD>
						<TD align='center'>$actualhp</TD>
						<TD align='center'><IMG src='pixelhp.bmp' width='$hpsz' height='10'><IMG src='pixelblack.bmp' width='$hpsza' height='10'>($hpsz%)</TD>
					</TR>
					<TR>
						<TD align='right'><B>Pajzs</B> szintje: $szintshield</TD>
						<TD align='center'>$maxshield</TD>
						<TD align='center'>$actualshield</TD>
						<TD align='center'><IMG src='pixelshield.bmp' width='$shieldsz' height='10'><IMG src='pixelblack.bmp' width='$shieldsza' height='10'>($shieldsz%)</TD>
					</TR>
					<TR>
						<TD align='right'><B>Mana</B> szintje: $szintmana</TD>
						<TD align='center'>$maxmana</TD>
						<TD align='center'>$actualmana</TD>
						<TD align='center'><IMG src='pixelmana.bmp' width='$manasz' height='10'><IMG src='pixelblack.bmp' width='$manasza' height='10'>($manasz%)</TD>
					</TR>
					<TR>
						<TD align='center' colspan='4'><B>Sebzés szintje:</B> $szintdmg, <B>Sebzés:</B> $actualdmg</TD>
					<TR>
						<TD align='right'><B>Penetráció</B> szintje: $szintpenet</TD>
						<TD align='center'>$ertekpenet %</TD>
						<TD align='center'>Álcák száma: $cloaknum</TD>
						<TD align='center'>Álcázott: $cloaked</TD>
					</TR>
					<TR>
						<TD align='right'><B>Képességek:</B></TD>
						<TD align='center'><B>$passziv</B> szintje: $szintpassziv</TD>
						<TD align='center'><B>$ability0</B> szintje: $szintability0</TD>
						<TD align='center'><B>$ability1</B> szintje: $szintability1</TD>
					</TR>
					<TR>
						<TD align='right'><B>Lőszerek:</B></TD>
						<TD align='center'><B>x2:</B> $x2num</TD>
						<TD align='center'><B>x3:</B> $x3num</TD>
						<TD align='center'><B>x4:</B> $x4num</TD>
					</TR>
					<TR>
						<TD align='right'><B>Potik száma:</B></TD>
						<TD align='center'><B>Életerőpoti:</B> $hppotnum</TD>
						<TD align='center'><B>Pajzspoti:</B> $shieldpotnum</TD>
						<TD align='center'><B>Manapoti:</B> $manapotnum</TD>
					</TR>
					<TR>
						<TD align='right'><B>Extrák száma:</B></TD>
						<TD align='center'><B>EMP:</B> $empnum</TD>
						<TD align='center'><B>ISH:</B> $ishnum</TD>
						<TD align='center'><B>PLD:</B> $pldnum</TD>
					</TR>
					<TR>
						<TD align='center' colspan='4'><B>Felszerelések:</B></TD>
					</TR>
					<TR>
						<TD align='center' colspan='4'>
							<TABLE align='center'>
								<TR>";
									if(!$leker = mysqli_query($_SESSION["conn"], "SELECT pajzs, pancel, kotszer, phalanx, kard, manakristaly, alap FROM users WHERE karakterazonosito='$id'")) die("Felszerelések lekérése nem sikerült");
									

									if(!$actualfelszleker = mysqli_query($_SESSION["conn"], "SELECT activefelszereles FROM users WHERE karakterazonosito='$id'")) die("Aktuális felszerelés lekérése sikertelen");
									foreach($aktufelsz = mysqli_fetch_row($actualfelszleker) as $aktfelsz)

									foreach($felsznev = mysqli_fetch_assoc($leker) as $name=>$ertek)
									{
										if($ertek)
										{
											if($name != $aktfelsz) print "<TD><FORM method='POST' action='startpage.php'><INPUT type='submit' name='felsz' value='$name'></FORM></TD>";
											if($name == $aktfelsz)	print "<TD><B>$name</B></TD>";
										}
									}
			print "				</TR>
							</TABLE>
						</TD>
					</TR>
					<TR>
						<TD rowspan='2' align='center'><B>Boosterek:</B></TD>
						<TD align='center'>Hpbooster: $hpboosterrounds</TD>
						<TD align='center'>Pajzsbooster: $shieldboosterrounds</TD>
						<TD align='center'>Manabooster: $manaboosterrounds</TD>
					</TR>
					<TR>
						<TD align='center'>Sebzésbooster: $dmgboosterrounds</TD>
						<TD align='center'>Attackbooster: $attackboosterrounds</TD>
						<TD align='center'>Jelölésbooster: $accurboosterrounds</TD>
					</TR>
				</TABLE>
			";
	}
	
	function shop($actualmoney, $id, $kaszt)
	{
		if(!$arak = mysqli_query($_SESSION["conn"], "SELECT cost from costs WHERE itemtype='ammo'")) die("Lőszerárak lekérése sikertelen");
		$szam = 2;
		while($ara = mysqli_fetch_row($arak))
		{
			foreach($ara as $ertek)
			{
				$valtozonev = "x" . "$szam" . "ar";
				$$valtozonev = $ertek;
				$szam++;
			}
		}
		
		if(!$actualammo = mysqli_query($_SESSION["conn"], "SELECT x2num, x3num, x4num FROM users WHERE karakterazonosito='$id'")) die("A karakter lőszerének lekérése sikertelen");
		foreach($valtnev = mysqli_fetch_assoc($actualammo) as $name=>$ertek) $$name = $ertek;

		$x2vasarlas = vasarlas($actualmoney, $x2ar, "x2num");
		$x3vasarlas = vasarlas($actualmoney, $x3ar, "x3num");
		$x4vasarlas = "Nem vásárolható";
		if(0) vasarlas($actualmoney, $x4ar, "x4num");
		
		if(!$x2manausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='x2'")) die("x2 manafelhasználás lekérése sikertelen.");
		foreach($x2manausagetomb = mysqli_fetch_row($x2manausagelekeres) as $x2manausage);

		$x2leiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Kétszerezi az alapsebzést.</TD>
					<TD align='right'>Manafelhasználás: $x2manausage/kör</TD>
				</TR>
			</TABLE>
		";
		
		if(!$x3manausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='x3'")) die("x3 manafelhasználás lekérése sikertelen.");
		foreach($x3manausagetomb = mysqli_fetch_row($x3manausagelekeres) as $x3manausage)

		$x3leiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Háromszorozza az alapsebzést.</TD>
					<TD align='right'>Manafelhasználás: $x3manausage/kör</TD>
				</TR>
			</TABLE>
		";
		
		if(!$x4manausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='x4'")) die("x4 manafelhasználás lekérése sikertelen.");
		foreach($x4manausagetomb = mysqli_fetch_row($x4manausagelekeres) as $x4manausage)
		
		$x4leiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Négyszerezi az alapsebzést.</TD>
					<TD align='right'>Manafelhasználás: $x4manausage/kör</TD>
				</TR>
			</TABLE>
		";
		
		print"
			<TABLE border='1' align='center'>
				<TR>
					<TD align='center' colspan='5'><H1>Bolt</H1></TD>
				</TR>
				<TR>
					<TD colspan='4'><BR><H2>Lőszerek</H2></TD>
					<TD align='right'><H2>Pénzed: $actualmoney</H2></TD>
				</TR>
					<TD align='center'><H3>Tárgy</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Táskában</H3></TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
				<TR>
					<TD align='right'>x2</TD>
					<TD align='center'>$x2leiras</TD>
					<TD align='center'>$x2ar</TD>
					<TD align='center'>$x2num</TD>
					<TD align='center'>$x2vasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>x3</TD>
					<TD align='center'>$x3leiras</TD>
					<TD align='center'>$x3ar</TD>
					<TD align='center'>$x3num</TD>
					<TD align='center'>$x3vasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>x4</TD>
					<TD align='center'>$x4leiras</TD>
					<TD align='center'>$x4ar</TD>
					<TD align='center'>$x4num</TD>
					<TD align='center'>$x4vasarlas</TD>
				</TR>
		";

		if(!$extraarak = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE itemtype='extra'")) die("Extraárak lekérése sikertelen");

		if(!$extraitems = mysqli_query($_SESSION["conn"], "SELECT item FROM costs WHERE itemtype='extra'")) die("Itemnevek lekérése sikertelen");

		while($extraitem = mysqli_fetch_row($extraitems))
		{
			foreach($extraitem as $extranev) $extranevek[] = $extranev;
		}
		
		$szam = 0;
		while($extraara = mysqli_fetch_row($extraarak))
		{
			foreach($extraara as $ertek)
			{
				$extranev = $extranevek[$szam] . "ar";
				$$extranev = $ertek;
				$szam++;
			}
		}
		
		if(!$actualextra = mysqli_query($_SESSION["conn"], "SELECT empnum, ishnum, pldnum, cloaknum FROM users WHERE karakterazonosito='$id'")) die("A karakter extráinak lekérése sikertelen");
		foreach($valtnev = mysqli_fetch_assoc($actualextra) as $name=>$ertek) $$name = $ertek;

		$empvasarlas = vasarlas($actualmoney, $empar, "empnum");
		$ishvasarlas = vasarlas($actualmoney, $ishar, "ishnum");
		$pldvasarlas = vasarlas($actualmoney, $pldar, "pldnum");
		$cloakvasarlas = vasarlas($actualmoney, $cloakar, "cloaknum");
		
		
		if(!$empmanausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='emp'")) die("EMP manafelhasználás lekérése sikertelen");
		foreach($empmanausagetomb = mysqli_fetch_row($empmanausagelekeres) as $empmanausage);

		if(!$empreloadtimelekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='emp'")) die("EMP újratöltési idő lekérése sikertelen");
		foreach($empreloadtomb = mysqli_fetch_row($empreloadtimelekeres) as $empreload);

		$empleiras = "
			<TABLE width='100%' align='center'>
				<TR>
					<TD>Az összes kijelölést leszedi a karakterről.</TD>
					<TD align='center'>Manafelhasználás: $empmanausage</TD>
					<TD align='right'>Újratöltési idő: $empreload</TD>
				</TR>
			</TABLE>
		";
		
		if(!$ishmanausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='ish'")) die("ish manafelhasználás lekérése sikertelen");
		foreach($ishmanausagetomb = mysqli_fetch_row($ishmanausagelekeres) as $ishmanausage);

		if(!$ishreloadtimelekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='ish'")) die("ish újratöltési idő lekérése sikertelen");
		foreach($ishreloadtomb = mysqli_fetch_row($ishreloadtimelekeres) as $ishreload);

		$ishleiras = "
			<TABLE width='100%' align='center'>
				<TR>
					<TD>Elnyeli az összes beérkező sebzést</TD>
					<TD align='center'>Manafelhasználás: $ishmanausage</TD>
					<TD align='right'>Újratöltési idő: $ishreload</TD>
				</TR>
			</TABLE>
		";
		
		if(!$pldmanausagelekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='pld'")) die("pld manafelhasználás lekérése sikertelen");
		foreach($pldmanausagetomb = mysqli_fetch_row($pldmanausagelekeres) as $pldmanausage);

		if(!$pldreloadtimelekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='pld'")) die("pld újratöltési idő lekérése sikertelen");
		foreach($pldreloadtomb = mysqli_fetch_row($pldreloadtimelekeres) as $pldreload);
		
		if(!$pldaktivlekeres = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='pld'")) die("Pld körének lekérése sikertelen");
		foreach($pldaktivtomb = mysqli_fetch_row($pldaktivlekeres) as $pldaktiv);

		$pldleiras = "
			<TABLE width='100%' align='center'>
				<TR>
					<TD>Csökkenti az eltalált célpont attackját.</TD>
					<TD align='center'>Manafelhasználás: $pldmanausage</TD>
					<TD align='center'>Aktív körök: $pldaktiv</TD>
					<TD align='right'>Újratöltési idő: $pldreload</TD>
				</TR>
			</TABLE>
		";
		
		$alcaleiras = "
			<TABLE width='100%' align='center'>
				<TR>
					<TD align='center'>Növeli az első támadás esélyét, és rontja az ellenfelek kijelölését a csata első körében</TD>
				</TR>
			</TABLE>
		";
		print "
				<TR>
					<TD colspan='5'><BR><H2>Extrák</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Tárgy</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Táskában</H3></TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
				<TR>
					<TD align='right'>EMP</TD>
					<TD align='center'>$empleiras</TD>
					<TD align='center'>$empar</TD>
					<TD align='center'>$empnum</TD>
					<TD align='center'>$empvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>ISH</TD>
					<TD align='center'>$ishleiras</TD>
					<TD align='center'>$ishar</TD>
					<TD align='center'>$ishnum</TD>
					<TD align='center'>$ishvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>PLD</TD>
					<TD align='center'>$pldleiras</TD>
					<TD align='center'>$pldar</TD>
					<TD align='center'>$pldnum</TD>
					<TD align='center'>$pldvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Álca</TD>
					<TD align='center'>$alcaleiras</TD>
					<TD align='center'>$cloakar</TD>
					<TD align='center'>$cloaknum</TD>
					<TD align='center'>$cloakvasarlas</TD>
				</TR>
		";
		
		
		if(!$potionarak = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE itemtype='potion'")) die("Potionárak lekérése sikertelen");

		if(!$potionitems = mysqli_query($_SESSION["conn"], "SELECT item FROM costs WHERE itemtype='potion'")) die("Itemnevek lekérése sikertelen");

		while($potionitem = mysqli_fetch_row($potionitems))
		{
			foreach($potionitem as $potionnev)
			{
				$potionnevek[] = $potionnev;
			}
		}
		
		$szam = 0;
		while($potionara = mysqli_fetch_row($potionarak))
		{
			foreach($potionara as $ertek)
			{
				$potionnev = $potionnevek[$szam] . "ar";
				$$potionnev = $ertek;
				$szam++;
			}
		}
		
		if(!$actualpotion = mysqli_query($_SESSION["conn"], "SELECT hppotnum, shieldpotnum, manapotnum FROM users WHERE karakterazonosito='$id'")) die("A karakter potiojainak lekérése sikertelen");
		foreach($potinev = mysqli_fetch_assoc($actualpotion) as $name=>$ertek) $$name = $ertek;

		
		
		$hppotvasarlas = vasarlas($actualmoney, $hppotar, "hppotnum");
		$shieldpotvasarlas = vasarlas($actualmoney, $shieldpotar, "shieldpotnum");
		$manapotvasarlas = vasarlas($actualmoney, $manapotar, "manapotnum");
		
		
		if(!$hppotactivelekeres = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='hppot'")) die("Hp poti aktív köreinek lekérése sikertelen");
		foreach($hppotactivetomb = mysqli_fetch_row($hppotactivelekeres) as $hppotactive);
	
		if(!$hppotmanalekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='hppot'")) die("Hp poti manafelhasználás lekérése sikertelen");
		foreach($hppotmanatomb = mysqli_fetch_row($hppotmanalekeres) as $hppotmanausage)

		if(!$hppotreloadlekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='hppot'")) die("Hp poti újratöltési idő lekérése sikertelen");
		foreach($hppotreloadtomb = mysqli_fetch_row($hppotreloadlekeres) as $hppotreload);
	
		$hppotleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Körönként a maximális életerő 10%-ával tölti az életerőt.</TD>
					<TD align='center'>Aktív: $hppotactive körig.</TD>
					<TD align='center'>Manafelhasználás: $hppotmanausage</TD>
					<TD align='right'>Újratöltés: $hppotreload kör</TD>
				</TR>
			</TABLE>
		";
		
		if(!$shieldpotactivelekeres = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='shieldpot'")) die("shield poti aktív köreinek lekérése sikertelen");
		foreach($shieldpotactivetomb = mysqli_fetch_row($shieldpotactivelekeres) as $shieldpotactive);

		if(!$shieldpotmanalekeres = mysqli_query($_SESSION["conn"], "SELECT manausage FROM specials WHERE specialname='shieldpot'")) die("shield poti manafelhasználás lekérése sikertelen");
		foreach($shieldpotmanatomb = mysqli_fetch_row($shieldpotmanalekeres) as $shieldpotmanausage);

		if(!$shieldpotreloadlekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='shieldpot'")) die("shield poti újratöltési idő lekérése sikertelen");
		foreach($shieldpotreloadtomb = mysqli_fetch_row($shieldpotreloadlekeres) as $shieldpotreload);
		
		$shieldpotleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Körönként a maximális pajzs 10%-ával tölti a pajzsot.</TD>
					<TD align='center'>Aktív: $shieldpotactive körig.</TD>
					<TD align='center'>Manafelhasználás: $shieldpotmanausage</TD>
					<TD align='right'>Újratöltés: $shieldpotreload kör</TD>
				</TR>
			</TABLE>
		";
		
		if(!$manapotactivelekeres = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='manapot'")) die("mana poti aktív köreinek lekérése sikertelen");
		foreach($manapotactivetomb = mysqli_fetch_row($manapotactivelekeres) as $manapotactive);

		if(!$manapotreloadlekeres = mysqli_query($_SESSION["conn"], "SELECT reload FROM specials WHERE specialname='manapot'")) die("mana poti újratöltési idő lekérése sikertelen");
		foreach($manapotreloadtomb = mysqli_fetch_row($manapotreloadlekeres) as $manapotreload);
		
		$manapotleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Körönként a maximális mana 10%-ával tölti a manát.</TD>
					<TD align='center'>Aktív: $manapotactive körig.</TD>
					<TD align='right'>Újratöltés: $manapotreload kör</TD>
				</TR>
			</TABLE>
		";
		
		print "
				<TR>
					<TD colspan='5'><H2><BR>Potik</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Tárgy</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Táskában</H3></TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
				<TR>
					<TD align='right'>Életerőpoti</TD>
					<TD align='center'>$hppotleiras</TD>
					<TD align='center'>$hppotar</TD>
					<TD align='center'>$hppotnum</TD>
					<TD align='center'>$hppotvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Pajzspoti</TD>
					<TD align='center'>$shieldpotleiras</TD>
					<TD align='center'>$shieldpotar</TD>
					<TD align='center'>$shieldpotnum</TD>
					<TD align='center'>$shieldpotvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Manapoti</TD>
					<TD align='center'>$manapotleiras</TD>
					<TD align='center'>$manapotar</TD>
					<TD align='center'>$manapotnum</TD>
					<TD align='center'>$manapotvasarlas</TD>
				</TR>
		";
		
		
		if(!$boosterarak = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE itemtype='booster'")) die("Boosterárak lekérése sikertelen");

		if(!$boosteritems = mysqli_query($_SESSION["conn"], "SELECT item FROM costs WHERE itemtype='booster'")) die("Itemnevek lekérése sikertelen");

		while($boosteritem = mysqli_fetch_row($boosteritems))
		{
			foreach($boosteritem as $boosternev)
			{
				$boosternevek[] = $boosternev;
			}
		}
		
		$szam = 0;
		while($boosterara = mysqli_fetch_row($boosterarak))
		{
			foreach($boosterara as $ertek)
			{
				$boosternev = $boosternevek[$szam] . "ar";
				$$boosternev = $ertek;
				$szam++;
			}
		}
		
		if(!$actualbooster = mysqli_query($_SESSION["conn"], "SELECT hpboosterrounds, shieldboosterrounds, manaboosterrounds, dmgboosterrounds, attackboosterrounds, accurboosterrounds FROM users WHERE karakterazonosito='$id'")) die("A karakter potiojainak lekérése sikertelen");
		foreach($boosternev = mysqli_fetch_assoc($actualbooster) as $name=>$ertek) $$name = $ertek;
		
		$hpboostervasarlas = vasarlas($actualmoney, $hpboosterar, "hpboosterrounds");
		$shieldboostervasarlas = vasarlas($actualmoney, $shieldboosterar, "shieldboosterrounds");
		$manaboostervasarlas = vasarlas($actualmoney, $manaboosterar, "manaboosterrounds");
		$dmgboostervasarlas = vasarlas($actualmoney, $dmgboosterar, "dmgboosterrounds");
		$attackboostervasarlas = vasarlas($actualmoney, $attackboosterar, "attackboosterrounds");
		$accurboostervasarlas = vasarlas($actualmoney, $accurboosterar, "accurboosterrounds");
		
		if(!$maxhpboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='hpbooster'")) die("Hp booster köreinek lekérése sikertelen");
		foreach($maxhpboosterroundstomb = mysqli_fetch_row($maxhpboosterroundsleker) as $maxhpboosterrounds);

		$hpboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli a maximális életerőt.</TD>
					<TD align='right'>Aktív: $maxhpboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		if(!$maxshieldboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='shieldbooster'")) die("shield booster köreinek lekérése sikertelen");
		foreach($maxshieldboosterroundstomb = mysqli_fetch_row($maxshieldboosterroundsleker) as $maxshieldboosterrounds);

		$shieldboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli a maximális pajzsot.</TD>
					<TD align='right'>Aktív: $maxshieldboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		if(!$maxmanaboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='manabooster'")) die("mana booster köreinek lekérése sikertelen");
		foreach($maxmanaboosterroundstomb = mysqli_fetch_row($maxmanaboosterroundsleker) as $maxmanaboosterrounds);

		$manaboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli a maximális manát.</TD>
					<TD align='right'>Aktív: $maxmanaboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		if(!$maxdmgboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='dmgbooster'")) die("dmg booster köreinek lekérése sikertelen");
		foreach($maxdmgboosterroundstomb = mysqli_fetch_row($maxdmgboosterroundsleker) as $maxdmgboosterrounds);
		
		$dmgboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli az alapsebzést.</TD>
					<TD align='right'>Aktív: $maxdmgboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		
		if(!$maxattackboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='attackbooster'")) die("attack booster köreinek lekérése sikertelen");
		foreach($maxattackboosterroundstomb = mysqli_fetch_row($maxattackboosterroundsleker) as $maxattackboosterrounds);
		
		$attackboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli a attackot.</TD>
					<TD align='right'>Aktív: $maxattackboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		if(!$maxaccurboosterroundsleker = mysqli_query($_SESSION["conn"], "SELECT aktivkor FROM specials WHERE specialname='accurbooster'")) die("accur booster köreinek lekérése sikertelen");
		foreach($maxaccurboosterroundstomb = mysqli_fetch_row($maxaccurboosterroundsleker) as $maxaccurboosterrounds);
		
		$accurboosterleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>25%-al növeli a jelölést.</TD>
					<TD align='right'>Aktív: $maxaccurboosterrounds körig.</TD>
				</TR>
			</TABLE>
		";
		
		print "
				<TR>
					<TD colspan='5'><H2><BR>Boosterek</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Tárgy</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Táskában</H3></TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
				<TR>
					<TD align='right'>Életerőbooster</TD>
					<TD align='center'>$hpboosterleiras</TD>
					<TD align='center'>$hpboosterar</TD>
					<TD align='center'>$hpboosterrounds</TD>
					<TD align='center'>$hpboostervasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Pajzsbooster</TD>
					<TD align='center'>$shieldboosterleiras</TD>
					<TD align='center'>$shieldboosterar</TD>
					<TD align='center'>$shieldboosterrounds</TD>
					<TD align='center'>$shieldboostervasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Manabooster</TD>
					<TD align='center'>$manaboosterleiras</TD>
					<TD align='center'>$manaboosterar</TD>
					<TD align='center'>$manaboosterrounds</TD>
					<TD align='center'>$manaboostervasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Sebzésbooster</TD>
					<TD align='center'>$dmgboosterleiras</TD>
					<TD align='center'>$dmgboosterar</TD>
					<TD align='center'>$dmgboosterrounds</TD>
					<TD align='center'>$dmgboostervasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Attackbooster</TD>
					<TD align='center'>$attackboosterleiras</TD>
					<TD align='center'>$attackboosterar</TD>
					<TD align='center'>$attackboosterrounds</TD>
					<TD align='center'>$attackboostervasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Jelölésbooster</TD>
					<TD align='center'>$accurboosterleiras</TD>
					<TD align='center'>$accurboosterar</TD>
					<TD align='center'>$accurboosterrounds</TD>
					<TD align='center'>$accurboostervasarlas</TD>
				</TR>
		";
		
		
		
		
		if(!$felszereleslekeres = mysqli_query($_SESSION["conn"], "SELECT pajzs, pancel, kotszer, phalanx, kard, manakristaly FROM users WHERE karakterazonosito='$id'")) die("A karakter felszereléseinek lekérése sikertelen");
		foreach($felszerelesek = mysqli_fetch_assoc($felszereleslekeres) as $felszerelesnev=>$ertek) $felszerelesertek["$felszerelesnev"] = $ertek;
		
		foreach($felszerelesertek as $name=>$vane)
		{
			if(!$vane) $felszereles["$name"] = $vane;
		}
		
		if(isset($felszereles))
		{
			print "
				<TR>
					<TD colspan='5'><H2><BR>Felszerelések:</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Tárgy</H3></TD>
					<TD align='center' colspan='2'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
			";
			foreach($felszereles as $felsz=>$ertek)
			{
				if(!$lekeres = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE item='$felsz'")) die("$felsz árának lekérése sikertelen");
				foreach($artomb = mysqli_fetch_row($lekeres) as $felszerelesar);
				
				$felszerelesvasarlas = vasarlas($actualmoney, $felszerelesar, $felsz, 1);
				
				if(!$felszereleslekeres = mysqli_query($_SESSION["conn"], "SELECT hpbonus, shieldbonus, penetbonus, dmgbonus, roundshield, manabonus FROM felszereles WHERE felszerelesnev='$felsz'")) die("Felszerelésbónuszok lekérése sikertelen");
				$felszerelesstatnevek = mysqli_fetch_fields($felszereleslekeres);
				foreach($felszerelesstatnevek = mysqli_fetch_assoc($felszereleslekeres) as $name=>$ertek) $felszerelesertekek["$name"] = $ertek;
			
				$felszleiras = "
					<TABLE align='center'>
						<TR>
				";
				
				foreach($felszerelesertekek as $felszereles=>$ertek)
				{
					switch($felszereles)
					{
						case "hpbonus":
							if($ertek != 100)
							{
								if($ertek > 100) $mod = "+";
								if($ertek < 100) $mod = "";
								$hpertek = $ertek-100;
								$hpbonus = "<TD align='center'>Életerő: $mod$hpertek%</TD>";
								$felszleiras = $felszleiras . $hpbonus;
							}
						break;
						case "shieldbonus":
							if($ertek != 100)
							{
								if($ertek > 100) $mod = "+";
								if($ertek < 100) $mod = "";
								$shieldertek = $ertek-100;
								$shieldbonus = "<TD align='center'>Pajzs: $mod$shieldertek%</TD>";
								$felszleiras = $felszleiras . $shieldbonus;
							}
						break;
						case "penetbonus":
							if($ertek != 0)
							{
								if($ertek > 0) $mod = "+";
								if($ertek < 0) $mod = "";
								$penetertek = $ertek;
								$penetbonus = "<TD align='center'>Penetráció: $mod$penetertek%</TD>";
								$felszleiras = $felszleiras . $penetbonus;
							}
						break;
						case "dmgbonus":
							if($ertek != 100)
							{
								if($ertek > 100) $mod = "+";
								if($ertek < 100) $mod = "";
								$dmgertek = $ertek-100;
								$dmgbonus = "<TD align='center'>Sebzés: $mod$dmgertek%</TD>";
								$felszleiras = $felszleiras . $dmgbonus;
							}
						break;
						case "roundshield":
							if($ertek != 0)
							{
								if($ertek > 0) $mod = "+";
								if($ertek < 0) $mod = "";
								$roundshieldertek = $ertek;
								$roundshieldbonus = "<TD align='center'>Pajzs: $mod$roundshieldertek%/kör</TD>";
								$felszleiras = $felszleiras . $roundshieldbonus;
							}
						break;
						case "manabonus":
							if($ertek != 100)
							{
								if($ertek > 100) $mod = "+";
								if($ertek < 100) $mod = "";
								$manaertek = $ertek-100;
								$manabonus = "<TD align='center'>Mana:: $mod$manaertek%</TD>";
								$felszleiras = $felszleiras . $manabonus;
							}
						break;
					}
				}
				
				$felszleiras = $felszleiras . "</TR></TABLE>";
				
				print "
					<TR>
						<TD align='right'>$felsz</TD>
						<TD align='center' colspan='2'>$felszleiras</TD>
						<TD align='center'>$felszerelesar</TD>
						<TD align='center'>$felszerelesvasarlas</TD>
					</TR>
				";
			}
		}
		
		
		if(!$statlekerdezes = mysqli_query($_SESSION["conn"], "SELECT szinthp, szintshield, szintmana, szintdmg, szintpenet FROM users WHERE karakterazonosito='$id'")) die("Karakterstatok lekérdezése siekertelen");
		foreach($statoszlopnevek = mysqli_fetch_assoc($statlekerdezes) as $name=>$ertek) $$name = $ertek;
		
		if(!$statarlekerdezes = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE itemtype='stat'")) die("Stat szintárak lekérdezése sikertelen");
		if(!$statnevlekerdezes = mysqli_query($_SESSION["conn"], "SELECT item  FROM costs WHERE itemtype='stat'")) die("Statnevek lekérdezése sikertelen");
		
		while($stattombnev = mysqli_fetch_row($statnevlekerdezes))
		{
			foreach($stattombnev as $statneve) $statn[] = $statneve;
		}
		while($statartomb = mysqli_fetch_row($statarlekerdezes))
		{
			foreach($statartomb as $ertek) $statarak[] = $ertek;
		}
		
		$szam = 0;
		foreach($statarak as $ertek)
		{
			$statnev = $statn[$szam];
			$statar[$statnev] = $ertek;
			$szam++;
		}
		
		foreach($statar as $statnev=>$ar)
		{
			$valtozonev = $statnev . "ar";
			$$valtozonev = $ar;
		}
		
		if(!$maxszintpenetleker = mysqli_query($_SESSION["conn"], "SELECT maxpenet FROM kasztok WHERE kasztnev='$kaszt'")) die("Max penetrációszint lekérdezése sikertelen");
		foreach($maxszintpenetracio = mysqli_fetch_row($maxszintpenetleker) as $maxszintpenet);
		
		$szinthpvasarlas = vasarlas($actualmoney, $szinthpar, "szinthp");
		$szintshieldvasarlas = vasarlas($actualmoney, $szintshieldar, "szintshield");
		$szintmanavasarlas = vasarlas($actualmoney, $szintmanaar, "szintmana");
		$szintdmgvasarlas = vasarlas($actualmoney, $szintdmgar, "szintdmg");
		
		if(!$hpinclekeres = mysqli_query($_SESSION["conn"], "SELECT hpinc FROM kasztok WHERE kasztnev='$kaszt'")) die("Hpemelkedés lekérése sikertelen");
		foreach($hpinctomb = mysqli_fetch_row($hpinclekeres) as $hpinc);
		
		$hpleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Növeli az életerőt</TD>
					<TD align='right'>+$hpinc/szint</TD>
				</TR>
			</TABLE>
		";
		
		if(!$shieldinclekeres = mysqli_query($_SESSION["conn"], "SELECT shieldinc FROM kasztok WHERE kasztnev='$kaszt'")) die("shieldemelkedés lekérése sikertelen");
		foreach($shieldinctomb = mysqli_fetch_row($shieldinclekeres) as $shieldinc);
		
		$shieldleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Növeli a pajzsot</TD>
					<TD align='right'>+$shieldinc/szint</TD>
				</TR>
			</TABLE>
		";
		
		if(!$manainclekeres = mysqli_query($_SESSION["conn"], "SELECT manainc FROM kasztok WHERE kasztnev='$kaszt'")) die("manaemelkedés lekérése sikertelen");
		foreach($manainctomb = mysqli_fetch_row($manainclekeres) as $manainc)
		
		$manaleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Növeli a manát</TD>
					<TD align='right'>+$manainc/szint</TD>
				</TR>
			</TABLE>
		";
		
		if(!$dmginclekeres = mysqli_query($_SESSION["conn"], "SELECT dmginc FROM kasztok WHERE kasztnev='$kaszt'")) die("dmgemelkedés lekérése sikertelen");
		foreach($dmginctomb  = mysqli_fetch_row($dmginclekeres) as $dmginc)
		
		$dmgleiras = "
			<TABLE align='center' width='100%'>
				<TR>
					<TD>Növeli a sebzést</TD>
					<TD align='right'>+$dmginc/szint</TD>
				</TR>
			</TABLE>
		";
		
		print "
				<TR>
					<TD colspan='5'><H2><BR>Statok:</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Stat</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Jelenlegi szint</TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
				<TR>
					<TD align='right'>Életerő</TD>
					<TD align='center'>$hpleiras</TD>
					<TD align='center'>$szinthpar</TD>
					<TD align='center'>$szinthp</TD>
					<TD align='center'>$szinthpvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Pajzs</TD>
					<TD align='center'>$shieldleiras</TD>
					<TD align='center'>$szintshieldar</TD>
					<TD align='center'>$szintshield</TD>
					<TD align='center'>$szintshieldvasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Mana</TD>
					<TD align='center'>$manaleiras</TD>
					<TD align='center'>$szintmanaar</TD>
					<TD align='center'>$szintmana</TD>
					<TD align='center'>$szintmanavasarlas</TD>
				</TR>
				<TR>
					<TD align='right'>Sebzés</TD>
					<TD align='center'>$dmgleiras</TD>
					<TD align='center'>$szintdmgar</TD>
					<TD align='center'>$szintdmg</TD>
					<TD align='center'>$szintdmgvasarlas</TD>
				</TR>

			";
		if($szintpenet < $maxszintpenet)
		{
			if(!$penetinclekeres = mysqli_query($_SESSION["conn"], "SELECT penetinc FROM kasztok WHERE kasztnev='$kaszt'")) die("penetemelkedés lekérése sikertelen");
			foreach($penetinctomb  = mysqli_fetch_row($penetinclekeres) as $penetinc);
				
			$penetleiras = "
				<TABLE align='center' width='100%'>
					<TR>
						<TD>Növeli a penetrációt</TD>
						<TD align='right'>+$penetinc%/szint</TD>
					</TR>
				</TABLE>
			";
			
			if($szintpenetar <= $actualmoney) $szintpenetvasarlas = "
				<FORM method='POST' action='vasarlasfeldolgoz.php'>
					<INPUT type='hidden' name='szintpenet' value='1'>
					<INPUT type='submit' name='submit' value='Vásárlás'>
				</FORM>
			";
			if($szintpenetar > $actualmoney) $szintpenetvasarlas = "Nincs elég pénzed!";
			print "
				<TR>
					<TD align='right'>Penetráció</TD>
					<TD align='center'>$penetleiras</TD>
					<TD align='center'>$szintpenetar</TD>
					<TD align='center'>$szintpenet</TD>
					<TD align='center'>$szintpenetvasarlas</TD>
				</TR>
			";
		}
		
		
		print "
				<TR>
					<TD colspan='5'><H2><BR>Képességek:</H2></TD>
				</TR>
				</TR>
					<TD align='center'><H3>Képesség</H3></TD>
					<TD align='center'><H3>Leírás</H3></TD>
					<TD align='center'><H3>Ár</H3></TD>
					<TD align='center'><H3>Jelenlegi szint</TD>
					<TD align='center'><H3>Vásárlás</H3></TD>
				</TR>
		";
		
		switch($kaszt)
		{
			case "warrior":
				if(!$passzivincleker = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='passziv'")) die("Passzív képesség fejlődéseének lekérése sikertelen");
				foreach($passzivinctomb = mysqli_fetch_row($passzivincleker) as $passzivinc);
				
				$passzivleiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Növeli a jelölést.</TD>
							<TD align='right'>+$passzivinc/szint</TD>
						</TR>
					</TABLE>
				";
				
				if(!$ability0aktivincleker = mysqli_query($_SESSION["conn"], "SELECT aktivkorinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 képesség aktív köridő lekérése sikertelen");
				foreach($ability0aktivinctomb = mysqli_fetch_row($ability0aktivincleker) as $ability0aktivkorinc);
				
				if(!$ability0reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability0reloadinctomb = mysqli_fetch_row($ability0reloadincleker) as $ability0reloadinc);
				
				$ability0leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>A célzott játékos nem használhat képességet</TD>
							<TD align='center'>Aktív körök: +$ability0aktivkorinc/szint</TD>
							<TD align='right'>Újratöltési idő: $ability0reloadinc kör/szint</TD>
						</TR>
					</TABLE>
				";
				
				if(!$ability1aktivincleker = mysqli_query($_SESSION["conn"], "SELECT aktivkorinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability1 képesség aktív köridő lekérése sikertelen");
				foreach($ability1aktivinctomb = mysqli_fetch_row($ability1aktivincleker) as $ability1aktivkorinc);
				
				if(!$ability1reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability1reloadinctomb = mysqli_fetch_row($ability1reloadincleker) as $ability1reloadinc);
				
				if(!$ability1ertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 értéknövekedésének lekérése sikertelen");
				foreach($ability1ertekinctomb = mysqli_fetch_row($ability1ertekinclekeres) as $ability1ertekinc);
				
				$ability1leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Az okozott sebzést pajzsként hasznosítja újra.</TD>
							<TD align='center'>Újrahasznosított pajzs: +$ability1ertekinc%/szint</TD>
							<TD align='center'>Aktív körök: +$ability1aktivkorinc/szint</TD>
							<TD align='right'>Újratöltési idő: $ability1reloadinc kör/szint</TD>
						</TR>
					</TABLE>
				";
			break;
			case "mage":
				if(!$passzivincleker = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='passziv'")) die("Passzív képesség fejlődéseének lekérése sikertelen");
				foreach($passzivinctomb = mysqli_fetch_row($passzivincleker) as $passzivinc);
				
				$passzivleiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Növeli az attackot.</TD>
							<TD align='right'>+$passzivinc/szint</TD>
						</TR>
					</TABLE>
				";
				
				if(!$ability0aktivincleker = mysqli_query($_SESSION["conn"], "SELECT aktivkorinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 képesség aktív köridő lekérése sikertelen");
				foreach($ability0aktivinctomb = mysqli_fetch_row($ability0aktivincleker) as $ability0aktivkorinc);
				
				if(!$ability0reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability0reloadinctomb = mysqli_fetch_row($ability0reloadincleker) as $ability0reloadinc);
				
				if(!$ability0ertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 értéknövekedésének lekérése sikertelen");
				foreach($ability0ertekinctomb = mysqli_fetch_row($ability0ertekinclekeres) as $ability0ertekinc);
				
				$ability0leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Növeli a kitérés esélyét.</TD>
							<TD align='center'>+$ability0ertekinc/szint</TD>
							<TD align='center'>Aktív körök: +$ability0aktivkorinc/szint</TD>
							<TD align='right'>Újratöltési idő: $ability0reloadinc kör/szint</TD>
						</TR>
					</TABLE>
				";
				
				if(!$ability1aktivincleker = mysqli_query($_SESSION["conn"], "SELECT aktivkorinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 képesség aktív köridő lekérése sikertelen");
				foreach($ability1aktivinctomb = mysqli_fetch_row($ability1aktivincleker) as $ability1aktivkorinc);
			
				if(!$ability1reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability1reloadinctomb = mysqli_fetch_row($ability1reloadincleker) as $ability1reloadinc);
				
				$ability1leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Leveszi az összes jelölést, ha a körben nem támad.</TD>
							<TD align='center'>Aktív körök: +$ability1aktivkorinc/szint</TD>
							<TD align='right'>Újratöltési idő: $ability1reloadinc kör/szint</TD>
						</TR>
					</TABLE>
				";
			break;
			case "paladin":
				if(!$passzivincleker = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='passziv'")) die("Passzív képesség fejlődéseének lekérése sikertelen");
				foreach($passzivinctomb = mysqli_fetch_row($passzivincleker) as $passzivinc);
				
				$passzivleiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Csökkenti a kapott sebzést.</TD>
							<TD align='right'>-$passzivinc%/szint</TD>
						</TR>
					</TABLE>
				";
				if(!$ability0aktivincleker = mysqli_query($_SESSION["conn"], "SELECT aktivkorinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 képesség aktív köridő lekérése sikertelen");
				foreach($ability0aktivinctomb = mysqli_fetch_row($ability0aktivincleker) as $ability0aktivkorinc);
				
				if(!$ability0reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability0reloadinctomb = mysqli_fetch_row($ability0reloadincleker) as $ability0reloadinc);
				
				if(!$ability0ertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 értéknövekedésének lekérése sikertelen");
				foreach($ability0ertekinctomb = mysqli_fetch_row($ability0ertekinclekeres) as $ability0ertekinc);
				
				$ability0leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Csökkenti a kapott sebzést.</TD>
							<TD align='center'>+$ability0ertekinc%/szint</TD>
							<TD align='center'>Aktív körök: +$ability0aktivkorinc/szint</TD>
							<TD align='right'>Újratöltési idő: $ability0reloadinc kör/szint</TD>
						</TR>
					</TABLE>
					";
					
				if(!$ability1reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability1reloadinctomb = mysqli_fetch_row($ability1reloadincleker) as $ability1reloadinc);
				
				$ability1leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Magára húzza a legkevesebb életerővel rendelkező szövetségest támadó ellenségek jelölését.</TD>
							<TD align='right'>Újratöltési idő: $ability1reloadinc kör/szint</TD>
						</TR>
					</TABLE>
				";
			break;
			case "healer":
				if(!$passzivertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='passziv'")) die("passziv értéknövekedésének lekérése sikertelen");
				foreach($passzivertekinctomb = mysqli_fetch_row($passzivertekinclekeres) as $passzivertekinc);
				
				$passzivleiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Körönként tölti a manát</TD>
							<TD align='right'>Körönként +$passzivertekinc mana/szint</TD>
						</TR>
					</TABLE>
					";
				
				if(!$ability0reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability0reloadinctomb = mysqli_fetch_row($ability0reloadincleker) as $ability0reloadinc);
				
				if(!$ability0ertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 értéknövekedésének lekérése sikertelen");
				foreach($ability0ertekinctomb = mysqli_fetch_row($ability0ertekinclekeres) as $ability0ertekinc);
				
				$ability0leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Tölti a saját, és egy kiválasztott csapattárs pajzsát.</TD>
							<TD align='center'>+$ability0ertekinc pajzs/szint</TD>
							<TD align='right'>Újratöltési idő: $ability0reloadinc kör/szint</TD>
						</TR>
					</TABLE>
					";
					
				if(!$ability1reloadincleker = mysqli_query($_SESSION["conn"], "SELECT reloadinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 újratöltési idő csökkenésének lekérése sikertelen");
				foreach($ability1reloadinctomb = mysqli_fetch_row($ability1reloadincleker) as $ability1reloadinc);
				
				if(!$ability1ertekinclekeres = mysqli_query($_SESSION["conn"], "SELECT ertekinc FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 értéknövekedésének lekérése sikertelen");
				foreach($ability1ertekinctomb = mysqli_fetch_row($ability1ertekinclekeres) as $ability1ertekinc);
				
				$ability1leiras = "
					<TABLE align='center' width='100%'>
						<TR>
							<TD>Tölti a saját, és egy kiválasztott csapattárs életerejét.</TD>
							<TD align='center'>+$ability1ertekinc életerő/szint</TD>
							<TD align='right'>Újratöltési idő: $ability1reloadinc kör/szint</TD>
						</TR>
					</TABLE>
					";
		}
		
		if(!$passzivlekernev = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='passziv' AND ownerkaszt='$kaszt'")) die("Passzív képesség lekérdezésese sikertelen");
		foreach($passzivnev = mysqli_fetch_row($passzivlekernev) as $passziv);
		
		if(!$maxszintpasszivleker = mysqli_query($_SESSION["conn"], "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='passziv'")) die("Passzív képesség max szintjének lekérése sikertelen");
		foreach($maxpasszivszinttomb = mysqli_fetch_row($maxszintpasszivleker) as $maxszintpassziv);
		
		if(!$szintpasszivleker = mysqli_query($_SESSION["conn"], "SELECT szintpassziv FROM users WHERE karakterazonosito='$id'")) die("Passzív képesség szintjének lekérése sikertelen");
		foreach($szintpasszivlekertomb = mysqli_fetch_row($szintpasszivleker) as $szintpassziv);
		
		if(!$passzivarleker = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE item='$passziv'")) die("Passzív képesség árának lekérése sikertelen");
		foreach($passzivartomb = mysqli_fetch_row($passzivarleker) as $passzivar);
		
		if($passzivar <= $actualmoney) $passzivvasarlas = "
				<FORM method='POST' action='vasarlasfeldolgoz.php'>
					<INPUT type='hidden' name='szintpassziv' value='1'>
					<INPUT type='submit' name='submit' value='Vásárlás'>
				</FORM>
			";
		if($passzivar > $actualmoney) $passzivvasarlas = "Nincs elég pénzed!";
		if($szintpassziv < $maxszintpassziv)
		{
			print "
					<TR>
						<TD align='right'>$passziv</TD>
						<TD align='center'>$passzivleiras</TD>
						<TD align='center'>$passzivar</TD>
						<TD align='center'>$szintpassziv</TD>
						<TD align='center'>$passzivvasarlas</TD>
					</TR>
			";
		}
		
		if(!$ability0lekernev = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='ability0' AND ownerkaszt='$kaszt'")) die("Passzív képesség lekérdezésese sikertelen");
		foreach($ability0nev = mysqli_fetch_row($ability0lekernev) as $ability0);
		
		if(!$maxszintability0leker = mysqli_query($_SESSION["conn"], "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability0'")) die("ability0 képesség max szintjének lekérése sikertelen");
		foreach($maxability0szinttomb = mysqli_fetch_row($maxszintability0leker) as $maxszintability0);
	
		if(!$szintability0leker = mysqli_query($_SESSION["conn"], "SELECT szintability0 FROM users WHERE karakterazonosito='$id'")) die("ability0 képesség szintjének lekérése sikertelen");
		foreach($szintability0lekertomb = mysqli_fetch_row($szintability0leker) as $szintability0);
		
		if(!$ability0arleker = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE item='$ability0'")) die("ability0 képesség árának lekérése sikertelen");
		foreach($ability0artomb = mysqli_fetch_row($ability0arleker) as $ability0ar);
		
		if($ability0ar <= $actualmoney) $ability0vasarlas = "
				<FORM method='POST' action='vasarlasfeldolgoz.php'>
					<INPUT type='hidden' name='szintability0' value='1'>
					<INPUT type='submit' name='submit' value='Vásárlás'>
				</FORM>
		";
		if($ability0ar > $actualmoney) $ability0vasarlas = "Nincs elég pénzed!";
		if($szintability0 < $maxszintability0) 
		{		
		print "
				<TR>
					<TD align='right'>$ability0</TD>
					<TD align='center'>$ability0leiras</TD>
					<TD align='center'>$ability0ar</TD>
					<TD align='center'>$szintability0</TD>
					<TD align='center'>$ability0vasarlas</TD>
				</TR>
		";
		}
		
		if(!$ability1lekernev = mysqli_query($_SESSION["conn"], "SELECT abilityname FROM abilities WHERE abilitytype='ability1' AND ownerkaszt='$kaszt'")) die("Passzív képesség lekérdezésese sikertelen");
		foreach($ability1nev = mysqli_fetch_row($ability1lekernev) as $ability1);
	
		if(!$maxszintability1leker = mysqli_query($_SESSION["conn"], "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilitytype='ability1'")) die("ability1 képesség max szintjének lekérése sikertelen");
		foreach($maxability1szinttomb = mysqli_fetch_row($maxszintability1leker) as $maxszintability1);
		
		if(!$szintability1leker = mysqli_query($_SESSION["conn"], "SELECT szintability1 FROM users WHERE karakterazonosito='$id'")) die("ability1 képesség szintjének lekérése sikertelen");
		foreach($szintability1lekertomb = mysqli_fetch_row($szintability1leker) as $szintability1);
		
		if(!$ability1arleker = mysqli_query($_SESSION["conn"], "SELECT cost FROM costs WHERE item='$ability1'")) die("ability1 képesség árának lekérése sikertelen");
		foreach($ability1artomb = mysqli_fetch_row($ability1arleker) as $ability1ar);
		
		if($ability1ar <= $actualmoney) $ability1vasarlas = "
			<FORM method='POST' action='vasarlasfeldolgoz.php'>
					<INPUT type='hidden' name='szintability1' value='1'>
					<INPUT type='submit' name='submit' value='Vásárlás'>
				</FORM>
		";
		if($ability1ar > $actualmoney) $ability1vasarlas = "Nincs elég pénzed!";
		if($szintability1 < $maxszintability1)
		{		
		print "
				<TR>
					<TD align='right'>$ability1</TD>
					<TD align='center'>$ability1leiras</TD>
					<TD align='center'>$ability1ar</TD>
					<TD align='center'>$szintability1</TD>
					<TD align='center'>$ability1vasarlas</TD>
				</TR>
		";
		}
		
		
		
		
		print "
			</TABLE>
		";
	}
	
	function vasarlas($actualmoney, $value, $name, $maxset = 0)
	{
		$max = $actualmoney/$value;
		settype($max, "integer");
		if($maxset) $max = $maxset;
		
		
		if($value <= $actualmoney)
		{
			return "
				<FORM method='POST' action='vasarlasfeldolgoz.php'>
					<INPUT type='number' min='1' max='$max' name='$name' value='$max'>
					<INPUT type='submit' name='submit' value='Vásárlás'>
				</FORM>
			";
		}
		if($value > $actualmoney) return "Nincs elég pénzed!";
	}
	
?>