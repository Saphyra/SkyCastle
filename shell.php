<?php
	include("connection.php");
	include("mobgenerate.php");
	include("karaktergenerate.php");
	$_SESSION["log"] = "";
	include_once("korfeldolgoz.php");
	
	$id = $_SESSION["karakterazonosito"];
	if(!$szintleker = mysqli_query($_SESSION["conn"], "SELECT karakterszint FROM users WHERE karakterazonosito='$id'")) die("Karakterszint lekérése sikertelen");
	foreach($szinttomb = mysqli_fetch_row($szintleker) as $szint);
	
	if(!$friendszamleker = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='0'")) die("Barátok lekérése sikertelen");
	$friendszam = mysqli_num_rows($friendszamleker);
	if(!$enemyszamleker = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='1'")) DIE("Ellenségek lekérése sikertelen");
	$enemyszam = mysqli_num_rows($enemyszamleker);
	if(!$mobszamleker = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='mob'")) die("Mobszám lekérése sikertelen");
	$mobszam = mysqli_num_rows($mobszamleker);
	
	$log = $_SESSION["log"];
	$log[] = $_SESSION["gamemode"];
	
	if($_SESSION["gamemode"] == "normal")
	{
		if(!rand(0, 150))
		{
			switch(rand(0, 2))
			{
				case 0:
					$_SESSION["gamemodeget"] = "xvx";
					$log[] = "xvx kapu elérhető";
				break;
				case 1:
					$_SESSION["gamemodeget"] = "ggstylemob";
					$log[] = "ggstylemob kapu elérhető";
				break;
				case 2:
					$_SESSION["gamemodeget"] = "ggstyleenemy";
					$log[] = "ggstyleenemy kapu elérhető";
				break;
			}
		}
	}
	
	if($_SESSION["gamemode"] == "normal")
	{
		if($friendszam < 4)
		{
			if(!rand(0, 2))
			{
				$log[] = "Friend";
				$genszazalek = rand(1, 100);
				$log[] = "$genszazalek";
				if($genszazalek >= 1 and $genszazalek <= 10) $gen = 4-$friendszam;
				elseif($genszazalek >= 11 and $genszazalek <= 30) $gen = 3;
				elseif($genszazalek >= 31 and $genszazalek <= 60) $gen = 2;
				elseif($genszazalek >= 61 and $genszazalek <= 100) $gen = 1;
				$log[] = "$gen gen";
				if($friendszam+$gen > 4) $gen = $friendszam-$gen-1;
				$log[] = "$gen érték";
				$szam = rand(1, $gen);
				$log[] = "$szam generált";
				for($x = 0; $x < $szam; $x++)
				{
					karaktergenerate($szint, 0);
					$log[] = "Barát érkezett";
				}
			}
		}
		if($enemyszam < 5)
		{
			if(!rand(0, 20))
			{
				$log[] = "Enemy";
				$genszazalek = rand(1, 200);
				$log[] = "$genszazalek";
				if($genszazalek >= 1 and $genszazalek <= 10) $gen = 5-$enemyszam;
				elseif($genszazalek >= 11 and $genszazalek <= 30) $gen = 4;
				elseif($genszazalek >= 31 and $genszazalek <= 60) $gen = 3;
				elseif($genszazalek >= 61 and $genszazalek <= 120) $gen = 2;
				elseif($genszazalek >= 121 and $genszazalek <= 200) $gen = 1;
				$log[] = "$gen gen";
				if($enemyszam+$gen > 5) $gen = $enemyszam-$gen-1;
				$log[] = "$gen érték";
				$szam = rand(1, $gen);
				$log[] = "$szam generált";
				for($x = 0; $x < $szam; $x++)
				{
					karaktergenerate($szint, 1);
					$log[] = "Ellenség érkezett";
				}

			}
		}
		if($mobszam < 5)
		{
			if(!rand(0, 1))
			{
				$log[] = "Mob";
				$genszazalek = rand(1, 200);
				$log[] = "$genszazalek";
				if($genszazalek >= 1 and $genszazalek <= 10) $gen = 5-$mobszam;
				elseif($genszazalek >= 11 and $genszazalek <= 30) $gen = 4;
				elseif($genszazalek >= 31 and $genszazalek <= 60) $gen = 3;
				elseif($genszazalek >= 61 and $genszazalek <= 120) $gen = 2;
				elseif($genszazalek >= 121 and $genszazalek <= 200) $gen = 1;
				$log[] = "$gen gen";
				if($mobszam+$gen > 5) $gen = $mobszam-$gen-1;
				$log[] = "$gen érték";
				$szam = rand(1, $gen);
				$log[] = "$szam generált";
				for($x = 0; $x < $szam; $x++)
				{
					mobgenerate($szint);
					$log[] = "Mob érkezett";
				}
			}
		}
	}
	if($_SESSION["gamemode"] == "xvx")
	{
		if(!$_SESSION["gamesession"])
		{
			$x = rand(1, 25);
			$log[] = "$x vs $x";
			for($szam = 0; $szam < $x-1; $szam++)
			{
				karaktergenerate($szint, 0);
			}
			for($szam = 0; $szam < $x; $szam++)
			{
				karaktergenerate($szint, 1);
			}
			$_SESSION["gamesession"] = $x;
		}
		if($_SESSION["gamesession"])
		{
			if(!$enemyszamlekeres = mysqli_query($_SESSION["conn"], "SELECT karakterazonosito FROM npcs WHERE enemy='1'")) die("Enemyszam lekérése sikertelen");
			if(!mysqli_num_rows($enemyszamlekeres)) $_SESSION["gamesession"] = 0-$_SESSION["gamesession"];
		}
		if($_SESSION["gamesession"] < 0)
		{
			if(!$karakterleker = mysqli_query($_SESSION["conn"], "SELECT * FROM users WHERE karakterazonosito='$id'")) die("Karakter felszerelésének lekérése siekrtelen");
			foreach($karakterlekertomb = mysqli_fetch_assoc($karakterleker) as $name=>$ertek) $npc["$name"] = $ertek;
			$npc["x4num"] += 50;
			$npc["actualmoney"] += 50000;
			for($szam = 0; $szam > $_SESSION["gamesession"]*2; $szam--)
			{
				switch(rand(1, 17))
				{
					case 1:
						$amount = rand(1, 250);
						$npc["hpboosterrounds"] += $amount;
						$log[] = "$id $amount környi életerő boostert kapott";
					break;
					case 2:
						$amount = rand(1, 250);
						$npc["shieldboosterrounds"] += $amount;
						$log[] = "$id $amount környi pajzs boostert kapott";
					break;
					case 3:
						$amount = rand(1, 250);
						$npc["manaboosterrounds"] += $amount;
						$log[] = "$id $amount környi mana boostert kapott";
					break;
					case 4:
						$amount = rand(1, 250);
						$npc["dmgboosterrounds"] += $amount;
						$log[] = "$id $amount környi sebzés boostert kapott";
					break;
					case 5:
						$amount = rand(1, 250);
						$npc["attackboosterrounds"] += $amount;
						$log[] = "$id $amount környi attack boostert kapott";
					break;
					case 6:
						$amount = rand(1, 250);
						$npc["accurboosterrounds"] += $amount;
						$log[] = "$id $amount környi jelölés boostert kapott";
					break;
					case 7:
						$amount = rand(1, 5);
						$npc["empnum"] += $amount;
						$log[] = "$id $amount EMPet kapott";
					break;
					case 8:
						$amount = rand(1, 5);
						$npc["ishnum"] += $amount;
						$log[] = "$id $amount ISH-t kapott";
					break;
					case 9:
						$amount = rand(1, 5);
						$npc["pldnum"] += $amount;
						$log[] = "$id $amount PLD-t kapott";
					break;
					case 10:
						$amount = rand(1, 5);
						$npc["cloaknum"] += $amount;
						$log[] = "$id $amount álcát kapott";
					break;
					case 11:
						$amount = rand(1, 5);
						$npc["hppotnum"] += $amount;
						$log[] = "$id $amount életerő potit kapott";
					break;
					case 12:
						$amount = rand(1, 5);
						$npc["shieldpotnum"] += $amount;
						$log[] = "$id $amount pajzs potit kapott";
					break;
					case 13:
						$amount = rand(1, 5);
						$npc["manapotnum"] += $amount;
						$log[] = "$id $amount manapotit kapott";
					break;
					case 14:
						$amount = rand(1, 100000);
						$npc["actualmoney"] += $amount;
						$log[] = "$id $amount pénzt kapott";
					break;
					case 15:
						$amount = rand(1, 100);
						$npc["x2num"] += $amount;
						$log[] = "$id $amount x2-t kapott";
					break;
					case 16:
						$amount = rand(1, 100);
						$npc["x3num"] += $amount;
						$log[] = "$id $amount x3-at kapott";
					break;
					case 17:
						$amount = rand(1, 100);
						$npc["x4num"] += $amount;
						$log[] = "$id $amount x4-et kapott";
					break;
					case 100:
						$amount = rand(1, 1);
						$npc[""] += $amount;
						$log[] = "$id $amount kapott";
					break;
				}
			}
			$_SESSION["gamesession"] = 0;
			$_SESSION["gamemode"] = "normal";
			foreach($npc as $name=>$ertek) $$name = $ertek;
			if(!$karakterupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET hpboosterrounds='$hpboosterrounds', shieldboosterrounds='$shieldboosterrounds', manaboosterrounds='$manaboosterrounds', dmgboosterrounds='$dmgboosterrounds', attackboosterrounds='$attackboosterrounds', accurboosterrounds='$accurboosterrounds', empnum='$empnum', ishnum='$ishnum', pldnum='$pldnum', cloaknum='$cloaknum', hppotnum='$hppotnum', shieldpotnum='$shieldpotnum', manapotnum='$manapotnum', actualmoney='$actualmoney', x2num='$x2num', x3num='$x3num', x4num='$x4num' WHERE karakterazonosito='$id'")) die("Karakter értékeinek frissítése sikertelen");
		}
	}
	if($_SESSION["gamemode"] == "ggstylemob")
	{
		$sess = $_SESSION["gamesession"];
		$log[] = "sessionszint: $sess";
		if(!$mobszam)
		{
			$mobszint = $_SESSION["gamesession"]*$_SESSION["gamesession"]*1.1;
			settype($mobszint, "integer");
			$gen = rand(0, $mobszint);
			$log[] = "$gen";
			mobgenerate($gen);
			$_SESSION["gamesession"] += 0.5;
			recharge($_SESSION["karakterazonosito"]);
		}
	}
	if($_SESSION["gamemode"] == "ggstyleenemy")
	{
		$sess = $_SESSION["gamesession"];
		$log[] = "sessionszint: $sess";
		if(!$enemyszam)
		{
			$enemyszint = $_SESSION["gamesession"]*$_SESSION["gamesession"]*1.1;
			$log[] = "$enemyszint";
			settype($enemyszint, "integer");
			$gen = rand(0, $enemyszint);
			$log[] = "$gen (gen)";
			karaktergenerate($gen, 1);
			$_SESSION["gamesession"] += 0.5;
			recharge($_SESSION["karakterazonosito"]);
		}
	}
	$log[] = "";
	$_SESSION["log"] = $log;
	
	header("location:battlefield.php");
	
?>

<?php
	function recharge($id)
	{
		if(!$userleker = mysqli_query($_SESSION["conn"], "SELECT * FROM users WHERE karakterazonosito='$id'")) die("Karakteradatok bekérése sikerteelen");
		foreach($usertomb = mysqli_fetch_assoc($userleker) as $name=>$ertek)
		{
			$n = $name;
			$npc["$n"] = $ertek;
		}
		
		$npc["actualhp"] += $npc["maxhp"]*0.5;
		if($npc["actualhp"] > $npc["maxhp"]) $npc["actualhp"] = $npc["maxhp"];
		
		$npc["actualshield"] += $npc["maxshield"]*0.5;
		if($npc["actualshield"] > $npc["maxshield"]) $npc["actualshield"] = $npc["maxshield"];
		
		$npc["actualmana"] += $npc["maxmana"]*0.5;
		if($npc["actualmana"] > $npc["maxmana"]) $npc["actualmana"] = $npc["maxmana"];
		
		foreach($npc as $name=>$ertek) $$name = $ertek;
		if(!$userupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET actualhp='$actualhp', actualshield='$actualshield', actualmana='$actualmana' WHERE karakterazonosito='$id'")) die("Karakteradatok frissítése sikertelen");
		return 1;
	}
?>