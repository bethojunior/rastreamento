<?php include('../seguranca.php');

$codCliente=$_GET["codCliente"];

$con = mysql_connect('localhost', 'user1', 'pass1');
if (!$con)
  {
	die('Could not connect: ' . mysql_error());
  }

mysql_select_db("tracker", $con);

$result = "";

if ($codCliente != "")
{
	if (!mysql_query("DELETE from bem WHERE cliente = $codCliente", $con))
	{
		$result = 'Error: ' . mysql_error();
	}
	else
	{
		$result = "OK";
		
		if (!mysql_query("DELETE from cliente WHERE id = $codCliente", $con))
		{
			//$result = 'Error: ' . mysql_error();
		}
		else
		{
			//Excluido com sucesso
			$result = "OK";
		}
	}
}

echo $result;

mysql_close($con);
?>
