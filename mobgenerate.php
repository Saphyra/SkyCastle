<?php
	include("connection.php");
	
	function mobgenerate($szint)
	{
		$szam = $szint*2;
		settype($szam, "integer");
		settype($szint, "integer");
		$karakterszint = rand($szint/2, $szam);
		$mob["karakterszint"] = $karakterszint;
		
		do
		{

			$karakterazonosito = "id" . rand(1, 1000);
			
			if(!$azonositonpc = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE karakterazonosito='$karakterazonosito'")) die("NPC-k id lekérdezése sikertelen");
			if(!$azonositousers = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM users WHERE karakterazonosito='$karakterazonosito'")) die("Felhasználók id lekérdezése sikertelen");
			
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
		$mob["karakterazonosito"] = $karakterazonosito;
		
		
		$mobtype = rand(0, 5000);
		if($mobtype == 0) $mob["kaszt"] = "Zumber";
		elseif($mobtype >= 1 and $mobtype <=150) $mob["kaszt"] = "Upidynyx";
		elseif($mobtype >= 151 and $mobtype <= 300) $mob["kaszt"] = "Talsub";
		elseif($mobtype >= 301 and $mobtype <= 500) $mob["kaszt"] = "Suder";
		elseif($mobtype >= 501 and $mobtype <= 1000) $mob["kaszt"] = "Nuban";
		elseif($mobtype >= 1001 and $mobtype <= 1500) $mob["kaszt"] = "Nazer";
		elseif($mobtype >= 1501 and $mobtype <= 2000) $mob["kaszt"] = "Naman";
		elseif($mobtype >= 2001 and $mobtype <= 3000) $mob["kaszt"] = "Gordanir";
		elseif($mobtype >= 3001 and $mobtype <= 4000) $mob["kaszt"] = "Epuregon";
		elseif($mobtype >= 4001 and $mobtype <= 5000) $mob["kaszt"] = "Biloxass";
		
		$mob["enemy"] = "mob";
		
		$mobname = $mob["kaszt"];
		if(!$moblekeres = mysqli_query($_SESSION["conn"], "SELECT * FROM mobs WHERE mobname='$mobname'")) die("Mob lekérése sikertelen");
		foreach($mobtomb = mysqli_fetch_assoc($moblekeres) as $name=>$ertek) $mob["$name"] = $ertek;
		
		$mob["szinthp"] = 0;
		$mob["szintshield"] = 0;
		$mob["szintdmg"] = 0;
		for($szam = 0; $szam < $mob["karakterszint"]; $szam++)
		{
			$kp = rand(1, 3);
			switch($kp)
			{
				case 1: $mob["szinthp"] += 1; break;
				case 2: $mob["szintshield"] += 1; break;
				case 3: $mob["szintdmg"] += 1; break;
			}
		}
		
		$mob["maxhp"] = $mob["basichp"]+$mob["szinthp"]*$mob["hpinc"];
		$mob["actualhp"] = $mob["maxhp"];
		if(rand(0, 1)) $mob["actualhp"] = $mob["maxhp"]*rand(1, 99)/100;
		
		$mob["maxshield"] = $mob["basicshield"]+$mob["szintshield"]*$mob["shieldinc"];
		$mob["actualshield"] = $mob["maxshield"];
		if(rand(0, 1)) $mob["actualshield"] = $mob["maxshield"]*rand(1, 99)/100;
		
		$mob["actualdmg"] = $mob["basicdmg"]+$mob["dmginc"]*$mob["szintdmg"];
		
		$mob["reward"] = $mob["basicreward"]+$mob["karakterszint"]*$mob["rewardinc"];
		
		if(1)
		{
		foreach($mob as $name=>$ertek) $$name = $ertek;
		if(!$battlefeltolt = mysqli_query($_SESSION["conn"], "INSERT INTO battle (karakterazonosito, ammo) VALUES ('$karakterazonosito', 'x1')")) die("Battle feltöltése sikertelen");
		if(!$npcfeltolt = mysqli_query($_SESSION["conn"], "INSERT INTO npcs (enemy, karakterazonosito, karakterszint, kaszt, activefelszereles, szinthp, basichp, maxhp, actualhp, szintshield, basicshield, maxshield, actualshield, ertekpenet, basicdmg, actualdmg, dmgmultiplierlevel) VALUES ('$enemy', '$karakterazonosito', '$karakterszint', '$kaszt', '$agressive', '$szinthp', '$basichp', '$maxhp', '$actualhp', '$szintshield', '$basicshield', '$maxshield', '$actualshield', '$ertekpenet', '$basicdmg', '$actualdmg', '$dmgmultiplierlevel')")) die("Npcs tábla feltöltése siekrtelen");
		}
		
	}