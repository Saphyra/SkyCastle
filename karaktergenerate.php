<?php
	include("connection.php");
	if(isset($_POST["username"]))
	{
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["password"] = $_POST["password"];
		$siker = karaktergenerate(0, 0, 0, $_POST["username"], $_POST["password"], $_POST["kaszt"]);
		if($siker) header("Location:login.php");
	}


	function karaktergenerate($szint, $enemy, $bot = 1, $username = 0, $password = 0, $kaszt = 0)
	{
		$conn = $_SESSION["conn"];
		settype($szint, "integer");
		$npc["bot"] = $bot;
		if(!$bot)
		{
			$npc["username"] = $username;
			$npc["password"] = $password;
			$npc["kaszt"] = $kaszt;
			$npc["karakterszint"] = 0;
			$npc["enemy"] = 0;
			$tabla = "users";
			$npc["cloaked"] = 0;
		}
		
		if($bot)
		{
			$szam = $szint*1.5;
			settype($szam, "integer");
			$karakterszint = rand($szint/2, $szam);
			$npc["karakterszint"] = $karakterszint;
			$tabla  = "npcs";
		}

		do
		{

			$karakterazonosito = "id" . rand(1, 1000);
			
			if(!$azonositonpc = mysqli_query($conn, "SELECT karakterazonosito FROM npcs WHERE karakterazonosito='$karakterazonosito'")) die("NPC-k id lekérdezése sikertelen");
			if(!$azonositousers = mysqli_query($conn, "SELECT karakterazonosito FROM users WHERE karakterazonosito='$karakterazonosito'")) die("Felhasználók id lekérdezése sikertelen");
			
			$idset = True;
			while($tomb1 = mysqli_fetch_row($azonositonpc))
			{
				foreach($tomb1 as $ertek1) if($karakterazonosito == $ertek1) $idset = False;
			}
			while($tomb2 = mysqli_fetch_row($azonositousers))
			{
				foreach($tomb2 as $ertek2) if($karakterazonosito == $ertek2) $idset = False;
			}
		}
		while(!$idset);
		$npc["karakterazonosito"] = $karakterazonosito;
		if(!$battlefeltoltes = mysqli_query($_SESSION["conn"], "INSERT INTO battle (karakterazonosito, ammo, target, lastattack) VALUES ('$karakterazonosito', '0', '0', '1')")) die("Feltöltés a csatatáblába sikertelen");
			
		if($bot)
		{
			$kasztazonosito = rand(1, 4);
			switch($kasztazonosito)
			{
				case 1:
					$kaszt = "warrior";
				break;
				case 2:
					$kaszt = "mage";
				break;
				case 3:
					$kaszt = "paladin";
				break;
				case 4:
					$kaszt = "healer";
				break;
			}
			$npc["kaszt"] = $kaszt;
			$npc["enemy"] = $enemy;
		}
			
		$npc["actualmoney"] = 50000;
			
		$kioszt = $npc["karakterszint"];
		$szinthp = 0;
		$szintshield = 0;
		$szintpenet = 0;
		$szintmana = 0;
		$szintdmg = 0;
		$szintpassziv = 0;
		$szintability0 = 0;
		$szintability1 = 0;
		for($kioszt = $npc["karakterszint"]; $kioszt > 0; $kioszt--)
		{
			$setted = False;
			do
			{
				$statnovel = rand(1, 8);
				switch($statnovel)
				{
					case 1:
						$szinthp++;
						$setted = True;
					break;
					case 2:
						$szintshield++;
						$setted = True;
					break;
					case 3:
						if(!$maxpenetleker = mysqli_query($conn, "SELECT maxpenet FROM kasztok WHERE kasztnev='$kaszt'")) die("Maximum penetráció lekérése sikertelen");
						foreach($maxpenettomb = mysqli_fetch_row($maxpenetleker) as $maxpenet);
						
						if($szintpenet < $maxpenet)
						{
							$szintpenet++;
							$setted = True;
						}
					break;
					case 4:
						$szintmana++;
						$setted = True;
					break;
					case 5:
						$szintdmg++;
						$setted = True;
					break;
					case 6:
						if(!$abilitynameleker = mysqli_query($conn, "SELECT passziv FROM kasztok where kasztnev='$kaszt'")) die("Képességnév lekérdezése sikertelen");
						foreach($abname = mysqli_fetch_row($abilitynameleker) as $abilityname);
						
						if(!$maxszintpasszivleker = mysqli_query($conn, "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilityname='$abilityname'")) die("Maximum passzív képesség szint lekérdezése sikertelen");
						foreach($maxint = mysqli_fetch_row($maxszintpasszivleker) as $maxszintpassziv);
						
						if($szintpassziv < $maxszintpassziv)
						{
							$szintpassziv++;
							$setted = True;
						}
					break;
					case 7:
						if(!$abilitynameleker = mysqli_query($conn, "SELECT ability0 FROM kasztok where kasztnev='$kaszt'")) die("Képességnév lekérdezése sikertelen");
						foreach($abname = mysqli_fetch_row($abilitynameleker) as $abilityname);
						
						if(!$maxszintability0leker = mysqli_query($conn, "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilityname='$abilityname'")) die("Maximum ability0 képesség szint lekérdezése sikertelen");
						foreach($maxint = mysqli_fetch_row($maxszintability0leker) as $maxszintability0);
						
						if($szintability0 < $maxszintability0)
						{
							$szintability0++;
							$setted = True;
						}
					break;
					case 8:
						if(!$abilitynameleker = mysqli_query($conn, "SELECT ability1 FROM kasztok where kasztnev='$kaszt'")) die("Képességnév lekérdezése sikertelen");
						foreach($abname = mysqli_fetch_row($abilitynameleker) as $abilityname);
						
						if(!$maxszintability1leker = mysqli_query($conn, "SELECT maxlevel FROM abilities WHERE ownerkaszt='$kaszt' AND abilityname='$abilityname'")) die("Maximum ability0 képesség szint lekérdezése sikertelen");
						foreach($maxint = mysqli_fetch_row($maxszintability1leker) as $maxszintability1);
						
						if($szintability1 < $maxszintability1)
						{
							$szintability1++;
							$setted = True;
						}
					break;						
				}
			}
			while (!$setted);
		}
		$npc["szinthp"] =  $szinthp;
		$npc["szintshield"] = $szintshield;
		$npc["szintpenet"] = $szintpenet;
		$npc["szintmana"] = $szintmana;
		$npc["szintdmg"] = $szintdmg;
		$npc["szintpassziv"] = $szintpassziv;
		$npc["szintability0"] = $szintability0;
		$npc["szintability1"] = $szintability1;
		
		$npc["hppotnum"] = 0;
		$npc["shieldpotnum"] = 0;
		$npc["manapotnum"] = 0;
		$npc["x2num"] = 0;
		$npc["x3num"] = 0;
		$npc["x4num"] = 0;
		$npc["empnum"] = 0;
		$npc["ishnum"] = 0;
		$npc["pldnum"] = 0;
		$npc["cloaknum"] = 0;
			
		if($bot)
		{
			if(rand(0, 1)) $npc["hppotnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["shieldpotnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["manapotnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["x2num"] = rand(1, 2000);
			if(rand(0, 1)) $npc["x3num"] = rand(1, 1000);
			if(!rand(0, 4)) $npc["x4num"] = rand(1, 500);
			if(rand(0, 1)) $npc["empnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["ishnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["pldnum"] = rand(1, 5);
			if(rand(0, 1)) $npc["cloaknum"] = rand(1, 10);
		}
		
		if(!$basehplekeres = mysqli_query($conn, "SELECT basehp FROM kasztok WHERE kasztnev='$kaszt'")) Die("A karakter alaphp lekérése sikertelen");
		foreach($bhp = mysqli_fetch_row($basehplekeres) as $basehp);
		
		if(!$hpincleker = mysqli_query($conn, "SELECT hpinc FROM kasztok WHERE kasztnev ='$kaszt'")) die("A karakter hpnövekedésének lekérése sikertelen");
		foreach($hpi = mysqli_fetch_row($hpincleker) as $hpinc);
		
		$npc["basichp"] = $basehp+$npc["szinthp"]*$hpinc;
			
		$npc["hpboosterrounds"] = 0;
		$npc["shieldboosterrounds"] = 0;
		$npc["manaboosterrounds"] = 0;
		$npc["dmgboosterrounds"] = 0;
		$npc["attackboosterrounds"] = 0;
		$npc["accurboosterrounds"] = 0;
		$npc["pajzs"] = 0;
		$npc["pancel"] = 0;
		$npc["kotszer"] = 0;
		$npc["phalanx"] = 0;
		$npc["kard"] = 0;
		$npc["manakristaly"] = 0;
		$npc["alap"] = 1;
		if($bot)
		{
			if(rand(0, 1)) $npc["hpboosterrounds"] = rand(1, 500);
			if(rand(0, 1)) $npc["shieldboosterrounds"] = rand(1, 500);
			if(rand(0, 1)) $npc["manaboosterrounds"] = rand(1, 500);
			if(rand(0, 1)) $npc["dmgboosterrounds"] = rand(1, 500);
			if(rand(0, 1)) $npc["attackboosterrounds"] = rand(1, 500);
			if(rand(0, 1)) $npc["accurboosterrounds"] = rand(1, 500);
			$npc["pajzs"] = rand(0, 1);
			$npc["pancel"] = rand(0, 1);
			$npc["kotszer"] = rand(0, 1);
			$npc["phalanx"] = rand(0, 1);
			$npc["kard"] = rand(0, 1);
			$npc["manakristaly"] = rand(0, 1);
		}
			
			
		foreach($npc as $kulcs=>$ertek)
		{
			if($ertek == 1) $tomb1[] = $kulcs;
		}
		foreach($tomb1 as $ertek)
		{
			switch($ertek)
			{
				case "pajzs":
					$tomb[] = $ertek;
				break;
				case "pancel":
					$tomb[] = $ertek;
				break;
				case "kotszer":
					$tomb[] = $ertek;
				break;
				case "phalanx":
					$tomb[] = $ertek;
				break;
				case "kard":
					$tomb[] = $ertek;
				break;
				case"manakristaly":
					$tomb[] = $ertek;
				break;
				case "alap":
					$tomb[] = $ertek;
				break;
			}
		}
		$sql = "SELECT priority FROM felszereles WHERE felszerelesnev='";
		foreach($tomb as $ertek)
		{
			$sql = "$sql" . "$ertek" . "' OR felszerelesnev='";
		}
		$sqlhossz = strlen($sql)-20;
		$sql = substr($sql, 0, $sqlhossz);
		$sql = "$sql" . " ORDER BY priority LIMIT 1";
		if(!$actfelsz = mysqli_query($conn, "$sql")) die("A prioritás lekérdezése sikertelen");
		foreach($felsz = mysqli_fetch_row($actfelsz) as $priority);
		
		if(!$actfelsz = mysqli_query($conn, "SELECT felszerelesnev FROM felszereles WHERE priority='$priority'")) die("A felszerelésnév lekérdezése sikertelen");
		foreach($felsznev = mysqli_fetch_row($actfelsz) as $activefelszereles);
		
		$npc["activefelszereles"] = $activefelszereles;
			
		if(!$hpfelszlekeres = mysqli_query($conn, "SELECT hpbonus FROM felszereles WHERE priority='$priority'")) die("Felszerelés HP értékének lekérése sikertelen");
		foreach($hpfelsz = mysqli_fetch_row($hpfelszlekeres) as $hpfelszbonus);
		
		$npc["hpfelszbonus"] = $hpfelszbonus;
		
		$npc["maxhp"] = $npc["basichp"]*($npc["hpfelszbonus"]/100);
		if($npc["hpboosterrounds"]) $npc["maxhp"] *= 1.25;
		
		$npc["actualhp"] = $npc["maxhp"];
		
		if($bot)
		{
			$damaged = rand(0, 1);
			if($damaged) $npc["actualhp"] = $npc["maxhp"]*(rand(1, 99)/100);
		}
		
		if(!$baseshieldlekeres = mysqli_query($conn, "SELECT baseshield FROM kasztok WHERE kasztnev='$kaszt'")) Die("A karakter alapshield lekérése sikertelen");
		foreach($bshd = mysqli_fetch_row($baseshieldlekeres) as $baseshield);
		
		if(!$shieldincleker = mysqli_query($conn, "SELECT shieldinc FROM kasztok WHERE kasztnev ='$kaszt'")) die("A karakter shieldnövekedésének lekérése sikertelen");
		foreach($shdi = mysqli_fetch_row($shieldincleker) as $shieldinc);
		
		$npc["basicshield"] = $baseshield+$npc["szintshield"]*$shieldinc;
			
		if(!$shieldfelszlekeres = mysqli_query($conn, "SELECT shieldbonus FROM felszereles WHERE priority='$priority'")) die("Felszerelés Shield értékének lekérése sikertelen");
		foreach($shieldfelsz = mysqli_fetch_row($shieldfelszlekeres) as $shieldfelszbonus);
		
		$npc["shieldfelszbonus"] = $shieldfelszbonus;
		
		$npc["maxshield"] = $npc["basicshield"]*($npc["shieldfelszbonus"]/100);
		if($npc["shieldboosterrounds"]) $npc["maxshield"] *= 1.25;
		
		$npc["actualshield"] = $npc["maxshield"];
		
		if($bot)
		{
			$damaged = rand(0, 1);
			if($damaged) $npc["actualshield"] = $npc["maxshield"]*(rand(1, 99)/100);
		}
			
		if(!$penetfelszlekeres = mysqli_query($conn, "SELECT penetbonus FROM felszereles WHERE priority='$priority'")) die("Felszerelés penetráció értékének lekérése sikertelen");
		foreach($penetfelsz = mysqli_fetch_row($penetfelszlekeres) as $penetfelszbonus);
		
		$npc["penetfelszbonus"] = $penetfelszbonus;
		
		if(!$penetincleker = mysqli_query($conn, "SELECT penetinc FROM kasztok WHERE kasztnev ='$kaszt'")) die("A karakter penetrációnövekedésének lekérése sikertelen");
		foreach($shdi = mysqli_fetch_row($penetincleker) as $penetinc);
		
		$npc["ertekpenet"] = 70+$npc["szintpenet"]*$penetinc+$npc["penetfelszbonus"];
			
		if(!$manaincleker = mysqli_query($conn, "SELECT manainc FROM kasztok WHERE kasztnev ='$kaszt'")) die("A karakter mananövekedésének lekérése sikertelen");
		foreach($manai = mysqli_fetch_row($manaincleker) as $manainc);
		
		if(!$basemanalekeres = mysqli_query($conn, "SELECT basemana FROM kasztok WHERE kasztnev='$kaszt'")) Die("A karakter alapmana lekérése sikertelen");
		foreach($bmana = mysqli_fetch_row($basemanalekeres) as $basemana);
		
		$npc["basicmana"] = $basemana+$npc["szintmana"]*$manainc;
		
		if(!$manafelszlekeres = mysqli_query($conn, "SELECT manabonus FROM felszereles WHERE priority='$priority'")) die("Felszerelés mana értékének lekérése sikertelen");
		foreach($manafelsz = mysqli_fetch_row($manafelszlekeres) as $manafelszbonus);
		
		$npc["manafelszbonus"] = $manafelszbonus;
		
		$npc["maxmana"] = $npc["basicmana"]*($npc["manafelszbonus"]/100);
		if($npc["manaboosterrounds"]) $npc["maxmana"] *= 1.25;
		
		$npc["actualmana"] = $npc["maxmana"];
		
		if($bot)
		{
			$damaged = rand(0, 1);
			if($damaged) $npc["actualmana"] = $npc["maxmana"]*(rand(1, 99)/100);
		}
		settype($npc["actualmana"], "integer");
		settype($npc["maxmana"], "integer");
			
		if(!$basedmglekeres = mysqli_query($conn, "SELECT basedmg FROM kasztok WHERE kasztnev='$kaszt'")) die("A karakter alapsebzés lekérése sikertelen");
		foreach($bdmg = mysqli_fetch_row($basedmglekeres) as $basedmg);
		
		if(!$dmgincleker = mysqli_query($conn, "SELECT dmginc FROM kasztok WHERE kasztnev ='$kaszt'")) die("A karakter dmgnövekedésének lekérése sikertelen");
		foreach($dmgi = mysqli_fetch_row($dmgincleker) as $dmginc);
		
		$npc["basicdmg"] = $basedmg+$dmginc*$npc["szintdmg"];
		
		if(!$dmgfelszlekeres = mysqli_query($conn, "SELECT dmgbonus FROM felszereles WHERE priority='$priority'")) die("Felszerelés dmg értékének lekérése sikertelen");
		foreach($dmgfelsz = mysqli_fetch_row($dmgfelszlekeres) as $dmgfelszbonus);
		
		$npc["dmgfelszbonus"] = $dmgfelszbonus;
		
		if(!$damagemultiplierlekeres = mysqli_query($conn, "SELECT damagemultiplier FROM kasztok WHERE kasztnev='$kaszt'")) die("A karakter sebzésmultiplikátorának lekérése sikertelen");
		foreach($dmgmult = mysqli_fetch_row($damagemultiplierlekeres) as $dmgmultiplierlevel);
		
		$npc["dmgmultiplierlevel"] = $dmgmultiplierlevel;
		
		$npc["actualdmg"] = $npc["basicdmg"]*($npc["dmgfelszbonus"]/100);
		if($npc["dmgboosterrounds"]) $npc["actualdmg"] *= 1.25;
		settype($npc["actualdmg"], "integer");
		
		$npc["activeability0"] = 0;
		
		$npc["reloadability0"] = 0;
			
		if($bot)
		{
			if(rand(0, 1))
			{
				if(!$abilitynamelekerdezes = mysqli_query($conn, "SELECT ability0 FROM kasztok WHERE kasztnev='$kaszt'")) die("ability0 képesség nevének lekérdezése sikertelen");
				foreach($abilitynamelekeres = mysqli_fetch_row($abilitynamelekerdezes) as $abilityname);
				
				if(!$maxreloadkor = mysqli_query($conn, "SELECT reload FROM abilities WHERE abilityname='$abilityname'")) die("Képesség max visszatöltési idejének lekérése sikertelen");
				foreach($reloadkor = mysqli_fetch_row($maxreloadkor) as $maxreload);
				
				if(!$reloadinclekeres = mysqli_query($conn, "SELECT reloadinc FROM abilities WHERE abilityname='$abilityname'")) die("A képesség újratöltési idő csökkenésének lekérése sikertelen");
				foreach($reloadincleker = mysqli_fetch_row($reloadinclekeres) as $reloadinc);
				
				$reload = $maxreload+$npc["szintability0"]*$reloadinc;
				$npc["reloadability0"] = rand(1, $reload);
			}
		}
		
		$npc["activeability1"] = 0;
		
		$npc["reloadability1"] = 0;
		
		if($bot)
		{
			if(rand(0, 1))
			{
				if(!$abilitynamelekerdezes = mysqli_query($conn, "SELECT ability1 FROM kasztok WHERE kasztnev='$kaszt'")) die("ability1 képesség nevének lekérdezése sikertelen");
				foreach($abilitynamelekeres = mysqli_fetch_row($abilitynamelekerdezes)as $abilityname);
				
				if(!$maxreloadkor = mysqli_query($conn, "SELECT reload FROM abilities WHERE abilityname='$abilityname'")) die("Képesség max visszatöltési idejének lekérése sikertelen");
				foreach($reloadkor = mysqli_fetch_row($maxreloadkor) as $maxreload);
				
				if(!$reloadinclekeres = mysqli_query($conn, "SELECT reloadinc FROM abilities WHERE abilityname='$abilityname'")) die("A képesség újratöltési idő csökkenésének lekérése sikertelen");
				foreach($reloadincleker = mysqli_fetch_row($reloadinclekeres) as $reloadinc);
				
				$reload = $maxreload+$npc["szintability1"]*$reloadinc;
				$npc["reloadability1"] = rand(1, $reload);
			}
		}
		
		$npc["hppotactive"] = 0;
		$npc["hppotreload"] = 0;
		$npc["shieldpotactive"] = 0;
		$npc["shieldpotreload"] = 0;
		$npc["manapotactive"] = 0;
		$npc["manapotreload"] = 0;
		$npc["empactive"] = 0;
		$npc["empreload"] = 0;
		$npc["ishactive"] = 0;
		$npc["ishreload"] = 0;
		$npc["pldactive"] = 0;
		$npc["pldreload"] = 0;
		if($bot)
		{			
			$potactive = 0;
			if($npc["actualhp"] != $npc["maxhp"]) $potactive = rand(0, 1);
			if($potactive) $npc["hppotactive"] = rand(1, 5);
			if($potactive) $npc["hppotreload"] = 20-$npc["hppotactive"];
			$hpreload = rand(0, 1);
			if(!$npc["hppotactive"]) if($hpreload) $npc["hppotreload"] = rand(1, 15);
			
			$potactive = 0;
			if($npc["actualshield"] != $npc["maxshield"]) $potactive = rand(0, 1);
			if($potactive) $npc["shieldpotactive"] = rand(1, 5);
			
			if($potactive) $npc["shieldpotreload"] = 20-$npc["shieldpotactive"];
			$shieldreload = rand(0, 1);
			if(!$npc["shieldpotactive"]) if($shieldreload) $npc["shieldpotreload"] = rand(1, 15);
			
			$potactive = 0;
			if($npc["actualmana"] != $npc["maxmana"]) $potactive = rand(0, 1);
			if($potactive) $npc["manapotactive"] = rand(1, 5);
			
			if($potactive) $npc["manapotreload"] = 20-$npc["manapotactive"];
			$manareload = rand(0, 1);
			if(!$npc["manapotactive"]) if($manareload) $npc["manapotreload"] = rand(1, 15);
			
			if($emprel = rand(0, 1)) $npc["empreload"] = rand(1, 20);
			
			if($ishrel = rand(0, 1)) $npc["ishreload"] = rand(1, 20);
			
			if(!$pldact = rand(0, 7)) $npc["pldactive"] = rand(1, 5);
			
			$pldre = rand(0, 1);
			if($pldre) $npc["pldreload"] = rand(1, 20);
			
			$cl = rand(0, 4);
			$npc["cloaked"] = 0;
			if(!$cl) $npc["cloaked"] = 1;
		}

			$sqloszlop = "(";
			$sqlvalue = "VALUES ('";
			foreach($npc as $kulcs=>$value)
			{
				$sqloszlop = "$sqloszlop" . "$kulcs" . ", ";
				$sqlvalue = "$sqlvalue" . "$value" . "', '";
			}
			
			$sqloszlophossz = strlen($sqloszlop)-2;
			$sqloszlop = substr($sqloszlop, 0, $sqloszlophossz);
			
			$sqlvaluehossz = strlen($sqlvalue)-3;
			$sqlvalue = substr($sqlvalue, 0, $sqlvaluehossz);
			
			$sqloszlop = "$sqloszlop" . ")";
			$sqlvalue = "$sqlvalue" . ")";
			
			$sql = "INSERT INTO " . "$tabla" . " " . "$sqloszlop" . " " . "$sqlvalue";
			
			$feltolt = mysqli_query($conn, "$sql");
			if(!$feltolt) die("A feltöltés sikertelen");

		if($feltolt) return 1;
	}

?>