<?php

$con = mysql_connect('localhost', 'user1', 'pass1');
if (!$con)
  {
	die('Could not connect: ' . mysql_error());
  }

mysql_select_db("tracker", $con);

if (!mysql_query("DELETE FROM gprmc WHERE date < '".date("Y-m-d H:i:s", mktime(0,0,0,date('m'),date('d')-90,date('Y')))."'", $con))
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
