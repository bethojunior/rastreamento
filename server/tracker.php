#!/usr/bin/php -q
<?php
//waiting for system startup
//crontab: @reboot php -q /var/www/server/tracker.php
//sleep (180);

/**
  * Listens for requests and forks on each connection
  */

$tipoLog = "arquivo"; // tela //debug log, escreve na tela ou no arquivo de log.

$fh = null;
$remip = null;
$remport = null;

function abrirArquivoLog($imeiLog) {
	GLOBAL $fh;
	
	//$fn = ".".dirname(__FILE__)."/sites/1/logs/Log_". trim($imeiLog) .".log";
	$fn = "./home/sistema/sites/1/logs/Log_". trim($imeiLog) .".log";
	$fn = trim($fn);
	$fh = fopen($fn, 'a') or die ("Can not create file");
	$tempstr = "Log Inicio".chr(13).chr(10); 
	fwrite($fh, $tempstr);	
}

function fecharArquivoLog() {
	GLOBAL $fh;
	if ($fh != null)
		fclose($fh);
}

function printLog( $fh, $mensagem ) {
	GLOBAL $tipoLog;
	GLOBAL $fh;
	
    if ($tipoLog == "arquivo") {
		//escreve no arquivo
		if ($fh != null)
			fwrite($fh, $mensagem.chr(13).chr(10));
    } else {
		//escreve na tela
		echo $mensagem."<br />";
    }
}

// IP Local
$ip = '191.101.226.135';
// Port
$port = 7095;
// Path to look for files with commands to send
$command_path = "./home/sistema/sites/1/";
$from_email = 'modal_dados';

$__server_listening = true;

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
declare(ticks = 1);
ini_set('sendmail_from', $from_email);

//printLog($fh, "become_daemon() in");
become_daemon();
//printLog($fh, "become_daemon() out");

/* nobody/nogroup, change to your host's uid/gid of the non-priv user 

** Comment by Andrew - I could not get this to work, i commented it out
   the code still works fine but mine does not run as a priv user anyway....
   uncommented for completeness
*/
//change_identity(65534, 65534);

/* handle signals */
pcntl_signal(SIGTERM, 'sig_handler');
pcntl_signal(SIGINT, 'sig_handler');
pcntl_signal(SIGCHLD, 'sig_handler');

//printLog($fh, "pcntl_signal ok");

/* change this to your own host / port */
//printLog($fh, "server_loop in");
server_loop($ip, $port);

//Finalizando arquivo
//fclose($fh);

/**
  * Change the identity to a non-priv user
  */
function change_identity( $uid, $gid ) {
    if( !posix_setgid( $gid ) ) {
        print "Unable to setgid to " . $gid . "!\n";
        exit;
    }

    if( !posix_setuid( $uid ) ) {
        print "Unable to setuid to " . $uid . "!\n";
        exit;
    }
}

/**
  * Creates a server socket and listens for incoming client connections
  * @param string $address The address to listen on
  * @param int $port The port to listen on
  */
function server_loop($address, $port) {
    GLOBAL $fh;
    GLOBAL $__server_listening;
	
	printLog($fh, "server_looping...");

    if(($sock = socket_create(AF_INET, SOCK_STREAM, 0)) < 0) {
		printLog($fh, "failed to create socket: ".socket_strerror($sock));
        exit();
    }

	if(($ret = socket_bind($sock, $address, $port)) < 0) {
		printLog($fh, "failed to bind socket: ".socket_strerror($ret));
		exit();
	}

	if( ( $ret = socket_listen( $sock, 0 ) ) < 0 ) {
		printLog($fh, "failed to listen to socket: ".socket_strerror($ret));
		exit();
	}

	socket_set_nonblock($sock);

	printLog($fh, "waiting for clients to connect...");

	while ($__server_listening) {
		$connection = @socket_accept($sock);
		if ($connection === false) {
			usleep(100);
		} elseif ($connection > 0) {
			handle_client($sock, $connection);
		} else {
			//printLog($fh, "error: ".socket_strerror($connection));
			die;
		}
	}
}

/**
* Signal handler
*/
function sig_handler($sig) {
	switch($sig) {
		case SIGTERM:
		case SIGINT:
			//exit();
			break;

		case SIGCHLD:
			pcntl_waitpid(-1, $status);
		break;
	}
}

