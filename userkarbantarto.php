<?php
	include("connection.php");
	function karbantart($id, $kaszt, $tabla)
	{
		szintupdate($id, $tabla);
		$bonustomb = felszbonus($id, $tabla);
		hp($id, $kaszt, $bonustomb["hpbonus"], $tabla);
		shield($id, $kaszt, $bonustomb["shieldbonus"], $tabla);
		mana($id, $kaszt, $bonustomb["manabonus"], $tabla);
		penet($id, $kaszt, $bonustomb["penetbonus"], $tabla);
		dmg($id, $kaszt, $bonustomb["dmgbonus"], $tabla);
	}
	
	function szintupdate($id, $tabla)
	{
		if(!$szintlekeres = mysqli_query($_SESSION["conn"], "SELECT szinthp, szintshield, szintpenet, szintmana, szintdmg, szintpassziv, szintability0, szintability1 FROM $tabla WHERE karakterazonosito='$id'")) die("Szintek lekérése sikertelen");
		$karakterszint = 0;
		foreach($szinttomb = mysqli_fetch_row($szintlekeres) as $szint) $karakterszint = $karakterszint+$szint;
		
		if(!$szintupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET karakterszint='$karakterszint' WHERE karakterazonosito='$id'")) die("Karakterszint frissítése sikertelen");
	}
	
	function felszbonus($id, $tabla)
	{
		if(!$activefelszlekeres = mysqli_query($_SESSION["conn"], "SELECT activefelszereles FROM $tabla WHERE karakterazonosito='$id'")) die("Aktív felszerelés lekérése sikertelen");
		foreach($activefelsztomb = mysqli_fetch_row($activefelszlekeres) as $activefelsz);
		
		if(!$activefelsz) $activefelsz = "alap";
		
		if(!$felszbonuslekeres = mysqli_query($_SESSION["conn"], "SELECT hpbonus, shieldbonus, penetbonus, manabonus, dmgbonus FROM felszereles WHERE felszerelesnev='$activefelsz'")) die("Felszerelésbónuszok lekérése sikertelen");
		foreach($bonusnevektomb = mysqli_fetch_assoc($felszbonuslekeres) as $name=>$ertek)
		{
			$$name = $ertek;
			$felszbonus["$name"] = $ertek;
		}		
		
		if(!$felszbonusfeltoltes = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET hpfelszbonus='$hpbonus', shieldfelszbonus='$shieldbonus', penetfelszbonus='$penetbonus', manafelszbonus='$manabonus', dmgfelszbonus='$dmgbonus' WHERE karakterazonosito='$id'")) die("Felszerelésbónuszok feltöltése siekrtelen");
		return $felszbonus;
	}

	function hp($id, $kaszt, $hpbonus, $tabla)
	{
		if(!$szinthplekeres = mysqli_query($_SESSION["conn"], "SELECT szinthp FROM $tabla WHERE karakterazonosito='$id'")) die("Hpszint lekérése sikertelen");
		foreach($szinthptomb = mysqli_fetch_row($szinthplekeres) as $szinthp)
		
		if(!$hpinclekeres = mysqli_query($_SESSION["conn"], "SELECT hpinc FROM kasztok WHERE kasztnev='$kaszt'")) die("HPinc lekérése sikertelen");
		foreach($hpinctomb = mysqli_fetch_row($hpinclekeres) as $hpinc);

		if(!$basehplekeres = mysqli_query($_SESSION["conn"], "SELECT basehp FROM kasztok WHERE kasztnev='$kaszt'")) die("Alaphp lekérése sikertelen");
		foreach($basehptomb = mysqli_fetch_row($basehplekeres) as $basehp);
		
		$basichp = $basehp+$hpinc*$szinthp;
		
		if(!$boosterlekeres = mysqli_query($_SESSION["conn"], "SELECT hpboosterrounds FROM $tabla WHERE karakterazonosito='$id'")) die("Hpbooster lekérése sikertelen");
		foreach($hpboostertomb = mysqli_fetch_row($boosterlekeres) as $hpboosterrounds);
		if($hpboosterrounds) $booster = 125;
		else $booster = 100;
		
		$maxhp = $basichp*($hpbonus/100)*($booster/100);
		
		if(!$hpupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET basichp='$basichp', maxhp='$maxhp' WHERE karakterazonosito='$id'")) die("hp frissítése sikertelen");
		
		if(!$actualhpleker = mysqli_query($_SESSION["conn"], "SELECT actualhp FROM $tabla WHERE karakterazonosito='$id'")) die("Aktuális hp lekérése sikertelen");
		foreach($actualhptomb = mysqli_fetch_row($actualhpleker) as $actualhp);
		
		if($actualhp > $maxhp) if(!$actualhpfrissit = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET actualhp='$maxhp' WHERE karakterazonosito='$id'")) die("actualhp frissítése sikertelen");
	}
	
	function shield($id, $kaszt, $shieldbonus, $tabla)
	{
		if(!$szintshieldlekeres = mysqli_query($_SESSION["conn"], "SELECT szintshield FROM $tabla WHERE karakterazonosito='$id'")) die("shieldszint lekérése sikertelen");
		foreach($szintshieldtomb = mysqli_fetch_row($szintshieldlekeres) as $szintshield)
		
		if(!$shieldinclekeres = mysqli_query($_SESSION["conn"], "SELECT shieldinc FROM kasztok WHERE kasztnev='$kaszt'")) die("shieldinc lekérése sikertelen");
		foreach($shieldinctomb = mysqli_fetch_row($shieldinclekeres) as $shieldinc);

		if(!$baseshieldlekeres = mysqli_query($_SESSION["conn"], "SELECT baseshield FROM kasztok WHERE kasztnev='$kaszt'")) die("Alapshield lekérése sikertelen");
		foreach($baseshieldtomb = mysqli_fetch_row($baseshieldlekeres) as $baseshield);
		
		$basicshield = $baseshield+$shieldinc*$szintshield;
		
		if(!$boosterlekeres = mysqli_query($_SESSION["conn"], "SELECT shieldboosterrounds FROM $tabla WHERE karakterazonosito='$id'")) die("shieldbooster lekérése sikertelen");
		foreach($shieldboostertomb = mysqli_fetch_row($boosterlekeres) as $shieldboosterrounds);
		if($shieldboosterrounds) $booster = 125;
		else $booster = 100;
		
		$maxshield = $basicshield*($shieldbonus/100)*($booster/100);
		
		if(!$shieldupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET basicshield='$basicshield', maxshield='$maxshield' WHERE karakterazonosito='$id'")) die("shield frissítése sikertelen");
		
		if(!$actualshieldleker = mysqli_query($_SESSION["conn"], "SELECT actualshield FROM $tabla WHERE karakterazonosito='$id'")) die("Aktuális shield lekérése sikertelen");
		foreach($actualshieldtomb = mysqli_fetch_row($actualshieldleker) as $actualshield);
		
		if($actualshield > $maxshield) if(!$actualshieldfrissit = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET actualshield='$maxshield' WHERE karakterazonosito='$id'")) die("actualshield frissítése sikertelen");
	}
	
	function mana($id, $kaszt, $manabonus, $tabla)
	{
		if(!$szintmanalekeres = mysqli_query($_SESSION["conn"], "SELECT szintmana FROM $tabla WHERE karakterazonosito='$id'")) die("manaszint lekérése sikertelen");
		foreach($szintmanatomb = mysqli_fetch_row($szintmanalekeres) as $szintmana)
		
		if(!$manainclekeres = mysqli_query($_SESSION["conn"], "SELECT manainc FROM kasztok WHERE kasztnev='$kaszt'")) die("manainc lekérése sikertelen");
		foreach($manainctomb = mysqli_fetch_row($manainclekeres) as $manainc);

		if(!$basemanalekeres = mysqli_query($_SESSION["conn"], "SELECT basemana FROM kasztok WHERE kasztnev='$kaszt'")) die("Alapmana lekérése sikertelen");
		foreach($basemanatomb = mysqli_fetch_row($basemanalekeres) as $basemana);
		
		$basicmana = $basemana+$manainc*$szintmana;
		
		if(!$boosterlekeres = mysqli_query($_SESSION["conn"], "SELECT manaboosterrounds FROM $tabla WHERE karakterazonosito='$id'")) die("manabooster lekérése sikertelen");
		foreach($manaboostertomb = mysqli_fetch_row($boosterlekeres) as $manaboosterrounds);
		if($manaboosterrounds) $booster = 125;
		else $booster = 100;
		
		$maxmana = $basicmana*($manabonus/100)*$booster/100;
		settype($maxmana, "integer");
		if(!$manaupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET basicmana='$basicmana', maxmana='$maxmana' WHERE karakterazonosito='$id'")) die("mana frissítése sikertelen");
		
		if(!$actualmanaleker = mysqli_query($_SESSION["conn"], "SELECT actualmana FROM $tabla WHERE karakterazonosito='$id'")) die("Aktuális mana lekérése sikertelen");
		foreach($actualmanatomb = mysqli_fetch_row($actualmanaleker) as $actualmana);
		
		if($actualmana > $maxmana) if(!$actualmanafrissit = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET actualmana='$maxmana' WHERE karakterazonosito='$id'")) die("actualmana frissítése sikertelen");
	}
	
	function penet($id, $kaszt, $penetbonus, $tabla)
	{
		if(!$szintpenetlekeres = mysqli_query($_SESSION["conn"], "SELECT szintpenet FROM $tabla WHERE karakterazonosito='$id'")) die("Penetrációszint lekérése sikertelen");
		foreach($szintpenettomb = mysqli_fetch_row($szintpenetlekeres) as $szintpenet);
		
		if(!$basepenetlekeres = mysqli_query($_SESSION["conn"], "SELECT basepenet FROM kasztok WHERE kasztnev='$kaszt'")) die("Alap penetráció lekérése sikertelen");
		foreach($basepenettomb = mysqli_fetch_row($basepenetlekeres) as $basepenet);
		
		if(!$penetinclekeres = mysqli_query($_SESSION["conn"], "SELECT penetinc FROM kasztok WHERE kasztnev='$kaszt'")) die("enetrációnövekedés lekérése sikertelen");
		foreach($penetinctomb = mysqli_fetch_row($penetinclekeres) as $penetinc);
		
		if($penetbonus < 0) $penetbonus = 0;
		$ertekpenet = $basepenet+$szintpenet*$penetinc;
		
		if(!$penetupdate = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET ertekpenet='$ertekpenet' WHERE karakterazonosito='$id'")) die("Pennetráció frissítése sikertelen");
	}
	
	function dmg($id, $kaszt, $dmgbonus, $tabla)
	{
		if(!$szintdmglekeres = mysqli_query($_SESSION["conn"], "SELECT szintdmg FROM $tabla WHERE karakterazonosito='$id'")) die("dmgszint lekérése sikertelen");
		foreach($szintdmgtomb = mysqli_fetch_row($szintdmglekeres) as $szintdmg)
		
		if(!$dmginclekeres = mysqli_query($_SESSION["conn"], "SELECT dmginc FROM kasztok WHERE kasztnev='$kaszt'")) die("dmginc lekérése sikertelen");
		foreach($dmginctomb = mysqli_fetch_row($dmginclekeres) as $dmginc);

		if(!$basedmglekeres = mysqli_query($_SESSION["conn"], "SELECT basedmg FROM kasztok WHERE kasztnev='$kaszt'")) die("Alapdmg lekérése sikertelen");
		foreach($basedmgtomb = mysqli_fetch_row($basedmglekeres) as $basedmg);
		
		$basicdmg = $basedmg+$szintdmg*$dmginc;
		
		if(!$boosterlekeres = mysqli_query($_SESSION["conn"], "SELECT dmgboosterrounds FROM $tabla WHERE karakterazonosito='$id'")) die("dmgbooster lekérése sikertelen");
		foreach($dmgboostertomb = mysqli_fetch_row($boosterlekeres) as $dmgboosterrounds);
		if($dmgboosterrounds) $booster = 125;
		else $booster = 100;
		
		$actualdmg = $basicdmg*($dmgbonus/100)*($booster/100);
		
		if(!$updatedmg = mysqli_query($_SESSION["conn"], "UPDATE $tabla SET basicdmg='$basicdmg', actualdmg='$actualdmg' WHERE karakterazonosito='$id'")) die("Sebzés frissítése sikertelen");
	}
?>