<?php
if(isset($_GET["salir"]) && ($_GET["salir"]  == 'si'))
		{
			include_once("facebook.php");
			$_SESSION["objetoSistema"]->salir();
		}
else
		{
			include_once("facebook.php");
			$_SESSION["objetoSistema"]->iniciarSistema();
		}

?>