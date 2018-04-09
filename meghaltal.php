<?php
	include("connection.php");
	include("interfacetarolo.php");
	$log = $_SESSION["log"];
	if($_SESSION["gamemode"] == "ggstylemob" or $_SESSION["gamemode"] == "ggstyleenemy")
	{
		$sess = $_SESSION["gamesession"];
		$log[] = "Session: $sess";
		$id = $_SESSION["karakterazonosito"];
		if(!$karakterleker = mysqli_query($_SESSION["conn"], "SELECT * FROM users WHERE karakterazonosito='$id'")) die("Karakter felszerelésének lekérése siekrtelen");
		foreach($karakterlekertomb = mysqli_fetch_assoc($karakterleker) as $name=>$ertek) $npc["$name"] = $ertek;
		$npc["x4num"] += 50;
		$npc["actualmoney"] += 50000;
		$amount1 = $_SESSION["gamesession"]*($_SESSION["gamesession"]/3);
		$log[] = "amount: $amount1";
		for($szam = 0; $szam < $amount1; $szam++)
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
					$amount = rand(1, 200);
					$npc["x4num"] += $amount;
					$log[] = "$id $amount x4-et kapott";
				break;
			}
		}
		foreach($npc as $name=>$ertek) $$name = $ertek;
		if(!$karakterupdate = mysqli_query($_SESSION["conn"], "UPDATE users SET hpboosterrounds='$hpboosterrounds', shieldboosterrounds='$shieldboosterrounds', manaboosterrounds='$manaboosterrounds', dmgboosterrounds='$dmgboosterrounds', attackboosterrounds='$attackboosterrounds', accurboosterrounds='$accurboosterrounds', empnum='$empnum', ishnum='$ishnum', pldnum='$pldnum', cloaknum='$cloaknum', hppotnum='$hppotnum', shieldpotnum='$shieldpotnum', manapotnum='$manapotnum', actualmoney='$actualmoney', x2num='$x2num', x3num='$x3num', x4num='$x4num' WHERE karakterazonosito='$id'")) die("Karakter értékeinek frissítése sikertelen");
		$_SESSION["gamesession"] = 0;
	}
	
	$log[] = "";
	$_SESSION["log"] = $log;
?>
<HTML>
	<HEAD>
		<TITLE>Meghaltál!</TITLE>
	</HEAD>
<BODY bgcolor='lightblue'>
	<?php head($_SESSION["karakterazonosito"], $_SESSION["username"]); ?>
	<TABLE border='1' align='center'>
		<TR>
			<TD><H1>Sajnos megöltek. <A href='startpage.php'>Vissza a kezdőlapra</A></H1></TD>
		</TR>
	</TABLE>
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