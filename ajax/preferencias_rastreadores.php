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
	$Qy = "
		insert into rastreadores
		   (rastreador)
		   values
		   ('".$_POST["rastreador"]."')
	";
	if(!mysql_query($Qy,$cnx))
	{
		echo "Desculpe, mas não foi possível gravar o novo rastreador. Tente novamente em instantes.";
	}
	else
	{
		header('location: ../nova_index.php?message=OK');
	}
	return;
}
else
{
	$Qy ='
		select *
		  from rastreadores
	';
	$rs = mysql_query($Qy, $cnx);	
}

?>


<h3>CADASTRO E REMOÇÃO DE RASTREADORES</h3>	
<form id="form" action="ajax/preferencias_rastreadores.php" method="post" class="form-horizontal" role="form">
<input type="hidden" name="acao" value="alterar"/>
<div class="table-responsive">
	<table id="tableEquip" class="equip table table-bordered table-striped table-hover">
	<thead><tr style="background: #009DC7">
		<td style="width: 950px;">CADASTRAR RASTREADOR</td>
		<td>CONFIRMAR</td>	    
	</tr></thead>
	<tbody>
	<tr>
	<td><input type="text" value="<?=$row['rastreador']?>" name="rastreador" class="form-control" id="rastreador" size="10"></td>
	<td><div class="">
                    <input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="OK" class="form-control">
                    <img style="display:none" src="../imagens/executando.gif" id="usuarioFormExecutando">
                    <img id="usuarioFormSucesso" style="display:none" src="../imagens/sucesso.png">
                    <span id="formError" style="display:none; color:red; font-size:14px;"></span>
                </div></td>	
</tr>
</tbody>
</table>
<table id="tableEquip" class="equip table table-bordered table-striped table-hover">
	<thead><tr style="background: #009DC7">
		<td style="width: 950px;">REMOVER RASTREADOR</td>
		<td>CONFIRMAR</td>	    
	</tr></thead>
	<tbody>
	<tr>
	<td><select id="rastreadoresCadastrados" class="form-control">
                     <?php
                     while($row = mysql_fetch_assoc($rs))
                     {
                        printf('
                            <option id="'.$row["rastreador"].')">'.$row["rastreador"].'</option>
                        '); 
                     }
                     ?>     
                     </select></td>
	<td><input type="button" class="btn btn-success btn-lg" style="width: 100%;" value="OK" id="btnRetirar" class="form-control"></td>	
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

<script>
$("#btnRetirar").click(function(){
	window.location.href="ajax/delTracker.php?rastreador="+$("#rastreadoresCadastrados").val();
});
</script>