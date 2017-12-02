<?php
include('../seguranca.php');

if ($cliente != "master")
{
	header("location: ../nova_index.php");
}
else
{

$cnx = mysql_connect("localhost", "user1", "pass1")  or die("Could not connect: " . mysql_error());
mysql_select_db('tracker', $cnx);

$acao  = $_POST['acao'];
$host  = $_POST['host'];
$auten = $_POST['auten'];
$user  = $_POST['user'];
$senha = $_POST['senha'];

if($acao == 'alterar'){		
	$QyUpdSmtpPreferencias = "
		update preferencias
		   SET smtp_host = '".$host."', smtp_auten = '".$auten."', smtp_user = '".$user."', smtp_passwd = '".$senha."'
	";

	$ans = mysql_query($QyUpdSmtpPreferencias) or die (mysql_error()); 
	if(!$ans)
	{
		echo "Desculpe, mas não foi possível configurar o SMTP no banco de dados. Tente novamente em instantes.";
	}
	else
	{
		header('location: ../nova_index.php?message=ok');
	}
	return;
}

$QySmtpParams ='
	select smtp_host, smtp_auten, smtp_user, smtp_passwd 
	  from preferencias
';
$rsSmtpParams = mysql_query($QySmtpParams, $cnx) or die(mysql_error());
$rowSmtpParams = mysql_fetch_assoc($rsSmtpParams);	

?>

<h3>CONFIGURAÇÃO SMTP</h3>
<form id="form_usuario" action="ajax/preferencias_smtp.php" method="post" class="form-horizontal" role="form">
<input type="hidden" name="acao" value="alterar"/>
<div class="table-responsive">
	<table id="tableEquip" class="equip table table-bordered table-striped table-hover">
	<thead><tr style="background: #009DC7">
		<td>HOST</td>
		<td>AUTENTICAR?</td>
	    <td>USUÁRIO</td>
	    <td>SENHA</td>
	    <td>SALVAR</td>	    
	</tr></thead>
	<tbody>
	<tr>
	<td><input type="text" value="<?php echo $rowSmtpParams['smtp_host']; ?>" name="host" class="form-control" id="host" size="10" placeholder="Host de Smtp"></td>
	<td><input type="text" value="<?=$rowSmtpParams['smtp_auten']?>" name="auten" size="1" id="auten" class="form-control" placeholder="S ou N"></td>
	<td><input type="text" value="<?=$rowSmtpParams['smtp_user']?>" name="user" id="user" class="form-control" placeholder="Nome do usuário SMTP"></td>
	<td><input type="text" value="<?=$rowSmtpParams['smtp_passwd']?>" name="senha" id="senha" class="form-control" placeholder="Senha do usuário Stmp"></td>
	<td><input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="OK" class="form-control">
        <img style="display:none" src="../imagens/executando.gif" id="usuarioFormExecutando">
        <img id="usuarioFormSucesso" style="display:none" src="../imagens/sucesso.png">
        <span id="formError" style="display:none; color:red; font-size:14px;"></span></td>
</tr>
</tbody>
</table>
</div>
</form>
	
<?php
mysql_close($cnx);
?>

<?php
}
?>