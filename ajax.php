<?php
  include_once 'seguranca.php';
  include_once 'usuario/config.php';
  include_once 'config.php';
  $token      = (isset($_POST['token'])) ? $_POST['token'] : false ;
  $auth_user  = isset($_SESSION['logSessioUser']) ? $_SESSION['logSessioUser'] : false;
  $logado     = isset($_SESSION['logSession']) ? $_SESSION['logSession'] : false;

  if (!$logado) {
  	header("Location: index.php");
  	exit();
  }
  $_SESSION['tokenSession'] = $token;	//Se estiver ok, coloca na nessao, e checa sempre na segurança

  $cnx = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS);
  mysql_select_db($DB_NAME);
?>
<?php
$query = mysql_query("SELECT * FROM bem WHERE cliente = $cliente");
$dados = mysql_fetch_assoc($query);
?>

<strong>PLACA: </strong><?=$dados['name'] = mb_strtoupper($dados['name'])?></br>
<strong>IMEI: </strong><?=$dados['imei']?></br>
<strong>CHIP: </strong><?=$dados['identificacao'] = mb_strtoupper($dados['identificacao'])?></br>
<strong>MODELO: </strong><?=$dados['modelo'] = mb_strtoupper($dados['modelo'])?></br>
<strong>MARCA: </strong><?=$dados['marca'] = mb_strtoupper($dados['marca'])?></br>
<strong>COR: </strong><?=$dados['cor'] = mb_strtoupper($dados['cor'])?></br>
<strong>ANO: </strong><?=$dados['ano'] = mb_strtoupper($dados['ano'])?></br>
<strong>HODOMETRO: </strong><?=$dados['hodometro'] = mb_strtoupper($dados['hodometro'])?></br>
<strong>IDENTIFICAÇÃO: </strong><?=$dados['apelido'] = mb_strtoupper($dados['apelido'])?></br>
<strong>TIPO: </strong><?=$dados['tipo'] = mb_strtoupper($dados['tipo'])?></br>
<strong>RASTREADOR: </strong><?=$dados['modelo_rastreador'] = mb_strtoupper($dados['modelo_rastreador']);?></br>
<?php
if ($dados['ligado'] == 'S') echo "<img src='imagens/ligado-2.png' alt='Ligado' rel='tooltip' title='IGNIÇÃO LIGADA'>";
else echo "<img src='imagens/desligado-2.png' alt='Desligado' rel='tooltip' title='IGNIÇÃO DESLIGADA'>";
if ($dados['bloqueado'] == 'N') echo " <img src='imagens/desbloqueado-2.png' alt='Veículo Desbloqueado' rel='tooltip' title='VEÍCULO DESBLOQUEADO'>"; 
else echo " <img src='imagens/bloqueado-2.png' alt='Bloqueado' rel='tooltip' title='VEÍCULO BLOQUEADO'>";
?>
<script>
$(document).ready(function () {   
      
	  $("img[rel=tooltip]").tooltip({ placement: 'bottom' });
	  $("button[rel=tooltip]").tooltip({ placement: 'bottom' });
	 	 
  	});
</script>
<script type="text/javascript" src="javascript/jquery.magnific-popup.min.js"></script>


