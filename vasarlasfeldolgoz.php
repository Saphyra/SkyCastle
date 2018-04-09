<?php
	include("interfacetarolo.php");
	include("userkarbantarto.php");
	$id = $_SESSION["karakterazonosito"];
	if(isset($_POST["submit"]))
	{
		foreach($_POST as $nev=>$ertek)
		{
			if($nev != "submit")
			{
				$item = $nev;
				$amount = $ertek;
				print "$item: $ertek<BR>";
			}
		}
		if($vasarlas = vasarlasfeldolgoz($id, $item, $amount, $_SESSION["kaszt"])) 
		{
			karbantart($id, $_SESSION["kaszt"], "users");
			$_SESSION["vasarlas"] = "A vásárlás sikeres!";
			header("location:shop.php");
		}
	}
	
?>