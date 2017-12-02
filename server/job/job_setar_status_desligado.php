<?php

$con = mysql_connect('localhost', 'user1', 'pass1');
if (!$con)
  {
	die('Could not connect: ' . mysql_error());
  }

mysql_select_db("tracker", $con);

if (!mysql_query("UPDATE bem set date=date, activated=activated, modo_operacao=modo_operacao, liberado=liberado, status_sinal = 'D'", $con))
{
	die('Error: ' . mysql_error());
}
else
{
	//Executado com sucesso
	echo "OK";
}

mysql_close($con);
?>
