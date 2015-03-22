<?php
$host = "us-cdbr-azure-east2-d.cloudapp.net";
$user = "b69330f3c803ee";
$pass = "bf5a0354";
$nombrebas = "phphubmx";

$nombre = $_POST["nombre"];
$edad = $_POST["edad"];

//echo "datos: $nombre -- $edad";

$objMysql = new MySqli($host, $user, $pass, $nombrebas);
if($objMysql->connect_errno)
{
	echo "error al conectarse";
	echo "<br>".$objMysql->connect_errno."---";
}
else
{
	//echo "si se conecto";
	$sql = 
	"insert into tablapersona 
	(id, nombre, edad)
	values(?,?,?)";
	$id = '';
	$sentencia = $objMysql->prepare($sql);
	$a = $sentencia->bind_param('isi',$id,$nombre,$edad);
	if(!$a)
	{
		echo "esto no funciono";
	}
	else
	{
		echo "SI funciono";
		$b = $sentencia->execute();
		$sentencia->close();
	}



}

























?>