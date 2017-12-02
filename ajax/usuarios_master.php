<?php
include('seguranca.php');
error_reporting(0);

$con = mysql_connect('localhost', 'user1', 'pass1');
mysql_select_db('tracker');

$result      = mysql_query("SELECT * FROM cliente WHERE id_admin = 0");
$dataCliente = mysql_fetch_assoc($result);
$numrows     = mysql_num_rows($result);


$acao          = $_POST['acao'];
$nome          = $_POST['nome'];
$email         = $_POST['email'];
$apelido       = $_POST['login'];
$senha         = $_POST['senha'];
$cpf           = $_POST['cpf'];
$celular       = $_POST['celular'];
$telefone1     = $_POST['telefone1'];
$telefone2     = $_POST['telefone2'];
$cep           = $_POST['cep'];
$endereco      = $_POST['endereco'];
$bairro        = $_POST['bairro'];
$cidade        = $_POST['cidade'];
$estado        = $_POST['estado'];
$rg            = $_POST['rg'];

$id_admin= 0;

if ($acao=='inserir')
{
mysql_query("
		insert into cliente 
		( nome, email, apelido, cpf, celular, telefone1, telefone2, cep, endereco, bairro, cidade, estado, senha, id_admin, rg)
        values
		('$nome','$email','$apelido', '$cpf', '$celular', '$telefone1','$telefone2', '$cep','$endereco','$bairro','$cidade','$estado', '".md5($senha)."', 0, '$rg'") or die(mysql_error());
}
 
if ($acao=='alterar')
{
	$ans = mysql_query("
			update cliente 
			   set nome='$nome', 
			       email='$email', 
				   apelido='$apelido', 
				   cpf='$cpf', 
				   celular='$celular',  
				   telefone1='$telefone1', 
				   telefone2='$telefone2', 
				   cep='$cep', 
				   endereco='$endereco', 
				   bairro='$bairro', 
				   cidade='$cidade', 
				   estado='$estado', 
				   rg='$rg',
				   senha='".md5($senha)."' 
		     where id_admin = 0
	") or die(mysql_error());  
    header('location: ../nova_index.php?message=Gravou com sucesso. ');
}

?>


<h3>INFORMAÇÕES USUÁRIO MASTER</h3>	
    <form id="form_usuario" action="ajax/usuarios_master.php" method="post" class="form-horizontal" role="form">
    	<input type="hidden" name="acao" value="alterar"/>
        <input type="hidden" name="codigo" value="<?=$dataCliente['id']?>" id="cliente_id"/>
		<div class="table-responsive">
			<table id="tableEquip" class="equip table table-bordered table-striped">
			<thead><tr style="background: #009DC7;">
				<td><b>CODIGO</b></td>
				<td><b>NOME</b></td>
				<td><b>E-MAIL</td>
				<td><b>LOGIN</b></td>
				<td><b>SENHA</b></td>
				<td><b>CPF/CNPJ</b></td>
				<td><b>RG</b></td>							
			</tr></thead>
			<tbody>					
			<tr>
			<td><input type="text" disabled value="<?=$dataCliente['id']?>" class="form-control" id="codigo" size="15"></td>
			<td><input type="text" value="<?=$dataCliente['nome']?>" name="nome" required size="40" id="nome" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['email']?>" name="email" size="40" id="email" class="form-control" required></td>
			<td><input type="text" value="<?=$dataCliente['apelido']?>" name="login" id="login" required class="form-control"></td>
			<td><input type="text" name="senha" id="senha" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['cpf']?>" name="cpf" size="30" id="cpf" class="form-control"></td>
			<td><input type="text" name="rg" id="rg" value="<?=$dataCliente['rg']?>" required class="form-control"></td>
			</tr>
			</tbody>
			</table>			
			
			<form id="form_usuario" action="ajax/usuarios_master.php" method="post" class="form-horizontal" role="form">
    	<input type="hidden" name="acao" value="alterar"/>
        <input type="hidden" name="codigo" value="<?=$dataCliente['id']?>" id="cliente_id"/>
		<div class="table-responsive">
			<table id="tableEquip" class="equip table table-bordered table-striped">
			<thead><tr style="background: #009DC7;">
				<td><b>CEP</b></td>
				<td><b>ENDEREÇO</b></td>
				<td><b>BAIRRO</td>
				<td><b>CIDADE</b></td>
				<td><b>UF</b></td>
				<td><b>CELULAR</b></td>
				<td><b>TELEFONE 1</b></td>
				<td><b>TELEFONE 2</b></td>
				<td><b>SALVAR</b></td>
			</tr></thead>
			<tbody>					
			<tr>
			<td><input type="text" value="<?=$dataCliente['cep']?>" name="cep" size="15" id="cep" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['endereco']?>" name="endereco" size="50" id="endereco" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['bairro']?>" name="bairro" id="bairro" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['cidade']?>" name="cidade" id="cidade" class="form-control"></td>
			<td><input type="text" value="<?=$dataCliente['estado']?>" name="estado" maxlength="2" size="2" id="estado" class="form-control"></td>
			<td><input type="tel" value="<?=$dataCliente['celular']?>" name="celular" required size="12" id="celular" class="form-control"></td>
			<td><input type="tel" value="<?=$dataCliente['telefone1']?>" name="telefone1" maxlength="11" size="12" id="telefone1" class="form-control"></td>
			<td><input type="tel" value="<?=$dataCliente['telefone2']?>" name="telefone2" maxlength="11" size="12" id="telefone2" class="form-control"></td>
			<td><input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="OK" class="form-control">
                    <img style="display:none" src="../imagens/executando.gif" id="usuarioFormExecutando">
                    <img id="usuarioFormSucesso" style="display:none" src="../imagens/sucesso.png">
                    <span id="formError" style="display:none; color:red; font-size:14px;"></span>
               </td>
			</tr>
			</tbody>			
			</table>
			
			</div>
			</form>           
            
                
           
        </fieldset>
    </form>

	
</div>
<?php
mysql_close($cnx);
?>