$firstInteraction = false;

/**
* Handle a new client connection
*/
function handle_client($ssock, $csock) {
	GLOBAL $__server_listening;
	GLOBAL $fh;
	GLOBAL $firstInteraction;
	GLOBAL $remip;
	GLOBAL $remport;

	$pid = pcntl_fork();

	if ($pid == -1) {
		/* fork failed */
		//printLog($fh, "fork failure!");
		die;
	} elseif ($pid == 0) {
		/* child process */
		$__server_listening = false;
		socket_getpeername($csock, $remip, $remport);
		
		//printLog($fh, date("d-m-y h:i:sa") . " Connection from $remip:$remport");

		$firstInteraction = true;
		
		socket_close($ssock);
		interact($csock);
		socket_close($csock);
		
		printLog($fh, date("d-m-y h:i:sa") . " Connection to $remip:$remport closed");
		
		fecharArquivoLog();
		
	} else {
		socket_close($csock);
	}
}

function interact($socket) {
	GLOBAL $fh;
	GLOBAL $command_path;
	GLOBAL $firstInteraction;
	GLOBAL $remip;
	GLOBAL $remport;	

	$loopcount = 0;
	$conn_imei = "";
	/* TALK TO YOUR CLIENT */
	$rec = "";
	// Variavel que indica se comando está em banco ou arquivo.
	$tipoComando = "banco"; //"arquivo";
	
	//Checando o protocolo
	$isGIMEI = false;
	$isGPRMC = false;
	
	$send_cmd = "";

	# Read the socket but don't wait for data..
	while (@socket_recv($socket, $rec, 2048, 0x40) !== 0) {
	  
	  # If we know the imei of the phone and there is a pending command send it.
	    if ($conn_imei != "") {
			if ($tipoComando == "arquivo" and file_exists("$command_path/$conn_imei")) {
				$send_cmd = file_get_contents("$command_path/$conn_imei");
				socket_send($socket, $send_cmd, strlen($send_cmd), 0);
				unlink("$command_path/$conn_imei");
				printLog($fh, "Arquivo de comandos apagado: " . $send_cmd . " imei: " . $conn_imei);
			} else {
				if ($tipoComando == "banco" and file_exists("$command_path/$conn_imei")) {
					//Conecta e pega o comando pendente
					$cnx = mysql_connect("localhost", "user1", "pass1") 
					  or die("Could not connect: " . mysql_error());
					mysql_select_db('tracker', $cnx);
					$res = mysql_query("SELECT c.command FROM command c WHERE c.imei = '$conn_imei' ORDER BY date DESC LIMIT 1");
					while($data = mysql_fetch_assoc($res)) {
						$send_cmd = $data['command'];
					}
					// Deletando comando
					//mysql_query("DELETE FROM command WHERE imei = $conn_imei");
					mysql_close($cnx);
					
					socket_send($socket, $send_cmd, strlen($send_cmd), 0);
					unlink("$command_path/$conn_imei");
					
					printLog($fh, "Comandos do arquivo apagado: " . $send_cmd . " imei: " . $conn_imei);
				} else {
					//Se nao tiver comando na fila e for a primeira iteracao, obtem o ultimo comando válido enviado
					if ($firstInteraction == true) {
						sleep (1);
						$send_cmd = "**,imei:". $conn_imei .",C,02m";
						
						//Obtendo o ultimo comando
						$cnx = mysql_connect("localhost", "user1", "pass1") 
						  or die("Could not connect: " . mysql_error());
						mysql_select_db('tracker', $cnx);
						$res = mysql_query("SELECT c.command FROM command c WHERE c.command like '**,imei:". $conn_imei .",C,%' and c.imei = $conn_imei ORDER BY date DESC LIMIT 1");
						while($data = mysql_fetch_assoc($res)) {
							$send_cmd = $data['command'];
						}
						mysql_close($cnx);
						
						socket_send($socket, $send_cmd, strlen($send_cmd), 0);
						printLog($fh, "Comando de start: " . $send_cmd . " imei: " . $conn_imei);
						$firstInteraction = false;
					}
				}
			}

			// Comando enviado
			printLog($fh, date("d-m-y h:i:sa") . " Sent: $send_cmd");
		}

		# Some pacing to ensure we don't split any incoming data.
		sleep (1);

		# Timeout the socket if it's not talking...
		# Prevents duplicate connections, confusing the send commands
		$loopcount++;
		if ($loopcount > 120) return;

		#remove any whitespace from ends of string.
		$rec = trim($rec);
		
		if ($rec != "") {
			if (strpos($rec, "GPRMC") === false) {
			
				/* MÓDULO IMEI GENÉRICO */
				
				$isGIMEI = true;
			
				$loopcount = 0;
			  
				if ($fh != null)
					printLog($fh, date("d-m-y h:i:sa") . " Got : $rec");
			    
				$parts = explode(',',$rec);
				if (strpos($parts[0], "#") === FALSE) {
					$cnx = mysql_connect("localhost", "user1", "pass1");
					/* Andrew's tracker is different....
					Array
					(       
						[0] => imei:354779030525274
						[1] => tracker
						[2] => 0909221022
						[3] => +61417801658
						[4] => F
						[5] => 022234.000
						[6] => A
						[7] => 3506.5232
						[8] => S
						[9] => 13829.5988
						[10] => E
						[11] => 0.00
						[12] => 
					)
					*/
					# $imei                       = substr($parts[0],0, -1);
					# Only worry about the rest if there is data to get
					if (count($parts) > 1) {
					  $imei			  			  = substr($parts[0],5);
					  $infotext			  		  = mysql_real_escape_string($parts[1]);
					  $trackerdate                = mysql_real_escape_string($parts[2]);
					  $gpsSignalIndicator         = mysql_real_escape_string($parts[4]);
					  
					  //Se gpsSignalIndicator <> L, pega o outros dados
					  if ($gpsSignalIndicator != 'L') {
						  $phone                      = mysql_real_escape_string($parts[3]);
						  $satelliteFixStatus         = mysql_real_escape_string($parts[6]);					  
						  $latitudeDecimalDegrees     = mysql_real_escape_string($parts[7]);
						  $latitudeHemisphere         = mysql_real_escape_string($parts[8]);
						  $longitudeDecimalDegrees    = mysql_real_escape_string($parts[9]);
						  $longitudeHemisphere        = mysql_real_escape_string($parts[10]);
						  $speed                      = mysql_real_escape_string($parts[11]);
					  }
					  
					  //inicio verifica geofence
					  mysql_select_db('tracker', $cnx);
					  
						if ( $imei != "" ) {
					  
						$consulta = mysql_query("SELECT * FROM geo_fence WHERE imei = '$imei'", $cnx);
							while($data = mysql_fetch_assoc($consulta)) {

								$idCerca = $data['id'];
								$imeiCerca = $data['imei'];
								$nomeCerca = $data['nome'];
								$coordenadasCerca = $data['coordenadas'];
								$resultCerca = $data['tipo'];
								$tipoEnvio = $data['tipoEnvio'];
								
								strlen($latitudeDecimalDegrees) == 9 && $latitudeDecimalDegrees = '0'.$latitudeDecimalDegrees;
								$g = substr($latitudeDecimalDegrees,0,3);
								$d = substr($latitudeDecimalDegrees,3);
								$strLatitudeDecimalDegrees = $g + ($d/60);
								$latitudeHemisphere == "S" && $strLatitudeDecimalDegrees = $strLatitudeDecimalDegrees * -1;

								strlen($longitudeDecimalDegrees) == 9 && $longitudeDecimalDegrees = '0'.$longitudeDecimalDegrees;
								$g = substr($longitudeDecimalDegrees,0,3);
								$d = substr($longitudeDecimalDegrees,3);
								$strLongitudeDecimalDegrees = $g + ($d/60);
								$longitudeHemisphere == "S" && $strLongitudeDecimalDegrees = $strLongitudeDecimalDegrees * -1;

								$strLongitudeDecimalDegrees = $strLongitudeDecimalDegrees * -1;

								$lat_point = $strLatitudeDecimalDegrees;
								$lng_point = $strLongitudeDecimalDegrees;

								$exp = explode("|", $coordenadasCerca);

								if( ( count($exp) ) < 5 ) {
									$strExp = explode(",", $exp[0]);
									$strExp1 = explode(",", $exp[2]);
								} else {
									$int = (count($exp)) / 2;
									$strExp = explode(",", $exp[0]);
									$strExp1 = explode(",", $exp[$int]);
								}

								$lat_vertice_1 = $strExp[0];
								$lng_vertice_1 = $strExp[1];
								$lat_vertice_2 = $strExp1[0];
								$lng_vertice_2 = $strExp1[1];

								if ( $lat_vertice_1 < $lat_point Or $lat_point < $lat_vertice_2 And $lng_point < $lng_vertice_1 Or $lng_vertice_2 < $lng_point ) {
									$result = '0';
									$situacao = 'fora';
								} else {
									$result = '1';
									$situacao = 'dentro';
								}

								if ( $result <> $resultCerca And round($speed * 1.852,0) > 0 ) {
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Cerca ". $nomeCerca ." Violada!')", $cnx);

									if ( $tipoEnvio == 0 ) {

										# Convert the GPS coordinates to a human readable address
										$tempstr = "http://maps.google.com/maps/geo?q=$lat_point,$lng_point&oe=utf-8&sensor=true&key=ABQIAAAAFd56B-wCWVpooPPO7LR3ihTz-K-sFZ2BISbybur6B4OYOOGbdRShvXwdlYvbnwC38zgCx2up86CqEg&output=csv"; //output = csv, xml, kml, json
										$rev_geo_str = file_get_contents($tempstr);
										$rev_geo_str = preg_replace("/\"/","", $rev_geo_str);
										$rev_geo = explode(',', $rev_geo_str);
										$logradouro = $rev_geo[2] .",". $rev_geo[3] ;

										require "lib/class.phpmailer.php";

										$consulta1 = mysql_query("SELECT a.*, b.* FROM cliente a INNER JOIN bem b ON (a.id = b.cliente) WHERE b.imei = '$imei'", $cnx);
										while($data = mysql_fetch_assoc($consulta1)) {

											$emailDestino = $data['email'];
											$nameBem = $data['name'];
											$mensagem = "O veiculo ". $nameBem .", esta ". $situacao ." do perimetro ". $nomeCerca .", as ". date("H:i:s") ." do dia ". date("d/m/Y") .", no local ". $logradouro ." e trafegando a ". round($speed * 1.852, 0) ." km/h.";
													
											$msg = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">";
											$msg .= "<html>";
											$msg .= "<head></head>";
											$msg .= "<body style=\"background-color:#fff;\" >";
											$msg .= "<p><strong>Alerta de Violacao de Perimetro:</strong><br /><br />";
											$msg .= $mensagem ."<br /><br />";
											$msg .= "Equipe System Tracker<br />";
											$msg .= "(85)88462069<br />";
											$msg .= "<a href=\"http://www.systemtracker.com.br\">www.systemtracker.com.br</a></p>";
											$msg .= "</body>";
											$msg .= "</html>";

											$mail = new PHPMailer();
											$mail->Mailer = "smtp";
											$mail->IsHTML(true);
											$mail->CharSet = "utf-8";
											$mail->SMTPSecure = "tls";
											$mail->Host = "smtp.gmail.com";
											$mail->Port = "587";
											$mail->SMTPAuth = "true";
											$mail->Username = "paccelli.rocha";
											$mail->Password = "sua_senha";
											$mail->From = "paccelli.rocha@gmail.com";
											$mail->FromName = "System Tracker";
											$mail->AddAddress($emailDestino);
											$mail->AddReplyTo($mail->From, $mail->FromName);
											$mail->Subject = "System Tracker - Alerta de Violacao de Perimetro";
											$mail->Body = $msg;

											if (!$mail->Send()) {
												echo "Erro de envio: ". $mail->ErrorInfo;
											} else {
												echo "Mensagem enviada com sucesso!";
											}
										}
									}
								}
							}
						} // if imei
					//final verifica geofence
						
					  # Write it to the database...
					  if ($gpsSignalIndicator != 'L') {
						mysql_query("UPDATE bem set date = date, status_sinal = 'R' WHERE imei = '$imei'", $cnx);
						mysql_query("INSERT INTO gprmc (date, imei, phone, satelliteFixStatus, latitudeDecimalDegrees, latitudeHemisphere, longitudeDecimalDegrees, longitudeHemisphere, speed, infotext, gpsSignalIndicator) VALUES (now(), '$imei', '$phone', '$satelliteFixStatus', '$latitudeDecimalDegrees', '$latitudeHemisphere', '$longitudeDecimalDegrees', '$longitudeHemisphere', '$speed', '$infotext', '$gpsSignalIndicator')", $cnx);
					  } else {
					    mysql_query("UPDATE bem set date = date, status_sinal = 'S' WHERE imei = '$imei'", $cnx);
					  }
					  
					 # Now check to see if we need to send any alerts.
						if ($infotext != "tracker") {
						   $res = mysql_query("SELECT * FROM bem WHERE imei='$imei'", $cnx);
						   while($data = mysql_fetch_assoc($res)) {
							  switch ($infotext) {
								  case "dt":
									$body = "Disable Track OK";
									break;
								  case "et":
									$body = "Stop Alarm OK";
									break;
								  case "gt";
									$body = "Move Alarm set OK";
									break;
								  case "help me":
									$body = "Help!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$$conn_imei', 'SOS!')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (help me): " . $send_cmd . " imei: " . $conn_imei);									
									break;
								  case "ht":
									$body = "Speed alarm set OK";
									break;
								  case "it":
									$body = "Timezone set OK";
									break;
								  case "low battery":
									$body = "Low battery!\nYou have about 2 minutes...";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Bat. Fraca')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (low battery): " . $send_cmd . " imei: " . $conn_imei);
									break;
								  case "move":
									$body = "Move Alarm!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Movimento')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (move): " . $send_cmd . " imei: " . $conn_imei);									
									break;
								  case "nt":
									$body = "Returned to SMS mode OK";
									break;
								  case "speed":
									$body = "Speed alarm!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Velocidade')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (speed): " . $send_cmd . " imei: " . $conn_imei);
									break;
								  case "stockade":
									$body = "Geofence Violation!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Cerca')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (stockade): " . $send_cmd . " imei: " . $conn_imei);
									break;
								  case "door alarm":
									$body = "Open door!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Porta')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (door alarm): " . $send_cmd . " imei: " . $conn_imei);
									break;
								  case "acc alarm":
									$body = "ACC alarm!";
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Ignição')", $cnx);
									//Envia comando de resposta: alerta recebido
									$send_cmd = "**,imei:". $conn_imei .",E";
									socket_send($socket, $send_cmd, strlen($send_cmd), 0);
									//printLog($fh, "Comando de resposta (acc alarm): " . $send_cmd . " imei: " . $conn_imei);
									break;
								} //switch

								//Enviando e-mail de alerta
								$headers = "From: $email_from" . "\r\n" . "Reply-To: $email_from" . "\r\n";
								$responsible = $data['responsible'];
								$rv = mail($responsible, "Tracker - $imei", $body, $headers);

							} //while
						}
					  mysql_close($cnx);
					} else {
					 /* If we got here, we got an imei ONLY - not even 'imei:' first
						This seems to be some sort of 'keepalive' packet
						The TK-201 is not stateless like the TK-102, it
						needs to retain a session.  Basically, we just reply with 'ON'
						anything else seems to cause the device to reset the connection.
					 */
						@socket_send($socket, "ON", 2, 0);
						
						printLog($fh, date("d-m-y h:i:sa") . " Sent: ON");
					}
				} else {
				  /*Here is where we land on the first iteration of the loop
					on a new connection. We get from the gps: ##,imei:<IMEI>,A;
					It seems the correct reply is 'LOAD' so that's what we send.
				  */
				  $init = $parts[0];
				  $conn_imei = substr($parts[1],5);
				  $cmd = $parts[2];
				  if ($cmd = "A") {
					@socket_send($socket, "LOAD", 4, 0);
					
					// Abrindo arquivo de log do imei
					abrirArquivoLog($conn_imei);
					printLog($fh, date("d-m-y h:i:sa") . " Connection from $remip:$remport");
					printLog($fh, date("d-m-y h:i:sa") . " Got : $rec");
					printLog($fh, date("d-m-y h:i:sa") . " Sent: LOAD");
				  }
				}
			
			} else {
				/* MÓDULO GPRMC */
				if (strpos($rec, "GPRMC") === true)
					$isGPRMC = true;
				
				$loopcount = 0;
			  
				//printLog($fh, date("d-m-y h:i:sa") . " Got : $rec");
				$parts = explode(',',$rec);
				
				$cnx = mysql_connect("localhost", "user1", "pass1");
				/*
				Array
				(
					[0] => 0908242216
					[1] => 0033663282263
					[2] => GPRMC
					[3] => 212442.000
					[4] => A
					[5] => 4849.0475
					[6] => N
					[7] => 00219.4763
					[8] => E
					[9] => 2.29
					[10] =>
					[11] => 220809
					[12] =>
					[13] =>
					[14] => A*70
					[15] => L
					[16] => imei:359587017313647
					[17] => 101Q
					[18] =>

				)
				*/
				if (count($parts) > 1) {
					$trackerdate 		     = mysql_real_escape_string($parts[0]);
					$phone 		    		 = mysql_real_escape_string($parts[1]);
					$gprmc 		    		 = mysql_real_escape_string($parts[2]);
					$satelliteDerivedTime    = mysql_real_escape_string($parts[3]);
					$satelliteFixStatus 	 = mysql_real_escape_string($parts[4]);
					$latitudeDecimalDegrees  = mysql_real_escape_string($parts[5]);
					$latitudeHemisphere 	 = mysql_real_escape_string($parts[6]);
					$longitudeDecimalDegrees = mysql_real_escape_string($parts[7]);
					$longitudeHemisphere 	 = mysql_real_escape_string($parts[8]);
					$speed 		    	  	 = mysql_real_escape_string($parts[9]);
					$bearing 		     	 = mysql_real_escape_string($parts[10]);
					$utcDate 		     	 = mysql_real_escape_string($parts[11]);
					// = $parts[12];
					// = $parts[13];
					$checksum 		    	= mysql_real_escape_string($parts[14]);
					$gpsSignalIndicator 	= mysql_real_escape_string($parts[15]);
					
					if(preg_match("/imei/", $parts[16])) {
						$infotext   		= "gprmc"; //Nenhum comando enviado pelo gps
						$imei 				= mysql_real_escape_string($parts[16]);
						$other 				= mysql_real_escape_string($parts[17]);
					} else {
						$infotext			= mysql_real_escape_string($parts[16]);
						$imei 				= mysql_real_escape_string($parts[17]);
						$other 				= mysql_real_escape_string($parts[18].' '.$parts[19]);
					}
					
					if ($infotext == "") {
						$infotext = "gprmc";
					}
					
					if (preg_match("/:/", substr($imei,5))) {
						$imei = substr($imei,6);
					} else {
						$imei = substr($imei,5);
					}
					
					$conn_imei = $imei;
					
					abrirArquivoLog($conn_imei);
					printLog($fh, date("d-m-y h:i:sa") . " Connection from $remip:$remport");
					printLog($fh, date("d-m-y h:i:sa") . " Got : $rec");
					
					//inicio verifica geofence
					mysql_select_db('tracker', $cnx);
				
						if ( $imei != "" ) {
					  
						$consulta = mysql_query("SELECT * FROM geo_fence WHERE imei = '$imei'", $cnx);
						while($data = mysql_fetch_assoc($consulta)) {

								$idCerca = $data['id'];
								$imeiCerca = $data['imei'];
								$nomeCerca = $data['nome'];
								$coordenadasCerca = $data['coordenadas'];
								$resultCerca = $data['tipo'];
								$tipoEnvio = $data['tipoEnvio'];
								
								strlen($latitudeDecimalDegrees) == 9 && $latitudeDecimalDegrees = '0'.$latitudeDecimalDegrees;
								$g = substr($latitudeDecimalDegrees,0,3);
								$d = substr($latitudeDecimalDegrees,3);
								$strLatitudeDecimalDegrees = $g + ($d/60);
								$latitudeHemisphere == "S" && $strLatitudeDecimalDegrees = $strLatitudeDecimalDegrees * -1;

								strlen($longitudeDecimalDegrees) == 9 && $longitudeDecimalDegrees = '0'.$longitudeDecimalDegrees;
								$g = substr($longitudeDecimalDegrees,0,3);
								$d = substr($longitudeDecimalDegrees,3);
								$strLongitudeDecimalDegrees = $g + ($d/60);
								$longitudeHemisphere == "S" && $strLongitudeDecimalDegrees = $strLongitudeDecimalDegrees * -1;

								$strLongitudeDecimalDegrees = $strLongitudeDecimalDegrees * -1;

								$lat_point = $strLatitudeDecimalDegrees;
								$lng_point = $strLongitudeDecimalDegrees;

								$exp = explode("|", $coordenadasCerca);

								if( ( count($exp) ) < 5 ) {
									$strExp = explode(",", $exp[0]);
									$strExp1 = explode(",", $exp[2]);
								} else {
									$int = (count($exp)) / 2;
									$strExp = explode(",", $exp[0]);
									$strExp1 = explode(",", $exp[$int]);
								}

								$lat_vertice_1 = $strExp[0];
								$lng_vertice_1 = $strExp[1];
								$lat_vertice_2 = $strExp1[0];
								$lng_vertice_2 = $strExp1[1];

								if ( $lat_vertice_1 < $lat_point Or $lat_point < $lat_vertice_2 And $lng_point < $lng_vertice_1 Or $lng_vertice_2 < $lng_point ) {
									$result = '0';
									$situacao = 'fora';
								} else {
									$result = '1';
									$situacao = 'dentro';
								}

								if ( $result <> $resultCerca And round($speed * 1.852,0) > 0 ) {
									mysql_query("INSERT INTO message (imei, message) VALUES ('$imeiCerca', 'Cerca ". $nomeCerca ." Violada!')", $cnx);
									
									if ($tipoEnvio == 0) {

										# Convert the GPS coordinates to a human readable address
										$tempstr = "http://maps.google.com/maps/geo?q=$lat_point,$lng_point&oe=utf-8&sensor=true&key=ABQIAAAAFd56B-wCWVpooPPO7LR3ihTz-K-sFZ2BISbybur6B4OYOOGbdRShvXwdlYvbnwC38zgCx2up86CqEg&output=csv"; //output = csv, xml, kml, json
										$rev_geo_str = file_get_contents($tempstr);
										$rev_geo_str = preg_replace("/\"/","", $rev_geo_str);
										$rev_geo = explode(',', $rev_geo_str);
										$logradouro = $rev_geo[2] .",". $rev_geo[3] ;

										require "lib/class.phpmailer.php";

										$consulta1 = mysql_query("SELECT a.*, b.* FROM cliente a INNER JOIN bem b ON (a.id = b.cliente) WHERE b.imei = '$imeiCerca'", $cnx);
										while($data = mysql_fetch_assoc($consulta1)) {

											$emailDestino = $data['email'];
											$nameBem = $data['name'];
											$mensagem = "O veiculo ". $nameBem .", esta ". $situacao ." do perimetro ". $nomeCerca .", as ". date("H:i:s") ." do dia ". date("d/m/Y") .", no local ". $logradouro ." e trafegando a ". round($speed * 1.852, 0) ." km/h.";
													
											$msg = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">";
											$msg .= "<html>";
											$msg .= "<head></head>";
											$msg .= "<body style=\"background-color:#fff;\" >";
											$msg .= "<p><strong>Alerta de Violacao de Perimetro:</strong><br /><br />";
											$msg .= $mensagem ."<br /><br />";
											$msg .= "Equipe System Tracker<br />";
											$msg .= "(85)88462069<br />";
											$msg .= "<a href=\"http://www.systemtracker.com.br\">www.systemtracker.com.br</a></p>";
											$msg .= "</body>";
											$msg .= "</html>";

											$mail = new PHPMailer();
											$mail->Mailer = "smtp";
											$mail->IsHTML(true);
											$mail->CharSet = "utf-8";
											$mail->SMTPSecure = "tls";
											$mail->Host = "smtp.gmail.com";
											$mail->Port = "587";
											$mail->SMTPAuth = "true";
											$mail->Username = "paccelli.rocha";
											$mail->Password = "sua_senha";
											$mail->From = "paccelli.rocha@gmail.com";
											$mail->FromName = "System Tracker";
											$mail->AddAddress($emailDestino);
											$mail->AddReplyTo($mail->From, $mail->FromName);
											$mail->Subject = "System Tracker - Alerta de Violacao de Perimetro";
											$mail->Body = $msg;

											if (!$mail->Send()) {
												echo "Erro de envio: ". $mail->ErrorInfo;
											} else {
												echo "Mensagem enviada com sucesso!";
											}
										}
									}
								}
							}
						} // if imei
					//final verifica geofence

					if($gpsSignalIndicator != 'L') {
						mysql_query("UPDATE bem set date = date, status_sinal = 'R' WHERE imei = '$imei'", $cnx);
						mysql_query("INSERT INTO gprmc (date, imei, phone, satelliteFixStatus, latitudeDecimalDegrees, latitudeHemisphere, longitudeDecimalDegrees, longitudeHemisphere, speed, infotext, gpsSignalIndicator) VALUES (now(), '$imei', '$phone', '$satelliteFixStatus', '$latitudeDecimalDegrees', '$latitudeHemisphere', '$longitudeDecimalDegrees', '$longitudeHemisphere', '$speed', '$infotext', '$gpsSignalIndicator')", $cnx);
					} else {
						mysql_query("UPDATE bem set date = date, status_sinal = 'S' WHERE imei = '$imei'", $cnx);
					}
					
					# Now check to see if we need to send any alerts.
					if (trim($infotext) != "gprmc") {
					   $res = mysql_query("SELECT * FROM bem WHERE imei='$imei'", $cnx);
					   while($data = mysql_fetch_assoc($res)) {
						  switch ($infotext) {
							  case "dt":
								$body = "Disable Track OK";
								break;
							  case "et":
								$body = "Stop Alarm OK";
								break;
							  case "gt";
								$body = "Move Alarm set OK";
								break;
							  case "help me":
								$body = "Help!";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'SOS!')", $cnx);
								break;
							  case "ht":
								$body = "Speed alarm set OK";
								break;
							  case "it":
								$body = "Timezone set OK";
								break;
							  case "low battery":
								$body = "Low battery!\nYou have about 2 minutes...";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Bat. Fraca')", $cnx);
								break;
							  case " bat:":
								$body = "Low battery!\nYou have about 2 minutes...";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Bat. Fraca')", $cnx);
								break;
							  case "Low batt":
								$body = "Low battery!\nYou have about 2 minutes...";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Bat. Fraca')", $cnx);
								break;
							  case "move":
								$body = "Move Alarm!";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Movimento')", $cnx);
								break;
							  case "nt":
								$body = "Returned to SMS mode OK";
								break;
							  case "speed":
								$body = "Speed alarm!";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Velocidade')", $cnx);
								break;
							  case "stockade":
								$body = "Geofence Violation!";
								mysql_query("INSERT INTO message (imei, message) VALUES ('$imei', 'Cerca')", $cnx);
								break;
							} //switch
														
							//Enviando e-mail de alerta
							$headers = "From: $email_from" . "\r\n" . "Reply-To: $email_from" . "\r\n";
							$responsible = $data['responsible'];
							$rv = mail($responsible, "Tracker - $imei", $body, $headers);

						} //while
					}
					
				} else {
					//GRPMC nao precisa reter a sessao
				}
				
				//No protocolo GPRMC cada nova conexão é um IP. Enviando comando no fim da conexao, após obter os dados.
				if (file_exists("$command_path/$conn_imei")) {
					$send_cmd = file_get_contents("$command_path/$conn_imei");
					socket_send($socket, $send_cmd, strlen($send_cmd), 0);
					mysql_query("DELETE FROM command WHERE imei = $conn_imei");
					unlink("$command_path/$conn_imei");
					printLog($fh, "Comandos do Banco e Arquivo apagados: " . $send_cmd . " imei: " . $conn_imei);				
				}
				
				mysql_close($cnx);

				break;
			}
		}
		
		//Checando se utilizou os dois protocolos para uma escuta
		if ($isGIMEI == true and $isGPRMC == true) {
			//printLog($fh, "ATENCAO: falha na obtencao do protocolo. Kill pid.");
		}
		
		$rec = "";
	} //while

} //fim interact

/**
  * Become a daemon by forking and closing the parent
  */
function become_daemon() {
    GLOBAL $fh;

	//printLog($fh, "pcntl_fork() in");
    $pid = pcntl_fork();
	//printLog($fh, "pcntl_fork() out");

    if ($pid == -1) {
        /* fork failed */
		//printLog($fh, "fork failure!");
        exit();
    } elseif ($pid) {
		//printLog($fh, "pid: " . $pid);
        /* close the parent */
        exit();
    } else {
        /* child becomes our daemon */
        posix_setsid();
        chdir('/');
        umask(0);
        return posix_getpid();
    }

	//printLog($fh, "become_daemon() fim");
}

?>
