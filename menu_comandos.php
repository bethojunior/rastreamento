<?php include('seguranca.php');
header("Content-Type: text/html; charset=iso-8859-1");

$imei = $_POST['imei'];
$command = $_POST['command'];
$commandTime = $_POST['commandTime'];
$commandSpeedLimit = $_POST['commandSpeedLimit'];

$cnx = mysql_connect("localhost", "user1", "pass1") or die("Could not connect: " . mysql_error());
mysql_select_db('tracker', $cnx);

$cancelar = (isset($_GET['cancelar'])) ? $_GET['cancelar'] : false ;

$command_path = $_SERVER['DOCUMENT_ROOT']."/sites/1/";

$resultBem = mysql_query("SELECT modelo_rastreador FROM bem WHERE imei = '$imei'");
$dataBem = mysql_fetch_assoc($resultBem);

if(empty($dataBem['modelo_rastreador']) || $dataBem['modelo_rastreador'] == 'tk102' || $dataBem['modelo_rastreador'] == 'tk103'|| $dataBem['modelo_rastreador'] == 'tk104' || $dataBem['modelo_rastreador'] == 'tk303' || $dataBem['modelo_rastreador'] == 'tk106a'){
	if ($command == ',C,30s') $command = $commandTime;
	elseif ($command == ',H,060') $command = ',H,0' . floor($commandSpeedLimit / 1.609);
	
	#echo "IMEI:$imei Command:$command";
	#echo "$_POST['imei']";
	
	if ($imei != "" and $command != ""){
		/****** DESCOMENTAR EM PRODUÇÃO *****/
	// Utilizando arquivos para guardar o comando
	// your path to command files
		$fn = "$command_path/$imei";
		$fh = fopen($fn, 'w') or die ("Can not create file");
		$tempstr = "**,imei:$imei$command"; 
		fwrite($fh, $tempstr);
		fclose($fh);
		
	// Guardando comandos a ser executado no banco
		$tempstr = "**,imei:$imei$command"; 
		
		if ($command == ',N'){
		//Ativando o modo SMS
			if (!mysql_query("UPDATE bem set modo_operacao = 'SMS' where imei = '$imei' and modo_operacao = 'GPRS'", $cnx))
				die('Error: ' . mysql_error());
		}
		
		if ($command == ',J'){
			$ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
			
		//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
			
			if (!mysql_query("UPDATE bem set bloqueado = 'S' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
		}	
		
		if ($command == ',K'){		
			if (!mysql_query("UPDATE bem set bloqueado = 'N' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
		}	
		
		if (!mysql_query("INSERT INTO command (imei, command, userid) VALUES ('$imei', '$tempstr', '$cliente')", $cnx)){
		// Se der erro, atualiza o comando existente
			mysql_query("UPDATE command set command = '$tempstr' WHERE imei = '$imei'", $cnx);
		/*
		// echo "<script language=javascript>alert('Comando enviado com sucesso!'); window.location = 'mapa.php?imei=$imei';</script>";
		//die('Error: ' . mysql_error());
		*/
		echo "OK";
	}
	/*
	// echo "<script language=javascript>alert('Comando enviado com sucesso!'); window.location = 'mapa.php?imei=$imei';</script>";
	*/
	echo "OK";
}
} else if($dataBem['modelo_rastreador'] == 'st200' || $dataBem['modelo_rastreador'] == 'st215' || $dataBem['modelo_rastreador'] == 'st340'){
	if ($imei != "" and $command != "") {
		$tempstr = "";
		
		if ($command == ',J'){
			$ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
			// extrai os 6 digitos do IMEI
      // $imei_part = substr($imei,8,-1);
			$tempstr = "SA200CMD;$imei;02;Enable1"; 
			
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
			
			if (!mysql_query("UPDATE bem set bloqueado = 'S' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
		}
		
		if ($command == ',K'){		
			// extrai os 6 digitos do IMEI
      // $imei_part = substr($imei,8,-1);
			$tempstr = "SA200CMD;$imei;02;Disable1"; 
			
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
			
			if (!mysql_query("UPDATE bem set bloqueado = 'N' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
		}
		
		if ($command == ',B'){		
			$tempstr = "SA200CMD;$imei;02;StatusReq"; 
			
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());				
		}
		
		if ($command == ',H,060'){
			$command = '' . floor($commandSpeedLimit);		
			// extrai os 6 digitos do IMEI
			// $imei_part = substr($imei,8,-1);
			$tempstr = "SA200SVC;$imei;02;1;$command;0;0;0;0;1;1;1;0;0;0;0"; 	
			
			//Guardando log de Velocidade Limite
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());			
		}			
		if ($command == ',C,30s'){		
			$tempstr = "SA200RPT;$imei;02;120;$commandTime;60;3;0;0;0;0;0"; 
			
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
		}		
		if ($command == ',G'){		
			$tempstr = "SA200SVC;$imei;02;1;120;0;0;0;0;1;1;1;0;0;0;0"; 
			
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
		}	
		if ($command == ',E'){		
			$tempstr = "SA200SVC;$imei;02;0;120;0;0;0;0;1;1;1;0;0;0;0"; 
			
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
		}	
		
		if (!mysql_query("INSERT INTO command (imei, command, userid) VALUES ('$imei', '$tempstr', '$userid')", $cnx))
		{
			// Se der erro, atualiza o comando existente
			mysql_query("UPDATE command set command = '$tempstr' WHERE imei = '$imei'", $cnx);
			//echo "<script language=javascript>alert('Comando enviado com sucesso!'); window.location = 'mapa.php?imei=$imei';</script>";
			//die('Error: ' . mysql_error());
		}
		
		$fn = "$command_path/$imei";
		$fh = fopen($fn, 'w') or die ("Can not create file");
		fwrite($fh, $tempstr);
		fclose($fh);
		echo "OK";
		//echo "<script language=javascript>alert('Comando enviado com sucesso!'); window.location = 'mapa.php?imei=$imei';</script>";
	}
} else if($dataBem['modelo_rastreador'] == 'gt06' || $dataBem['modelo_rastreador'] == 'gt06n' || $dataBem['modelo_rastreador'] == 'crx1'){
	if ($imei != "" and $command != "") {
		$tempstr = ""; 
		if ($command == ',J')
		{
			$ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
			
			$tempstr = "DYD#"; 
			
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
			
			if (!mysql_query("UPDATE bem set bloqueado = 'S' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
			
		}
		
		if ($command == ',K')
		{		
			$tempstr = "HFYD#"; 
			
			if (!mysql_query("UPDATE bem set bloqueado = 'N' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
		}	
		
		if (!mysql_query("INSERT INTO command (imei, command, userid) VALUES ('$imei', '$tempstr', '$userid')", $cnx))
		{
			// Se der erro, atualiza o comando existente
			mysql_query("UPDATE command set command = '$tempstr' WHERE imei = '$imei'", $cnx);
			//die('Error: ' . mysql_error());
			echo "OK";
		}
		
		$fn = "$command_path/$imei";
		$fh = fopen($fn, 'w') or die ("Can not create file");
		fwrite($fh, $tempstr);
		fclose($fh);
		
		echo "OK";
	}
} else if($dataBem['modelo_rastreador'] == 'crx1'){
	if ($imei != "" and $command != "") {
		$tempstr = "";

		if ($command == ',J'){
			$ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv("REMOTE_ADDR"));
			$tempstr = chr(0x78).chr(0x78).chr(0x15).chr(0x80).chr(0x0F).chr(0x00).chr(0x01).chr(0xA9).chr(0x58).chr(0x44).chr(0x59).chr(0x44).chr(0x2C).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x23).chr(0x00).chr(0xA0).chr(0xDC).chr(0xF1).chr(0x0D).chr(0x0A);
			//$tempstr = "RELAY,1#"; 
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());	
			if (!mysql_query("UPDATE bem set bloqueado = 'S' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
			echo "OK";
		}
		if ($command == ',K'){		
			$tempstr = chr(0x78).chr(0x78).chr(0x16).chr(0x80).chr(0x10).chr(0x00).chr(0x01).chr(0xA9).chr(0x63).chr(0x48).chr(0x46).chr(0x59).chr(0x44).chr(0x2C).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x30).chr(0x23).chr(0x00).chr(0xA0).chr(0x7B).chr(0xDC).chr(0x0D).chr(0x0A);
			echo "<script language=javascript>alert('Comando Desbloqueio!'); window.location = 'mapa.php?imei=$imei';</script>";		
			//Guardando log de bloqueio
			if (!mysql_query("INSERT INTO command_log (imei, command, cliente, ip) VALUES ('$imei', '$tempstr', '$cliente', '$ip')", $cnx))
				die('Error: ' . mysql_error());
			
			if (!mysql_query("UPDATE bem set bloqueado = 'N' WHERE imei = '$imei' and cliente = $cliente", $cnx))
				die('Error: ' . mysql_error());
			echo "OK";
		}
		if (!mysql_query("INSERT INTO command (imei, command, userid) VALUES ('$imei', '$tempstr', '$userid')", $cnx)){
			// Se der erro, atualiza o comando existente
			mysql_query("UPDATE command set command = '$tempstr' WHERE imei = '$imei'", $cnx);
			//die('Error: ' . mysql_error());
			echo "OK";
		}
		echo "OK";
	}
	echo "OK";
}
mysql_close($cnx);
//Cancelando o comando enviado
if ($cancelar != "") {
	$cnx = mysql_connect("localhost", "user1", "pass1") or die("Could not connect: " . mysql_error());
	mysql_select_db('tracker', $cnx);

	if (!mysql_query("DELETE FROM command WHERE imei = '$cancelar'", $cnx)){
		die('Error: ' . mysql_error());
	}

	mysql_close($cnx);	
}
?>