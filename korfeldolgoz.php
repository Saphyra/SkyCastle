<?php
	include("connection.php");
	
	//==========================ADATOK BEKÉRÉSE===================
	
	if(!$abilitylekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM abilities")) die("Képességek lekérése sikertelen");
	while($abilitytomb = mysqli_fetch_assoc($abilitylekeres))
	{
		$ownerkaszt = $abilitytomb["ownerkaszt"];
		$abilitytype = $abilitytomb["abilitytype"];
		$tombnev = "$ownerkaszt" . "$abilitytype";
		foreach($abilitytomb as $name=>$ertek)
		{
			$tarolo["$tombnev"]["$name"] = $ertek;
		}
	}
	if(!$pldlekeres = mysqli_query($_SESSION["conn"], "SELECT specialertek FROM specials WHERE specialname='pld'")) die("pld lekérése sikertelen");
	foreach($pldtomb = mysqli_fetch_row($pldlekeres) as $ertek) $tarolo["pld"]["pldbonus"] = $ertek;
	
	if(!$potilekeres = mysqli_query($_SESSION["conn"], "SELECT specialname, specialertek FROM specials WHERE specialtype='potion'")) die("potik lekérése sikertelen");
	while($potitomb = mysqli_fetch_assoc($potilekeres))
	{
		$specialname = $potitomb["specialname"];
		$tarolo["$specialname"]["specialertek"] = $potitomb["specialertek"];
	}
	if(!$potihasznallekeres = mysqli_query($_SESSION["conn"], "SELECT specialname, manausage, reload, aktivkor FROM specials WHERE specialtype='potion'")) die("potionok lekérése siekrtelen");
	while($potiontomb = mysqli_fetch_assoc($potihasznallekeres))
	{
		$specialname = $potiontomb["specialname"];
		$tarolo["$specialname"]["manausage"] = $potiontomb["manausage"];
		$tarolo["$specialname"]["reload"] = $potiontomb["reload"];
		$tarolo["$specialname"]["aktivkor"] = $potiontomb["aktivkor"];
	}
	if(!$extralekeres = mysqli_query($_SESSION["conn"], "SELECT specialname, manausage, reload, aktivkor FROM specials WHERE specialtype='extra'")) die("Extrák lekérése sikertelen");
	while($extratomb = mysqli_fetch_assoc($extralekeres))
	{
		$specialname = $extratomb["specialname"];
		$tarolo["$specialname"]["manausage"] = $extratomb["manausage"];
		$tarolo["$specialname"]["reload"] = $extratomb["reload"];
		$tarolo["$specialname"]["aktivkor"] = $extratomb["aktivkor"];
	}
	
	if(!$ammolekeres = mysqli_query($_SESSION["conn"], "SELECT specialname, manausage FROM specials WHERE specialtype='ammo'")) die("Lőszerek lekérése siekrtelen");
	while($ammotomb = mysqli_fetch_assoc($ammolekeres))
	{
		$specialname = $ammotomb["specialname"];
		$tarolo["$specialname"]["manausage"] = $ammotomb["manausage"];
	}
	if(!$felszereleslekeres = mysqli_query($_SESSION["conn"], "SELECT felszerelesnev, hpbonus, shieldbonus, penetbonus, dmgbonus, manabonus FROM felszereles")) die("Felszerelések bekérése sikertelen");
	while($felszerelestomb = mysqli_fetch_assoc($felszereleslekeres))
	{
		$felszerelesnev = $felszerelestomb["felszerelesnev"];
		$tarolo["$felszerelesnev"]["hpbonus"] = $felszerelestomb["hpbonus"];
		$tarolo["$felszerelesnev"]["shieldbonus"] = $felszerelestomb["shieldbonus"];
		$tarolo["$felszerelesnev"]["penetbonus"] = $felszerelestomb["penetbonus"];
		$tarolo["$felszerelesnev"]["dmgbonus"] = $felszerelestomb["dmgbonus"];
		$tarolo["$felszerelesnev"]["manabonus"] = $felszerelestomb["manabonus"];
	}
	if(!$penetincleker = mysqli_query($_SESSION["conn"], "SELECT penetinc, kasztnev, basepenet FROM kasztok")) die("Penetinc lekérése sikertelen");
	while($penetinctomb = mysqli_fetch_assoc($penetincleker))
	{
		$kasztnev = $penetinctomb["kasztnev"];
		$tarolo["$kasztnev"]["penetinc"] = $penetinctomb["penetinc"];
		$tarolo["$kasztnev"]["basepenet"] = $penetinctomb["basepenet"];
	}
	if(!$mobtablaleker = mysqli_query($_SESSION["conn"], "SELECT * FROM mobs")) die("Mobok bekérése sikertelen");
	while($mobtomb = mysqli_fetch_assoc($mobtablaleker))
	{
		$mobnev = $mobtomb["mobname"];
		$tarolo["$mobnev"]["ertekpenet"] = $mobtomb["ertekpenet"];
		$tarolo["$mobnev"]["agressive"] = $mobtomb["agressive"];
		$tarolo["$mobnev"]["basicreward"] = $mobtomb["basicreward"];
		$tarolo["$mobnev"]["rewardinc"] = $mobtomb["rewardinc"];
	}

	//======NPCK LÉPÉSE=====
	attackset($tarolo);
	if(!$npcbekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM battle ORDER BY attack DESC")) die("attack értékek betöltése siekrtelen");
	while($npcid = mysqli_fetch_row($npcbekeres))
	{
		$id = $npcid[0];
		kor($id, $tarolo);
	}
	
	if(0) die();

	function kor($id, $tarolo)
	{
		
		$log = $_SESSION["log"];
		$valt = 0;
		$loves = 1;
		//========KARAKTER  ADATAINAK BETÖLTÉSE======
		$tabla1 = "npcs";
		if($id == $_SESSION["karakterazonosito"]) $tabla1 = "users";
		if(!$karakterlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM $tabla1 WHERE karakterazonosito='$id'")) die("Karakteradatok bekérése siekrtelen");
		foreach($karaktertomb = mysqli_fetch_assoc($karakterlekeres) as $name=>$ertek) $npc["$name"] = $ertek;
		$log[] = "$id karakteradatainak betöltése sikeres";
		if(!$battlelekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM battle WHERE karakterazonosito='$id'")) die("Battle adatok beolvasása sikertelen");
		foreach($battletomb = mysqli_fetch_assoc($battlelekeres) as $name=>$ertek) $npc["$name"] = $ertek;
		$log[] = "$id battle adatainak bekérése sikeres";
		
		if($npc["enemy"] == "mob")
		{
			$_SESSION["log"] = mobkor($tarolo, $npc, $log);
			return 1;
		}
		$npc["attacked"] = 0;
		//====DISABILITY=====
		if($npc["disability"])
		{
			$npc["activeability0"] = 0;
			$npc["activeability1"] = 0;
			$npc["hppotactive"] = 0;
			$npc["shieldpotactive"] = 0;
			$npc["manapotactive"] = 0;
			$npc["ishactive"] = 0;
			$npc["epactive"] = 0;
		}
		//=====PAJZSTÖLTŐDÉS=====
		if(!$npc["lastattack"] and $npc["actualshield"] != $npc["maxshield"])
		{
			$npc["actualshield"] = $npc["actualshield"]+$npc["maxshield"]*0.2;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
			$log[] = "$id pajzsa töltődött.";
		}
		if(!$npc["dmgreceived"] and $npc["lastattack"]) $npc["lastattack"] = $npc["lastattack"]-1;
		//=====KÖTSZERTÖLTŐDÉS=====
		if($npc["activefelszereles"] == "kotszer")
		{
			$npc["actualshield"] = $npc["actualshield"]+$npc["maxshield"]*0.05;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
		}
		//=====ISHAKTIV=====
		if($npc["ishactive"])
		{
			$npc["dmgreceived"] = 0;
			$npc["ishactive"] = 0;
			$log[] = "$id ISH-t használt";
		}
		//=====DROPTARGET=====
		if($npc["kaszt"] == "mage" and $npc["activeability1"])
		{
			$loves = 0;
			if(!$mageability1update = mysqli_query($_SESSION["conn"], "UPDATE battle SET target='0' WHERE target='$id'")) die("Droptarget frisítés sikertelen");
			$dropszam = mysqli_affected_rows($mageability1update);
			$log[] = "$id droptargete $dropszam kijelölést távolított el.";
			if($npc["cloaknum"])
			{
				$npc["cloaknum"] -= 1;
				$npc["cloaked"] = 1;
				$log[] = "$id álcázta magát.";
			}
		}
		//=====EMPRESET=====
		if($npc["empactive"]) $npc["empactive"] = 0;
		//=====DMGRECEIVED=====
		if($npc["dmgreceived"])
		{
			$npc["lastattack"] = 2;
			if($npc["kaszt"] == "paladin")
			{
				if($npc["szintpassziv"]) $npc["dmgreceived"] = $npc["dmgreceived"]*(1-($tarolo["paladinpassziv"]["ertek"]+$tarolo["paladinpassziv"]["ertekinc"]*$npc["szintpassziv"])/100);
				if($npc["activeability0"]) $npc["dmgreceived"] = $npc["dmgreceived"]*(1-($tarolo["paladinability0"]["ertek"]+$tarolo["paladinability0"]["ertekinc"]*$npc["szintability0"])/100);
			}
			settype($npc["dmgreceived"], "integer");
			$log[] = "$id " . $npc["dmgreceived"] . " sebzést szenvedett el.";
			if($npc["ertekpenet"] > 100) $npc["ertekpenet"] = 100;
			$npc["actualshield"] = $npc["actualshield"]-($npc["dmgreceived"]*$npc["ertekpenet"]/100);
			settype($npc["actualshield"], "integer");
			$npc["actualhp"] = $npc["actualhp"]-($npc["dmgreceived"]*(1-$npc["ertekpenet"]/100));
			settype($npc["actualhp"], "integer");
			if($npc["actualshield"] < 0)
			{
				$npc["actualhp"] = $npc["actualhp"]+$npc["actualshield"];
				$npc["actualshield"] = 0;
			}
			$npc["dmgreceived"] = 0;
			
			//=====FIZETÉS, KARAKTERTÖRLÉS=====
			if($npc["actualhp"] <= 0)
			{
				$log[] = "$id meghalt.";
				switch($npc["kaszt"])
				{
					case "warrior":
						$kasztbonusz = 1;
					break;
					case "mage":
						$kasztbonusz = 0.8;
					break;
					case "paladin":
						$kasztbonusz = 1.5;
					break;
					case "healer":
						$kasztbonusz = 1;
					break;
				}
				if(!$lovoklekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE target='$id' and attacked='1'")) die("Lövők számának lekérése siekrtelen");
				$lovok = mysqli_num_rows($lovoklekeres);
				if(!$lovok) $lovok = 1;
				$log[] = "$lovok lőtte $id-t";
				$reward = (5000+$npc["karakterszint"]*500)*$kasztbonusz/$lovok;
				settype($reward, "integer");
				if(!$payforlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito, attacked FROM battle WHERE target='$id'")) die("Gyilkosok lekérése sikertelen");
				while($payfortomb = mysqli_fetch_assoc($payforlekeres))
				{
					$payforid = $payfortomb["karakterazonosito"];
					{
						if($payforid == $_SESSION["karakterazonosito"] and $payfortomb["attacked"])
						{
							if(!$payforactualmoneylekeres = mysqli_query($_SESSION["conn"], "SELECT actualmoney FROM users WHERE karakterazonosito='$payforid'")) die("Játékos pénzének lekérése sikertelen");
							foreach($moneytomb = mysqli_fetch_row($payforactualmoneylekeres) as $payforactualmoney);
							$payfornewmoney = $payforactualmoney+$reward;
							if(!$payformoneyupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET actualmoney='$payfornewmoney' WHERE karakterazonosito='$payforid'")) die("Jutalom kifizetése siekrtelen");
							$log[] = "$payforid részére $reward kifizetve.";
						}
					}
				}
				if($id == $_SESSION["karakterazonosito"])
				{
					$_SESSION["log"] = $log;
					header("location:meghaltal.php");
					die();
				}
				else
				{
					if(!$battlefieldtorles = mysqli_query($_SESSION["conn"], "DELETE FROM battle WHERE karakterazonosito='$id'")) die("Battle tábla törlése sikertelen");
					if(!$npctorles = mysqli_query($_SESSION["conn"], "DELETE FROM npcs WHERE karakterazonosito='$id'")) die("Npc tábla törlése sikertelen");
					if(!$targettorles = mysqli_query($_SESSION["conn"], "UPDATE battle SET target='0' WHERE target='$id'")) die("Célpontok törlése sikertelen");
					$_POST["target"] = 0;
					$_SESSION["gamesession"] += 1;
				}
				$log[] = "$id törlése sikeres";
				$log[] = "";
				$_SESSION["log"] = $log;
				return 1;
			}
		}
		if(!$kap = rand(0, 20))
		{
			$max = rand(1, 3);
			for($szam = 0; $szam < $max; $szam++)
			{
				$item = rand(1, 13);
				switch($item)
				{
					case 1:
						$npc["x2num"] += $amount = rand(1, 50);
						$log[] = "$id $amount x2-t kapott.";
					break;
					case 2:
						$npc["x3num"] += $amount = rand(1, 25);
						$log[] = "$id $amount x3-t kapott.";
					break;
					case 3:
						$npc["x4num"] += $amount = rand(1, 10);
						$log[] = "$id $amount x4-t kapott.";
					break;
					case 4:
						$npc["ishnum"] += $amount = rand(1, 2);
						$log[] = "$id $amount ish-t kapott.";
					break;
					case 5:
						$npc["empnum"] += $amount = rand(1, 2);
						$log[] = "$id $amount emp-t kapott.";
					break;
					case 6:
						$npc["pldnum"] += $amount = rand(1, 5);
						$log[] = "$id $amount pld-t kapott.";
					break;
					case 7:
						$npc["hppotnum"] += $amount = rand(1, 2);
						$log[] = "$id $amount hppotitt kapott.";
					break;
					case 8:
						$npc["shieldpotnum"] += $amount = rand(1, 2);
						$log[] = "$id $amount shieldpotit kapott.";
					break;
					case 9:
						$npc["manapotnum"] += $amount = rand(1, 2);
						$log[] = "$id $amount manapotit kapott.";
					break;
					case 10:
						$npc["actualhp"] += $npc["maxhp"]*0.2;
						if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
						$log[] = "$id Életerő platformot kapott";
					break;
					case 11:
						$npc["actualshield"] += $npc["maxshield"]*0.2;
						if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
						$log[] = "$id Pajzs platformot kapott";
					break;
					case 12:
						$npc["actualmana"] += $npc["maxmana"]*0.2;
						if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
						$log[] = "$id Mana platformot kapott";
					break;
					case 13:
						$npc["cloaknum"] += $amount = rand(1, 5);
						$log[] = "$id $amount álcát kapott.";
					break;
				}
			}
		}
		//=====HEALER REGEN=====
		if($npc["kaszt"] == "healer" and $npc["szintpassziv"])
		{
			$manaregen = $tarolo["healerpassziv"]["ertek"]+($npc["szintpassziv"]-1)*$tarolo["healerpassziv"]["ertekinc"];
			$npc["actualmana"] = $npc["actualmana"]+$manaregen;
			if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
		}
		//=====ÚJRATÖLTŐDÉS/ELHASZNÁLÓDÁS=====
		//=====BOOSTEREK=====
		if($npc["hpboosterrounds"])
		{
			$npc["hpboosterrounds"] -= 1;
			if(!$npc["hpboosterrounds"])
				{
					$npc["maxhp"] = $npc["basichp"]*$npc["hpfelszbonus"]/100;
					if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
				}
		}
		if($npc["shieldboosterrounds"])
		{
			$npc["shieldboosterrounds"] -= 1;
			if(!$npc["shieldboosterrounds"])
				{
					$npc["maxshield"] = $npc["basicshield"]*$npc["shieldfelszbonus"]/100;
					if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
				}
		}
		if($npc["manaboosterrounds"])
		{
			$npc["manaboosterrounds"] -= 1;
			if(!$npc["manaboosterrounds"])
				{
					$npc["maxmana"] = $npc["basicmana"]*$npc["manafelszbonus"]/100;
					if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
				}
		}
		if($npc["dmgboosterrounds"])
		{
			$npc["dmgboosterrounds"] -= 1;
			if(!$npc["dmgboosterrounds"]) $npc["actualdmg"] = $npc["basicdmg"]*$npc["dmgfelszbonus"]/100;
		}
		if($npc["attackboosterrounds"]) $npc["attackboosterrounds"] -= 1;
		if($npc["accurboosterrounds"]) $npc["accurboosterrounds"] -= 1;
		//=====POTIK=====
		if($npc["hppotactive"])
		{
			$npc["actualhp"] = $npc["actualhp"]+$npc["maxhp"]*$tarolo["hppot"]["specialertek"]/100;
			if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
			$npc["hppotactive"] -= 1;
		}
		if($npc["hppotreload"]) $npc["hppotreload"] -= 1;
		
		if($npc["shieldpotactive"])
		{
			$npc["actualshield"] = $npc["actualshield"]+$npc["maxshield"]*$tarolo["shieldpot"]["specialertek"]/100;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
			$npc["shieldpotactive"] -= 1;
		}
		if($npc["shieldpotreload"]) $npc["shieldpotreload"] -= 1;
		
		if($npc["manapotactive"])
		{
			$npc["actualmana"] = $npc["actualmana"]+$npc["maxmana"]*$tarolo["manapot"]["specialertek"]/100;
			if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
			$npc["manapotactive"] -= 1;
		}
		if($npc["manapotreload"]) $npc["manapotreload"] -= 1;
		//=====EXTRÁK=====
		if($npc["empreload"]) $npc["empreload"] -= 1;
		if($npc["empactive"]) $npc["empactive"] -= 1;
		if($npc["ishreload"]) $npc["ishreload"] -= 1;
		if($npc["ishactive"]) $npc["ishactive"] -= 1;
		if($npc["pldreload"]) $npc["pldreload"] -= 1;
		if($npc["pldactive"]) $npc["pldactive"] -= 1;
		//=====KÉPESSÉGEK=====
		if($npc["activeability0"]) $npc["activeability0"] -= 1;
		if($npc["reloadability0"]) $npc["reloadability0"] -= 1;
		if($npc["activeability1"]) $npc["activeability1"] -= 1;
		if($npc["reloadability1"]) $npc["reloadability1"] -= 1;
			
		//=====AKTIVÁLÁS=====
		if($id == $_SESSION["karakterazonosito"])
		{
			//=====LŐSZERVÁLASZTÁS=====
			switch($_POST["loszer"])
			{
				case "x1": $npc["ammo"] = "x1"; break;
				case "x2":
					if($npc["x2num"] >= $npc["dmgmultiplierlevel"] and $tarolo["x2"]["manausage"] <= $npc["actualmana"])
					{
						$npc["ammo"] = "x2";
						$ammosetted = 1;
					}
				break;
				case "x3":
					if($npc["x3num"] >= $npc["dmgmultiplierlevel"] and $tarolo["x3"]["manausage"] <= $npc["actualmana"])
					{
						$npc["ammo"] = "x3";
						$ammosetted = 1;
					}
				break;
				case "x4":
					if($npc["x4num"] >= $npc["dmgmultiplierlevel"] and $tarolo["x4"]["manausage"] <= $npc["actualmana"])
					{
						$npc["ammo"] = "x4";
						$ammosetted = 1;
					}
			}
			if(!isset($ammosetted)) $npc["ammo"] = "x1";
			$log[] = "$id választott lőszere: " . $npc["ammo"];
			//=====POTIONHASZNÁLAT=====
			if(isset($_POST["hppotactive"]) and $tarolo["hppot"]["manausage"] <= $npc["actualmana"]  and !$npc["disability"])
			{
				$npc["hppotactive"] = $tarolo["hppot"]["aktivkor"];
				$npc["hppotreload"] = $tarolo["hppot"]["reload"];
				$npc["hppotnum"] -= 1;
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["hppot"]["manausage"];
			}
			if(isset($_POST["shieldpotactive"]) and $tarolo["shieldpot"]["manausage"] <= $npc["actualmana"] and !$npc["disability"])
			{
				$npc["shieldpotactive"] = $tarolo["shieldpot"]["aktivkor"];
				$npc["shieldpotreload"] = $tarolo["shieldpot"]["reload"];
				$npc["shieldpotnum"] -= 1;
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["shieldpot"]["manausage"];
			}
			if(isset($_POST["manapotactive"]) and !$npc["disability"])
			{
				$npc["manapotactive"] = $tarolo["manapot"]["aktivkor"];
				$npc["manapotreload"] = $tarolo["manapot"]["reload"];
				$npc["manapotnum"] -= 1;
			}
			//=====EXTRAHASZNÁLAT=====
			if(isset($_POST["ishreload"]) and $tarolo["ish"]["manausage"] <= $npc["actualmana"] and !$npc["disability"])
			{
				$npc["ishactive"] = 1;
				$npc["ishnum"] -= 1;
				$npc["ishreload"] = $tarolo["ish"]["reload"];
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["ish"]["manausage"];
			}
			if(isset($_POST["empreload"]) and $tarolo["emp"]["manausage"] <= $npc["actualmana"] and !$npc["disability"])
			{
				$npc["empactive"] = 1;
				$npc["empnum"] -= 1;
				$npc["empreload"] = $tarolo["emp"]["reload"];
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["emp"]["manausage"];
			}
			if(isset($_POST["cloaked"]) and $npc["cloaknum"])
			{
				$npc["cloaked"] = 1;
				$npc["cloaknum"] -= 1;
				$log[] = "$id álcázta magát.";
			}
			if(isset($_POST["pldactive"]) and $tarolo["pld"]["manausage"] <= $npc["actualmana"] and !$npc["disability"])
			{
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["pld"]["manausage"];
				$npc["pldreload"] = $tarolo["pld"]["reload"];
				$npc["pldnum"] -= 1;
				$tg = $npc["target"];
				$pldact = $tarolo["pld"]["aktivkor"];
				if(!$pldupdate = mysqli_query($_SESSION["conn"], "UPDATE npcs SET pldactive='$pldact' WHERE karakterazonosito='$tg'")) die("PLD használata sikertelen");
				$log[] = "$id PLD-t használt $tg-n";
			}
			//=====FELSZERELÉSVÁLASZTÁS=====
			$valt = 0;
			if($_POST["activefelszereles"] != $npc["activefelszereles"])
			{
				$npc["activefelszereles"] = $_POST["activefelszereles"];
				$valt = $npc["activefelszereles"];
			}
			//=====KÉPESSÉGAKTIVÁLÁS=====
			if(isset($_POST["ability0"])) $aktivateability0 = 1;
			if(isset($_POST["ability1"])) $aktivateability1 = 1;
		}
		
		//=====NPC MI=====
		if($id != $_SESSION["karakterazonosito"])
		{
			//=====FELSZERELÉSVÁLASZTÁS=====
			if($npc["activefelszereles"] == "alap")
			{
				if(!$npc["lastattack"])
				{
					do
					{
						if($npc["pajzs"])
						{
							$npc["activefelszereles"] = "pajzs";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["manakristaly"])
						{
							$npc["activefelszereles"] = "manakristaly";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["pancel"])
						{
							$npc["activefelszereles"] = "pancel";
							$valt = $npc["activefelszereles"];
							break;
						}
					}
					while(0);
				}
				elseif($npc["kard"] and $npc["actualshield"] < $npc["maxshield"]*0.8)
				{
					$npc["activefelszereles"] = "kard";
					$valt = $npc["activefelszereles"];
				}
			}
			if($npc["activefelszereles"] == "kotszer")
			{
				if($npc["kaszt"] == "mage" and $npc["actualshield"] < $npc["maxshield"]*0.8 and $npc["kard"])
				{
					$npc["activefelszereles"] = "kard";
					$valt = $npc["activefelszereles"];
				}
				if(!$npc["lastattack"])
				{
					do
					{
						if($npc["pajzs"])
						{
							$npc["activefelszereles"] = "pajzs";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["manakristaly"])
						{
							$npc["activefelszereles"] = "manakristaly";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["pancel"])
						{
							$npc["activefelszereles"] = "pancel";
							$valt = $npc["activefelszereles"];
							break;
						}
					}
					while(0);
				}
				if(!$npc["lastattack"])
				{
					do
					{
						if($npc["pajzs"])
						{
							$npc["activefelszereles"] = "pajzs";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["manakristaly"] and $npc["actualmana"] != $npc["maxmana"])
						{
							$npc["activefelszereles"] = "manakristaly";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["pancel"])
						{
							$npc["activefelszereles"] = "pancel";
							$valt = $npc["activefelszereles"];
							break;
						}
					}
					while(0);
				}
			}
			if($npc["activefelszereles"] == "phalanx")
			{
				if($npc["actualshield"] == 0 or $npc["actualhp"] > $npc["maxhp"]*0.5)
				{
					do
					{
						if($npc["kotszer"] and $npc["actualhp"] < $npc["maxhp"]*0.7)
						{
							$npc["activefelszereles"] = "kotszer";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["kard"])
						{
							$npc["activefelszereles"] = "kard";
							$valt = $npc["activefelszereles"];
							break;
						}
						else
						{
							$npc["activefelszereles"] = "alap";
							$valt = $npc["activefelszereles"];
						}
					}
					while(0);
				}
				if(!$npc["lastattack"])
				{
					do
					{
						if($npc["pajzs"])
						{
							$npc["activefelszereles"] = "pajzs";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["manakristaly"] and $npc["actualmana"] != $npc["maxmana"])
						{
							$npc["activefelszereles"] = "manakristaly";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["pancel"])
						{
							$npc["activefelszereles"] = "pancel";
							$valt = $npc["activefelszereles"];
							break;
						}
					}
					while(0);
				}
			}
			if($npc["activefelszereles"] == "kard" and !$npc["lastattack"])
			{
				do
				{
					if($npc["pajzs"])
					{
						$npc["activefelszereles"] = "pajzs";
						$valt = $npc["activefelszereles"];
						break;
					}
					if($npc["manakristaly"] and $npc["actualmana"] != $npc["maxmana"])
					{
						$npc["activefelszereles"] = "manakristaly";
						$valt = $npc["activefelszereles"];
						break;
					}
					if($npc["pancel"])
					{
						$npc["activefelszereles"] = "pancel";
						$valt = $npc["activefelszereles"];
						break;
					}
				}
				while(0);
			}
			if($npc["activefelszereles"] == "manakristaly" and $npc["lastattack"])
			{
				if($npc["actualmana"] < $npc["maxmana"]/2 or $npc["actualhp"] < $npc["maxhp"]*0.7)
				{
					do
					{
						if($npc["kotszer"] and $npc["actualhp"] < $npc["maxhp"]*0.7)
						{
							$npc["activefelszereles"] = "kotszer";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["kard"] and $npc["actualshield"] < $npc["maxshield"]*0.8)
						{
							$npc["activefelszereles"] = "kard";
							$valt = $npc["activefelszereles"];
							break;
						}
						$npc["activefelszereles"] = "alap";
						$valt = $npc["activefelszereles"];
						break;
					}
					while(0);
				}
			}
			if($npc["activefelszereles"] == "pajzs" and $npc["lastattack"])
			{
				if($npc["actualshield"] < $npc["maxshield"]/2 or $npc["actualhp"] < $npc["maxhp"]/2)
				{
					do
					{
						if($npc["pancel"])
						{
							$npc["activefelszereles"] = "pancel";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["kard"])
						{
							$npc["activefelszereles"] = "kard";
							$valt = $npc["activefelszereles"];
							break;
						}
						if($npc["kotszer"])
						{
							$npc["activefelszereles"] = "kotszer";
							$valt = $npc["activefelszereles"];
							break;
						}
						$npc["activefelszereles"] = "alap";
						$valt = $npc["activefelszereles"];
					}
					while(0);
				}
			}
			if($npc["actualhp"] < $npc["maxhp"]*0.3 and $npc["actualshield"] > $npc["maxshield"]*0.3 and $npc["lastattack"])
			{
				$npc["activefelszereles"] = "phalanx";
				$valt = $npc["activefelszereles"];
			}
			//=====EXTRÁK=====
			if($npc["ishnum"] and $npc["actualmana"] >= $tarolo["ish"]["manausage"] and !$npc["ishreload"] and $npc["lastattack"] and !$npc["disability"])
			{
				if($npc["actualhp"] < $npc["maxhp"]*0.4 or !$npc["actualshield"])
				{
					$npc["ishactive"] = 1;
					$npc["ishnum"] -= 1;
					$npc["ishreload"] = $tarolo["ish"]["reload"];
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["ish"]["manausage"];
				}
			}
			if($npc["empnum"] and !$npc["ishactive"] and $npc["actualmana"] >= $tarolo["emp"]["manausage"] and !$npc["empreload"] and $npc["lastattack"] and !$npc["disability"])
			{
				if($npc["actualhp"] < $npc["maxhp"]*0.4 or !$npc["actualshield"])
				{
					$npc["empactive"] = 1;
					$npc["empnum"] -= 1;
					$npc["empreload"] = $tarolo["emp"]["reload"];
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["emp"]["manausage"];
					if($npc["cloaknum"])
					{
						$npc["cloaknum"] -= 1;
						$npc["cloaked"] = 1;
						$log[] = "$id álcázta magát.";
						$loves = 0;
					}
				}
			}
			//=====POTIAKTIVÁLÁS=====
			if($npc["hppotnum"] and $npc["actualmana"] >= $tarolo["hppot"]["manausage"] and !$npc["hppotreload"] and !$npc["hppotactive"] and !$npc["disability"])
			{
				$hpszazalek = $npc["actualhp"]/$npc["maxhp"];
				if($hpszazalek < 0.6)
				{
					$npc["hppotactive"] = $tarolo["hppot"]["aktivkor"];
					$npc["hppotreload"] = $tarolo["hppot"]["reload"];
					$npc["hppotnum"] -= 1;
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["hppot"]["manausage"];
				}
			}
			if($npc["shieldpotnum"] and $npc["actualmana"] >= $tarolo["shieldpot"]["manausage"] and !$npc["shieldpotreload"] and !$npc["shieldpotactive"] and !$npc["disability"])
			{
				$shieldszazalek = $npc["actualshield"]/$npc["maxshield"];
				if($shieldszazalek < 0.6)
				{
					$npc["shieldpotactive"] = $tarolo["shieldpot"]["aktivkor"];
					$npc["shieldpotreload"] = $tarolo["shieldpot"]["reload"];
					$npc["shieldpotnum"] -= 1;
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["shieldpot"]["manausage"];
				}
			}
			if($npc["manapotnum"] and !$npc["manapotreload"] and !$npc["manapotactive"] and !$npc["disability"])
			{
				$manaszazalek = $npc["actualmana"]/$npc["maxmana"];
				if($manaszazalek < 0.6)
				{
					$npc["manapotactive"] = $tarolo["manapot"]["aktivkor"];
					$npc["manapotreload"] = $tarolo["manapot"]["reload"];
					$npc["manapotnum"] -= 1;
				}
			}
			if($npc["pldnum"] and $tarolo["pld"]["manausage"] <= $npc["actualmana"] and $npc["target"] and !$npc["pldreload"] and !$npc["disability"])
			{
				$npc["actualmana"] = $npc["actualmana"]-$tarolo["pld"]["manausage"];
				$npc["pldreload"] = $tarolo["pld"]["reload"];
				$npc["pldnum"] -= 1;
				$tg = $npc["target"];
				if($tg == $_SESSION["karakterazonosito"]) $tabla3 = "users";
				else $tabla3 = "npcs";
				$pldact = $tarolo["pld"]["aktivkor"];
				if(!$pldupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla3 SET pldactive='$pldact' WHERE karakterazonosito='$tg'")) die("PLD használata sikertelen");
				$log[] = "$id PLD-t használt $tg-n";
			}
			//=====KÉPESSÉGAKTIVÁLÁS=====
			if($npc["kaszt"] == "warrior")
			{
				if($npc["szintability0"] and !$npc["reloadability0"] and $tarolo["warriorability0"]["manausage"] <= $npc["actualmana"] and $npc["target"])
				{
					$celp = $npc["target"];
					if($celp == $_SESSION["karakterazonosito"]) $aktivateability0 = 1;
					else
					{
						if(!$celponttipuslekeres = mysqli_query($_SESSION["conn"], "SELECT enemy FROM npcs WHERE karakterazonosito='$celp'")) die("Célpont típusának lekérése sikertelen");
						foreach($celponttipustomb = mysqli_fetch_row($celponttipuslekeres) as $celponttipus);
						if($celponttipus != "mob") $aktivateability0 = 1;
					}
				}
				if($npc["szintability1"] and !$npc["reloadability1"] and $tarolo["warriorability1"]["manausage"] <= $npc["actualmana"] and $npc["actualshield"] < $npc["maxshield"]*0.8 and $npc["lastattack"]) $aktivateability1 = 1;
			}
			if($npc["kaszt"] == "mage")
			{
				if($npc["szintability0"] and !$npc["reloadability0"] and $tarolo["mageability0"]["manausage"] <= $npc["actualmana"] and $npc["lastattack"] == 2) $aktivateability0 = 1;
				if($npc["szintability1"] and !$npc["reloadability1"] and $tarolo["mageability1"]["manausage"] <= $npc["actualmana"] and $npc["actualshield"] < $npc["maxshield"]*0.5 and !$npc["empactive"]) $aktivateability1 = 1;
			}
			if($npc["kaszt"] == "paladin")
			{
				if($npc["szintability0"] and !$npc["reloadability0"] and $tarolo["paladinability0"]["manausage"] < $npc["actualmana"] and $npc["lastattack"] == 2) $aktivateability0 = 1;
				if($npc["szintability1"] and !$npc["reloadability1"] and $npc["actualhp"]/$npc["maxhp"] > 0.5 and $npc["actualshield"]/$npc["maxshield"] > 0.5) $aktivateability1 = 1;
			}
			if($npc["kaszt"] == "healer")
			{
				if($npc["szintability0"] and !$npc["reloadability0"] and $tarolo["healerability0"]["manausage"] <= $npc["actualmana"]) $aktivateability0 = 1;
				if($npc["szintability1"] and !$npc["reloadability1"] and $tarolo["healerability1"]["manausage"] <= $npc["actualmana"]) $aktivateability1 = 1;
			}
			//=====LŐSZERVÁLASZTÁS=====
			if($npc["x4num"] >= $npc["dmgmultiplierlevel"] and $npc["actualmana"] >= $tarolo["x4"]["manausage"]) $npc["ammo"] = "x4";
			elseif($npc["x3num"] >= $npc["dmgmultiplierlevel"] and $npc["actualmana"] >= $tarolo["x3"]["manausage"]) $npc["ammo"] = "x3";
			elseif($npc["x2num"] >= $npc["dmgmultiplierlevel"] and $npc["actualmana"] >= $tarolo["x2"]["manausage"]) $npc["ammo"] = "x2";
			else $npc["ammo"] = "x1";
			$log[] = "$id választott lőszere: " . $npc["ammo"];
		}
		
		if($npc["empactive"])
		{
			if(!$empupdate = mysqli_query($_SESSION["conn"], "UPDATE battle SET target='0' WHERE target='$id'")) die("EMP használata sikertelen");
			$log[] = "$id EMP-et használt";
		}
		//=====FELSZERELÉSVÁLTÁS=====
		
		if($valt)
		{
			$felsz = $npc["activefelszereles"];
			$log[] = "$id váltott $felsz felszerelésre.";
			$npc["hpfelszbonus"] = $tarolo["$felsz"]["hpbonus"];
			$hpboosterbonus = 1;
			if($npc["hpboosterrounds"]) $hpboosterbonus = 1.25;
			$npc["maxhp"] = $npc["basichp"]*$npc["hpfelszbonus"]*$hpboosterbonus/100;
			if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
			
			$npc["shieldfelszbonus"] = $tarolo["$felsz"]["shieldbonus"];
			$shieldboosterbonus = 1;
			if($npc["shieldboosterrounds"]) $shieldboosterbonus = 1.25;
			$npc["maxshield"] = $npc["basicshield"]*$npc["shieldfelszbonus"]*$shieldboosterbonus/100;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
			
			$npc["manafelszbonus"] = $tarolo["$felsz"]["manabonus"];
			$manaboosterbonus = 1;
			if($npc["manaboosterrounds"]) $manaboosterbonus = 1.25;
			$npc["maxmana"] = $npc["basicmana"]*$npc["manafelszbonus"]*$manaboosterbonus/100;
			if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
			
			$npc["penetfelszbonus"] = $tarolo["$felsz"]["penetbonus"];
			$kaszt = $npc["kaszt"];
			$npc["ertekpenet"] = $tarolo["$kaszt"]["basepenet"]+$npc["szintpenet"]*$tarolo["$kaszt"]["penetinc"]+$npc["penetfelszbonus"];
			if($npc["ertekpenet"] > 100) $npc["ertekpenet"] = 100;
			
			$npc["dmgfelszbonus"] = $tarolo["$felsz"]["dmgbonus"];
			$dmgboosterbonus = 1;
			if($npc["dmgboosterrounds"]) $dmgboosterbonus = 1.25;
			$npc["actualdmg"] = $npc["basicdmg"]*$dmgboosterbonus*$npc["dmgfelszbonus"]/100;
		}
		//======CÉLPONT KIVÁLASZTÁSA=====
		if($id == $_SESSION["karakterazonosito"])
		{
			if(!isset($_POST["target"]))
			{
				$_POST["target"] = 0;
				$npc["target"] = $_POST["target"];
			}
			if($_POST["target"] != $npc["target"])
			{
				$targetid = $_POST["target"];
				$log[] = "$id jelöli: $targetid";
			}
		}
		if(!$npc["target"] and $id != $_SESSION["karakterazonosito"])
		{
			do
			{
				if($npc["enemy"]) $enem = 0;
				if(!$npc["enemy"]) $enem = 1;
				
				if(!$celpontlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='$enem'")) die("Lehetséges célpontok lekérése sikertelen");
				while($celponttomb = mysqli_fetch_row($celpontlekeres))
				{
					foreach($celponttomb as $cp)
					{
						$cptomb[] = $cp;
					}
				}
				$celpontszam = mysqli_num_rows($celpontlekeres);
				if(!$npc["enemy"]) $celpont = rand(1, $celpontszam);
				if($npc["enemy"]) $celpont = rand(0, $celpontszam);
				if(!$celpont)
				{
					$targetid = $_SESSION["karakterazonosito"];
					break;
				}
				if(isset($cptomb))
				{
					$szam = $celpont-1;
					if($celpont) $targetid = $cptomb[$szam];
				
					$log[] = "$id jelöli: $targetid (enemy)";
					break;
				}
				
				if(!$agressivelekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='mob' AND activefelszereles='1'")) die("Agresszív mobok lekérése siekrtelen");
				while($celpontagtomb = mysqli_fetch_row($agressivelekeres))
				{
					foreach($celpontagtomb as $ertek)
					{
						$cpagtomb[] = $ertek;
					}
				}
				if(isset($cpagtomb))
				{
					$celpontagszam = mysqli_num_rows($agressivelekeres);
					$celpontag = rand(1, $celpontagszam)-1;
					$targetid = $cpagtomb[$celpontag];
					$log[] = "$id jelöli: $targetid (agressive)";
					break;
				}
				if(!$bekeslekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='mob' AND activefelszereles='0'")) die("Békés mobok lekérése siekrtelen");
				while($celpontbekestomb = mysqli_fetch_row($bekeslekeres))
				{
					foreach($celpontbekestomb as $ertek)
					{
						$cpbektomb[] = $ertek;
					}
				}
				if(isset($cpbektomb))
				{
					$celpontbekszam = mysqli_num_rows($bekeslekeres);
					$celpontbek = rand(1, $celpontbekszam)-1;
					$targetid = $cpbektomb[$celpontbek];
					$log[] = "$id jelöli: $targetid (bekes)";
					break;
				}
			}
			while(0);
		}
		if(isset($targetid))
		{
			//=====CÉLPONT BEMÉRÉSE=====
			if($targetid == $_SESSION["karakterazonosito"]) $tabla2 = "users";
			else $tabla2 = "npcs";
			
			if(!$celpontcloaklekerdezes = mysqli_query($_SESSION["conn"], "SELECT cloaked FROM $tabla2 WHERE karakterazonosito='$targetid'")) die("célpont álcázottságának lekérése siekrtelen");
			foreach($celpontcloaktomb = mysqli_fetch_row($celpontcloaklekerdezes) as $celpontcloaked);
			$cloakedbonus = 0;
			if($celpontcloaked) $cloakedbonus = 1200;
			
			$boosterbonus = 1;
			if($npc["accurboosterrounds"]) $boosterbonus = 1.25;
			
			$accurbonus = 0;
			if($npc["kaszt"] == "warrior") $accurbonus = $tarolo["warriorpassziv"]["ertek"]+($npc["szintpassziv"]-1)*$tarolo["warriorpassziv"]["ertekinc"];

			$accuracy = rand(0, 2000)*$boosterbonus+$accurbonus;
			$missclick = 500+$cloakedbonus;
			
			if($accuracy >= $missclick)
			{
				$npc["target"] = $targetid;
				$log[] = "$id sikeresen bemérte $targetid célpontot";
			}
			if($accuracy < $missclick)
			{
				$npc["target"] = 0;
				$log[] = "$id sikertelenül jelölte $targetid-t";
			}
		}
		if($id != $_SESSION["karakterazonosito"])
		{
			if(!$npc["lastattack"])
			{
				if($npc["actualhp"] < $npc["maxhp"]*0.8 or $npc["actualmana"] < $npc["maxmana"]*0.7)
				{
					$loves = 0;
					if($npc["cloaknum"] and !$npc["cloaked"])
					{
						$npc["cloaked"] = 1;
						$npc["cloaknum"] -= 1;
						$log[] = "$id álcázta magát.";
					}
				}
			}
		}
		if($id == $_SESSION["karakterazonosito"])
		{
			if(!$_POST["tamadas"]) $loves = 0;
		}
		if($npc["target"] and $loves)
		{
			//=====SEBZÉS=====
			$tg = $npc["target"];
			$log[] = "$id támadja: $tg";
			if(!$sebzesleker = mysqli_query($_SESSION["conn"], "SELECT dmgreceived FROM battle WHERE karakterazonosito='$tg'")) die("Sebzés lelérése sikertelen");
			foreach($sebzestomb = mysqli_fetch_row($sebzesleker) as $okozottsebzes);
			
			switch($npc["ammo"])
			{
				case "x1":	$ammobonus = 1; break;
				case "x2":
					$ammobonus = 2; 
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["x2"]["manausage"];
					$npc["x2num"] = $npc["x2num"]-$npc["dmgmultiplierlevel"];
				break;
				case "x3":
					$ammobonus = 3;
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["x3"]["manausage"];
					$npc["x3num"] = $npc["x3num"]-$npc["dmgmultiplierlevel"];
				break;
				case "x4":
					$ammobonus = 4;
					$npc["actualmana"] = $npc["actualmana"]-$tarolo["x4"]["manausage"];
					$npc["x4num"] = $npc["x4num"]-$npc["dmgmultiplierlevel"];
				break;
				default: $ammobonus = 1; break;
			}
			$dodgebonus = 0;
			if($npc["target"] == $_SESSION["karakterazonosito"]) $tabla5 = "users";
			else $tabla5 = "npcs";
			$targetdodge = $npc["target"];
			if(!$dodgelekeres = mysqli_query($_SESSION["conn"], "SELECT kaszt, szintability0, activeability0 FROM $tabla5 WHERE karakterazonosito='$targetdodge'")) die("Célpont dodge lekérése sikertelen");
			$dodgetomb = mysqli_fetch_assoc($dodgelekeres);
			if($dodgetomb["kaszt"] == "mage" and $dodgetomb["activeability0"])
			{
				$dodgebonus = $tarolo["mageability0"]["ertek"]+($dodgetomb["szintability0"]-1)*$tarolo["mageability0"]["ertekinc"];
			}
			$shieldleechalap = 0;
			for($sz = 0; $sz < $npc["dmgmultiplierlevel"]; $sz++)
			{
				$pldbonus = 0;
				if($npc["pldactive"]) $pldbonus = $tarolo["pld"]["pldbonus"];
				$kiteres = 1200+$pldbonus+$dodgebonus;
				$talalat = rand(1000, 2000);
				if($talalat > $kiteres)
				{
					$sebzesa = $npc["actualdmg"]*$ammobonus*rand(70, 120)/100;
					settype($sebzesa, "integer");
					$okozottsebzes = $okozottsebzes+$sebzesa;
					$log[] = "$id $sebzesa sebzést okozott.";
					if($npc["kaszt"] == "warrior")
					{
						$shieldleechalap += $sebzesa;
					}
				}
				if($talalat <= $kiteres) $log[] = "$id mellé lőtt.";
			}
			$npc["attacked"] = 1;
			$npc["cloaked"] = 0;
			if(!$sebzesfrissit = mysqli_query($_SESSION["conn"], "UPDATE battle SET dmgreceived='$okozottsebzes' WHERE karakterazonosito='$tg'")) die("Sebzés frissítése sikertelen");
		}
		if(!$npc["attacked"] and !$npc["lastattack"])
		{
			$npc["actualmana"] = $npc["actualmana"]+$npc["maxmana"]*0.2;
			if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
			$npc["actualhp"] = $npc["actualhp"]+$npc["maxhp"]*0.2;
			if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
			$log[] = "$id javult.";
		}
		//=====KÉPESSÉGEK BEKAPCSOLÁSA=====
		if(isset($aktivateability0) and !$npc["disability"])
		{
			switch($npc["kaszt"])
			{
				case "warrior":
					if($tarolo["warriorability0"]["manausage"] <= $npc["actualmana"])
					{
						$npc["actualmana"] -= $tarolo["warriorability0"]["manausage"];
						$npc["reloadability0"] = $tarolo["warriorability0"]["reload"]-($npc["szintability0"]-1)*$tarolo["warriorability0"]["reloadinc"];
						$disabilityaktivkor = $tarolo["warriorability0"]["aktivkor"]+($npc["szintability0"]-1)*$tarolo["warriorability0"]["aktivkorinc"];
						$target = $npc["target"];
						if(!$disabilityupdate = mysqli_query($_SESSION["conn"], "UPDATE battle SET disability='$disabilityaktivkor' WHERE karakterazonosito='$target'")) die("Disability feltöltés sikertelen");
						$log[] = "$id Disability képességet aktivált $target-n";
					}
				break;
				case "mage":
					if($tarolo["mageability0"]["manausage"] <= $npc["actualmana"])
					{
						$npc["actualmana"] -= $tarolo["mageability0"]["manausage"];
						$npc["activeability0"] = ($npc["szintability0"]-1)*$tarolo["mageability0"]["aktivkorinc"]+$tarolo["mageability0"]["aktivkor"];
						$npc["reloadability0"] = $tarolo["mageability0"]["reload"]-($npc["szintability0"]-1)*$tarolo["mageability0"]["reloadinc"];
						$log[] = "$id aktiválta Dodge képességét";
					}
				break;
				case "paladin":
					if($tarolo["paladinability0"]["manausage"] <= $npc["actualmana"] and !$npc["reloadability0"])
					{
						$npc["actualmana"] -= $tarolo["paladinability0"]["manausage"];
						$npc["activeability0"] = ($npc["szintability0"]-1)*$tarolo["paladinability0"]["aktivkorinc"]+$tarolo["paladinability0"]["aktivkor"];
						$npc["reloadability0"] = $tarolo["paladinability0"]["reload"]-($npc["szintability0"]*$tarolo["paladinability0"]["reloadinc"]);
						$log[] = "$id aktiválta Invulnerable képességét";
					}
				break;
				case "healer":
					if($tarolo["healerability0"]["manausage"] <= $npc["actualmana"])
					{
						$enemy = $npc["enemy"];
						if(!$allyshieldlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito, actualshield, maxshield FROM npcs WHERE enemy='$enemy'ORDER BY (actualshield/maxshield) LIMIT 2")) die("Sérültek lekérése siekrtelen");
						while($allyshieldtomb = mysqli_fetch_assoc($allyshieldlekeres))
						{
							if($allyshieldtomb["karakterazonosito"] != $npc["karakterazonosito"])
							{
								$healshieldid = $allyshieldtomb["karakterazonosito"];
								$healactualshield = $allyshieldtomb["actualshield"];
								$healmaxshield = $allyshieldtomb["maxshield"];
								break;
							}
						}
						if(!$enemy and $npc["karakterazonosito"] != $_SESSION["karakterazonosito"])
						{
							$userid = $_SESSION["karakterazonosito"];
							if(!$usershieldlekeres = mysqli_query($_SESSION["conn"], "SELECT actualshield, maxshield FROM users WHERE karakterazonosito='$userid'")) die("Játékos életének lekérése sikertelen");
							$usershieldtomb = mysqli_fetch_assoc($usershieldlekeres);
							$useractualshield = $usershieldtomb["actualshield"];
							$usermaxshield = $usershieldtomb["maxshield"];
							
							$usershieldszazalek = $useractualshield/$usermaxshield;
							$healshieldszazalek = $healactualshield/$healmaxshield;
							
							if($usershieldszazalek <= $healshieldszazalek)
							{
								$healshieldid = $userid;
								$healactualshield = $useractualshield;
								$healmaxshield = $usermaxshield;
							}
						}
						if(!isset($healshieldid))
						{
							$npc["actualmana"] -= $tarolo["healerability0"]["manausage"];
							$npc["activeability0"] = $tarolo["healerability0"]["aktivkor"]+($npc["szintability0"]-1)*$tarolo["healerability0"]["aktivkorinc"];
							$npc["reloadability0"] = $tarolo["healerability0"]["reload"]-($npc["szintability0"]-1)*$tarolo["healerability0"]["reloadinc"];
							$healamount = ($npc["szintability0"]-1)*$tarolo["healerability0"]["ertekinc"]+$tarolo["healerability0"]["ertek"];
							$npc["actualshield"] += $healamount;
							if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];	
							$log[] = "$id tölti saját pajzsát.";
						}
						if(isset($healshieldid))
						{
							$npc["actualmana"] -= $tarolo["healerability0"]["manausage"];
							$npc["activeability0"] = $tarolo["healerability0"]["aktivkor"]+($npc["szintability0"]-1)*$tarolo["healerability0"]["aktivkorinc"];
							$npc["reloadability0"] = $tarolo["healerability0"]["reload"]-($npc["szintability0"]-1)*$tarolo["healerability0"]["reloadinc"];
							$healamount = ($npc["szintability0"]-1)*$tarolo["healerability0"]["ertekinc"]+$tarolo["healerability0"]["ertek"];
							$npc["actualshield"] += $healamount;
							if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
							$healactualshield += $healamount;
							if($healactualshield > $healmaxshield) $healactualshield = $healmaxshield;
							if($healshieldid == $userid) $tabla4 = "users";
							if($healshieldid != $userid) $tabla4 = "npcs";
							if(!$healupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla4 SET actualshield='$healactualshield' WHERE karakterazonosito='$healshieldid'")) die("shield feltöltése sikertellen");
							$log[] = "$id tölti $healshieldid pajzsát";
						}
					}
				break;
			}
		}
		if(isset($aktivateability1) and !$npc["disability"])
		{
			switch($npc["kaszt"])
			{
				case "warrior":
					if($tarolo["warriorability1"]["manausage"] <= $npc["actualmana"])
					{
						$npc["actualmana"] -= $tarolo["warriorability1"]["manausage"];
						$npc["reloadability1"] = $tarolo["warriorability1"]["reload"]-($npc["szintability1"]-1)*$tarolo["warriorability1"]["reloadinc"];
						$npc["activeability1"] = $tarolo["warriorability1"]["aktivkor"]+($npc["szintability1"]-1)*$tarolo["warriorability1"]["aktivkorinc"];
						$log[] = "$id aktiválta Shieldleech képességét";
					}
				break;
				case "mage":
					if($tarolo["mageability1"]["manausage"] <= $npc["actualmana"])
					{
						$npc["actualmana"] -= $tarolo["mageability1"]["manausage"];
						$npc["reloadability1"] = $tarolo["mageability1"]["reload"]-($npc["szintability1"]-1)*$tarolo["mageability1"]["reloadinc"];
						$npc["activeability1"] = $tarolo["mageability1"]["aktivkor"]+($npc["szintability1"]-1)*$tarolo["mageability1"]["aktivkorinc"];
						$log[] = "$id aktiválta Droptarget képességét";
					}
				break;
				case "paladin":
					if($tarolo["paladinability1"]["manausage"] <= $npc["actualmana"])
					{
						$saveid = 0;
						$enemy = $npc["enemy"];
						if(!$allylekeres = mysqli_query($_SESSION["conn"], "SELECT actualhp, maxhp, karakterazonosito FROM npcs WHERE enemy='$enemy' ORDER BY (actualhp/maxhp) LIMIT 2")) die("Hpk lekérése sikertelen");
						while($allytomb = mysqli_fetch_assoc($allylekeres))
						{
							if($allytomb["karakterazonosito"] != $npc["karakterazonosito"])
							{
								$saveid = $allytomb["karakterazonosito"];
								break;
							}
						}
						if(!$enemy and $id != $_SESSION["karakterazonosito"])
						{
							$userid = $_SESSION["karakterazonosito"];
							if(!$usersavelekeres = mysqli_query($_SESSION["conn"], "SELECT actualhp, maxhp FROM users WHERE karakterazonosito='$userid'")) die("Játékos hp lekérése sikertelen");
							$usersavetomb = mysqli_fetch_assoc($usersavelekeres);
							if($usersavetomb["actualhp"]/$usersavetomb["maxhp"] < $allytomb["actualhp"]/$allytomb["maxhp"]) $saveid = $userid;
						}
						if($saveid)
						{
							if(!$tamadjaklekeres = mysqli_query($_SESSION["conn"], "SELECT lastattack FROM battle WHERE karakterazonosito='$saveid'")) die("Saveid támadottságának lekérése siekrtelen");
							foreach($tamadjaktomb = mysqli_fetch_row($tamadjaklekeres) as $tamadjak);
							if($tamadjak == 2)
							{
								$npc["actualmana"] -= $tarolo["paladinability1"]["manausage"];
								$npc["activeability1"] = $tarolo["paladinability1"]["aktivkor"]+($npc["szintability1"]-1)*$tarolo["paladinability1"]["aktivkorinc"];
								$npc["reloadability1"] = $tarolo["paladinability1"]["reload"]-($npc["szintability1"]-1)*$tarolo["paladinability1"]["reloadinc"];
								$id = $npc["karakterazonosito"];
								if(!$gettargetupdate = mysqli_query($_SESSION["conn"], "UPDATE battle SET target='$id' WHERE target='$saveid'")) die("gettarget frissítése siketelen");
								$log[] = "$id gettartget képességet használt $saveid-n";
							}
						}
					}
				break;
				case "healer":
					if($tarolo["healerability1"]["manausage"] <= $npc["actualmana"])
					{
						$enemy = $npc["enemy"];
						if(!$allyhplekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito, actualhp, maxhp FROM npcs WHERE enemy='$enemy' ORDER BY (actualhp/maxhp) LIMIT 2")) die("Sérültek lekérése siekrtelen");
						while($allyhptomb = mysqli_fetch_assoc($allyhplekeres))
						{
							if($allyhptomb["karakterazonosito"] != $npc["karakterazonosito"])
							{
								$healhpid = $allyhptomb["karakterazonosito"];
								$healactualhp = $allyhptomb["actualhp"];
								$healmaxhp = $allyhptomb["maxhp"];
								break;
							}
						}
						if(!$enemy)
						{
							$userid = $_SESSION["karakterazonosito"];
							if(!$userhplekeres = mysqli_query($_SESSION["conn"], "SELECT actualhp, maxhp FROM users WHERE karakterazonosito='$userid'")) die("Játékos életének lekérése sikertelen");
							$userhptomb = mysqli_fetch_assoc($userhplekeres);
							$useractualhp = $userhptomb["actualhp"];
							$usermaxhp = $userhptomb["maxhp"];
							
							$userhpszazalek = $useractualhp/$usermaxhp;
							$healhpszazalek = $healactualhp/$healmaxhp;
							
							if($userhpszazalek <= $healhpszazalek)
							{
								$healhpid = $userid;
								$healactualhp = $useractualhp;
								$healmaxhp = $usermaxhp;
							}
						}
						if(!isset($healhpid))
						{
							$npc["actualmana"] -= $tarolo["healerability1"]["manausage"];
							$npc["activeability1"] = $tarolo["healerability1"]["aktivkor"]+($npc["szintability1"]-1)*$tarolo["healerability1"]["aktivkorinc"];
							$npc["reloadability1"] = $tarolo["healerability1"]["reload"]-($npc["szintability1"]-1)*$tarolo["healerability1"]["reloadinc"];
							$healamount = ($npc["szintability1"]-1)*$tarolo["healerability1"]["ertekinc"]+$tarolo["healerability1"]["ertek"];
							$npc["actualhp"] += $healamount;
							if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
							$log[] = "$id tölti saját életét.";
						}
						if(isset($healhpid))
						{
							$npc["actualmana"] -= $tarolo["healerability1"]["manausage"];
							$npc["activeability1"] = $tarolo["healerability1"]["aktivkor"]+($npc["szintability1"]-1)*$tarolo["healerability1"]["aktivkorinc"];
							$npc["reloadability1"] = $tarolo["healerability1"]["reload"]-($npc["szintability1"]-1)*$tarolo["healerability1"]["reloadinc"];
							$healamount = ($npc["szintability1"]-1)*$tarolo["healerability1"]["ertekinc"]+$tarolo["healerability1"]["ertek"];
							$npc["actualhp"] += $healamount;
							if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
							$healactualhp += $healamount;
							if($healactualhp > $healmaxhp) $healactualhp = $healmaxhp;
							if($healhpid == $userid) $tabla4 = "users";
							if($healhpid != $userid) $tabla4 = "npcs";
							if(!$healupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla4 SET actualhp='$healactualhp' WHERE karakterazonosito='$healhpid'")) die("HP feltöltése sikertellen");
							$log[] = "$id tölti $healhpid életét";
						}
					}
				break;
			}
		}
		if($npc["kaszt"] == "warrior" and $npc["activeability1"])
		{
			$shieldleech = $shieldleechalap*($tarolo["warriorability1"]["ertek"]+($npc["szintability1"]-1)*$tarolo["warriorability1"]["ertekinc"])/100;
			settype($shieldleech, "integer");
			$npc["actualshield"] = $npc["actualshield"]+$shieldlech;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
			$log[] = "$id képessége $shieldleech pajzsot töltött.";
		}
		if($npc["disability"]) $npc["disability"] -= 1;
		settype($npc["actualdmg"], "integer");
		settype($npc["maxmana"], "integer");
		settype($npc["actualmana"], "integer");
		//=====TÁBLÁK FRISSÍTÉSE=====
		foreach($npc as $name=>$ertek) $$name = $ertek;
		if(!$battlefieldupdate = mysqli_query($_SESSION["conn"], "UPDATE battle SET dmgreceived='$dmgreceived', target='$target', attacked='$attacked', lastattack='$lastattack', ammo='$ammo', disability='$disability' WHERE karakterazonosito='$id'")) die("Battle tábla frissítése siekrtelen");
		if(!$tablaupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla1 SET hpboosterrounds='$hpboosterrounds', hpfelszbonus='$hpfelszbonus', maxhp='$maxhp', actualhp='$actualhp', shieldboosterrounds='$shieldboosterrounds', shieldfelszbonus='$shieldfelszbonus', maxshield='$maxshield', actualshield='$actualshield', penetfelszbonus='$penetfelszbonus', ertekpenet='$ertekpenet', szintmana='$szintmana', manaboosterrounds='$manaboosterrounds', manafelszbonus='$manafelszbonus', maxmana='$maxmana', actualmana='$actualmana', dmgboosterrounds='$dmgboosterrounds', dmgfelszbonus='$dmgfelszbonus', actualdmg='$actualdmg', attackboosterrounds='$attackboosterrounds', accurboosterrounds='$accurboosterrounds', activefelszereles='$activefelszereles', activeability0='$activeability0', reloadability0='$reloadability0', activeability1='$activeability1', reloadability1='$reloadability1', hppotnum='$hppotnum', hppotactive='$hppotactive', hppotreload='$hppotreload', shieldpotnum='$shieldpotnum', shieldpotactive='$shieldpotactive', shieldpotreload='$shieldpotreload', manapotnum='$manapotnum', manapotactive='$manapotactive', manapotreload='$manapotreload', x2num='$x2num', x3num='$x3num', x4num='$x4num', empnum='$empnum', empreload='$empreload',ishnum='$ishnum', ishreload='$ishreload', pldnum='$pldnum', pldactive='$pldactive', pldreload='$pldreload', cloaknum='$cloaknum', cloaked='$cloaked', empactive='$empactive', ishactive='$ishactive'  WHERE karakterazonosito='$id'")) die("$tabla1 frissítése sikertelen");

		$log[] = "";
		$_SESSION["log"] = $log;
		
	}
		
	function mobkor($tarolo, $npc, $log)
	{
		$kaszt = $npc["kaszt"];
		$id = $npc["karakterazonosito"];
		$npc["attacked"] = 0;
		
		if($npc["dmgreceived"])
		{
			$npc["lastattack"] = 3;
			$kapottsebzes = $npc["dmgreceived"];
			$log[] = "$id $kapottsebzes sebzést szenvedett el";
			$npc["actualshield"] -= $npc["dmgreceived"]*$npc["ertekpenet"]/100;
			$npc["actualhp"] -= $npc["dmgreceived"]*(1-$npc["ertekpenet"]/100);
			if($npc["actualshield"] < 0)
			{
				$npc["actualhp"] += $npc["actualshield"];
				$npc["actualshield"] = 0;
			}
			$npc["dmgreceived"] = 0;
			if($npc["actualhp"] <= 0)
			{
				$log[] = "$id meghalt.";
				if(!$lovoklekeres = mysqli_query($_SESSION["conn"], "SELECT target FROM battle WHERE target='$id' and attacked='1'")) die("Lövők számának lekérése siekrtelen");
				$lovok = mysqli_num_rows($lovoklekeres);
				if(!$lovok) $lovok = 1;
				$log[] = "$lovok lőtte $id-t";
				
				$reward = ($tarolo["$kaszt"]["basicreward"]+$npc["karakterszint"]*$tarolo["$kaszt"]["rewardinc"])/$lovok;
				settype($reward, "integer");
				if(!$payforlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito, attacked FROM battle WHERE target='$id'")) die("Gyilkosok lekérése sikertelen");
				while($payfortomb = mysqli_fetch_assoc($payforlekeres))
				{
					$payforid = $payfortomb["karakterazonosito"];
					{
						if($payforid == $_SESSION["karakterazonosito"] and $payfortomb["attacked"])
						{
							if(!$payforactualmoneylekeres = mysqli_query($_SESSION["conn"], "SELECT actualmoney FROM users WHERE karakterazonosito='$payforid'")) die("Játékos pénzének lekérése sikertelen");
							foreach($moneytomb = mysqli_fetch_row($payforactualmoneylekeres) as $payforactualmoney);
							$payfornewmoney = $payforactualmoney+$reward;
							if(!$payformoneyupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET actualmoney='$payfornewmoney' WHERE karakterazonosito='$payforid'")) die("Jutalom kifizetése siekrtelen");
							$log[] = "$payforid részére $reward kifizetve.";
						}
					}
				}
				if(!$battlefieldtorles = mysqli_query($_SESSION["conn"], "DELETE FROM battle WHERE karakterazonosito='$id'")) die("Battle tábla törlése sikertelen");
				if(!$npctorles = mysqli_query($_SESSION["conn"], "DELETE FROM npcs WHERE karakterazonosito='$id'")) die("Npc tábla törlése sikertelen");
				if(!$targettorles = mysqli_query($_SESSION["conn"], "UPDATE battle SET target='0' WHERE target='$id'")) die("Célpontok törlése sikertelen");
				$_POST["target"] = 0;
				$_SESSION["gamesession"] += 1;
				$log[] = "$id törlése sikeres";
				$log[] = "";
				return $log;
			}
		}
		if(!$npc["lastattack"] and $npc["actualshield"] != $npc["maxshield"])
		{
			$npc["actualshield"] = $npc["actualshield"]+$npc["maxshield"]*0.2;
			if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
			$log[] = "$id pajzsa töltődött.";
		}
		if($npc["lastattack"] == 3) $npc["activefelszereles"] = 1;
		if(!$npc["lastattack"] and !$tarolo["$kaszt"]["agressive"])  $npc["activefelszereles"] = 0;
		if($npc["lastattack"]) $npc["lastattack"] -= 1;
		
		if(!$npc["target"] and $npc["activefelszereles"])
		{
			if($tarolo["$kaszt"]["agressive"])
			{
				if(!$npclekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE NOT enemy='mob' AND NOT cloaked='1'")) die("Célpontok lekérése sikertelenn");
				$celpontszam = mysqli_num_rows($npclekeres);
				$celpont = rand(0, $celpontszam);
				if(!$celpont) $targetid = $_SESSION["karakterazonosito"];
				else
				{
					while($celponttomb = mysqli_fetch_row($npclekeres))
					{
						foreach($celponttomb as $cp)
						{
							$cptomb[] = $cp;
						}
					}
					$cpszam = $celpont-1;
					$targetid = $cptomb["$cpszam"];
					$log[] = "$id célpontja: $targetid";
				}
			}
			if(!$tarolo["$kaszt"]["agressive"])
			{
				if(!$celpontlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM battle WHERE target='$id'")) die("Mobot támadók idlekérése siekrtelen");
				$celpontszam = mysqli_num_rows($celpontlekeres);
				$celpont = rand(1, $celpontszam)-1;
				while($celponttomb = mysqli_fetch_row($celpontlekeres))
				{
					foreach($celponttomb as $cp) $cptomb[] = $cp;
				}
				$targetid = $cptomb[$celpont];
				$log[] = "$id jelöli: $targetid";
			}
		}
		if(isset($targetid))
		{
			//=====CÉLPONT BEMÉRÉSE=====
			if($targetid == $_SESSION["karakterazonosito"]) $tabla2 = "users";
			else $tabla2 = "npcs";
			
			if(!$celpontcloaklekerdezes = mysqli_query($_SESSION["conn"], "SELECT cloaked FROM $tabla2 WHERE karakterazonosito='$targetid'")) die("célpont álcázottságának lekérése siekrtelen");
			foreach($celpontcloaktomb = mysqli_fetch_row($celpontcloaklekerdezes) as $celpontcloaked);
			$cloakedbonus = 0;
			if($celpontcloaked) $cloakedbonus = 12000;

			$accuracy = rand(0, 2000);
			$missclick = 500+$cloakedbonus;
			
			if($accuracy >= $missclick)
			{
				$npc["target"] = $targetid;
				$log[] = "$id sikeresen bemérte $targetid célpontot";
			}
			if($accuracy < $missclick)
			{
				$npc["target"] = 0;
				$log[] = "$id sikertelenül jelölte $targetid-t";
			}
		}
		if($npc["target"])
		{
			$tg = $npc["target"];
			$log[] = "$id támadja: $tg";
			if(!$sebzesleker = mysqli_query($_SESSION["conn"], "SELECT dmgreceived FROM battle WHERE karakterazonosito='$tg'")) die("Sebzés lelérése sikertelen");
			foreach($sebzestomb = mysqli_fetch_row($sebzesleker) as $okozottsebzes);
			
			$dodgebonus = 0;
			if($npc["target"] == $_SESSION["karakterazonosito"]) $tabla5 = "users";
			else $tabla5 = "npcs";
			$targetdodge = $npc["target"];
			if(!$dodgelekeres = mysqli_query($_SESSION["conn"], "SELECT kaszt, szintability0, activeability0 FROM $tabla5 WHERE karakterazonosito='$targetdodge'")) die("Célpont dodge lekérése sikertelen");
			$dodgetomb = mysqli_fetch_assoc($dodgelekeres);
			if($dodgetomb["kaszt"] == "mage" and $dodgetomb["activeability0"]) $dodgebonus = $tarolo["mageability0"]["ertek"]+($dodgetomb["szintability0"]-1)*$tarolo["mageability0"]["ertekinc"];
			
			for($sz = 0; $sz < $npc["dmgmultiplierlevel"]; $sz++)
			{
				$pldbonus = 0;
				if($npc["pldactive"]) $pldbonus = $tarolo["pld"]["pldbonus"];
				$kiteres = 1200+$pldbonus+$dodgebonus;
				$talalat = rand(1000, 2000);
				if($talalat > $kiteres)
				{
					$sebzesa = $npc["actualdmg"]*rand(70, 120)/100;
					settype($sebzesa, "integer");
					$okozottsebzes = $okozottsebzes+$sebzesa;
					$log[] = "$id $sebzesa sebzést okozott.";
				}
				if($talalat <= $kiteres) $log[] = "$id mellé lőtt.";
			}
			$npc["attacked"] = 1;
			if(!$sebzesfrissit = mysqli_query($_SESSION["conn"], "UPDATE battle SET dmgreceived='$okozottsebzes' WHERE karakterazonosito='$tg'")) die("Sebzés frissítése sikertelen");
		}
		
		if($npc["pldactive"]) $npc["pldactive"] -= 1;

		foreach($npc as $name=>$ertek) $$name = $ertek;
		
		if(!$battleupdate = mysqli_query($_SESSION["conn"], "UPDATE battle SET lastattack='$lastattack', target='$target', attacked='$attacked', dmgreceived='$dmgreceived' WHERE karakterazonosito='$id'")) die("Battle tábla frissításe sikertelen");
		if(!$npcsupdate = mysqli_query($_SESSION["conn"], "UPDATE npcs SET actualhp='$actualhp', actualshield='$actualshield', activefelszereles='$activefelszereles', pldactive='$pldactive' WHERE karakterazonosito='$id'")) die("Npcs tábla frissítése siekrtelen");
		$log[] = "";
		return $log;
	}
	
	function attackset($tarolo)
	{
		$log = $_SESSION["log"];
		if(!$battlelekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM battle")) die("Karakterek lekérése sikertelen");
		while($battle = mysqli_fetch_row($battlelekeres))
		{
			foreach($battle as $id)
			{
				$tabla = "npcs";
				if($id == $_SESSION["karakterazonosito"]) $tabla = "users";
			}
			if(!$karakterlekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM $tabla WHERE karakterazonosito='$id'")) die("Karakteradatok lekérése sikertelen");
			foreach($karaktertomb = mysqli_fetch_assoc($karakterlekeres) as $mezo=>$ertek) $npc["$mezo"] = $ertek;
			
			$boosterbonus = 1;
			if($npc["attackboosterrounds"]) $boosterbonus = 1.25;
			
			$attackbonus = 0;
			if($npc["kaszt"] == "mage" and $npc["szintpassziv"] > 0) $attackbonus = $tarolo["magepassziv"]["ertek"]+$npc["szintpassziv"]*$tarolo["magepassziv"]["ertekinc"];
			
			$cloakedbonus = 0;
			if($npc["cloaked"]) $cloakedbonus = 500;
			
			$baseattack = rand(1000, 2000);
			
			$attack = ($baseattack+$attackbonus+$cloakedbonus)*$boosterbonus;
			settype($attack, "integer");
			
			if(!$feltolt = mysqli_query($_SESSION["conn"], "UPDATE battle SET attack='$attack' WHERE karakterazonosito='$id'")) die("Attack feltöltése sikertelen");
			$log[] = "$id $attack támadóértéket kapott.";
		}
		$log[] = "";
		$_SESSION["log"] = $log;
	}
?>