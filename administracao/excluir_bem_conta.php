<?php include('../seguranca.php');

$codCliente=$_GET["codCliente"];
$imei=$_GET["imei"];
$idBem=$_GET["idBem"];

$con = mysql_connect('localhost', 'user1', 'pass1');
if (!$con)
  {
	die('Could not connect: ' . mysql_error());
  }

mysql_select_db("tracker", $con);

$result = "OK";

if ($codCliente != "" and $imei != "" and $idBem != "")
{
	if (!mysql_query("DELETE from bem WHERE cliente = $codCliente and imei = $imei and id = $idBem", $con))
	{
		$result = 'Error: ' . mysql_error();
	}
	else
	{
		$result = "OK";
	}
}

echo $result;

mysql_close($con);
?>
