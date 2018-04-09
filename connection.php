<?php
	if(!isset($_SESSION["conn"]))
	{
		session_start();
		$_SESSION["conn"] = mysqli_connect("localhost", "root", "", "skycastle");
	}
?>