<?php
include('../seguranca.php');

$cnx = mysql_connect("localhost", "user1", "pass1")
  or die("Could not connect: " . mysql_error());
mysql_select_db('tracker', $cnx);

$acao = $_POST['acao'];
$codigo = $_POST['codigo'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$tipo = ($_POST['admin'] == 'R') ? 'S' : $_POST['admin'];
$repres = ($_POST['admin'] == 'R') ? 'S' : 'N' ;
$apelido = $_POST['login'];
$senha = $_POST['senha'];
$cpf = $_POST['cpf'];
$envia_sms = $_POST['envia_sms'];
$enviar_acada = $_POST['enviar_acada'];
$celular = $_POST['celular'];
$telefone1 = $_POST['telefone1'];
$telefone2 = $_POST['telefone2'];
$cep = $_POST['cep'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$venc   = $_POST['venc'];
$pessoa = $_POST['tipo_pessoa'];
$rg = $_POST['rg'];
$nacionalidade = $_POST['nacionalidade'];
$adesao = str_replace(',', '.', $_POST['valor_adesao']);
$mensalidade = str_replace(',', '.', $_POST['valor_mensalidade']);

if($acao == 'incluir'){
	if(!mysql_query("INSERT INTO cliente (nome, email, admin, representante, apelido, cpf, envia_sms, sms_acada, celular, telefone1, telefone2, cep, endereco, bairro, cidade, estado, senha, id_admin, dia_vencimento, data_contrato, tipo_pessoa, rg, nacionalidade, valor_adesao, valor_mensalidade)
        VALUES ('$nome','$email','$tipo','$repres', '$apelido', '$cpf','$envia_sms', '$enviar_acada','$celular','$telefone1','$telefone2','$cep','$endereco','$bairro','$cidade','$estado', '".md5($senha)."', '$id_admin', $venc, CURDATE(), '$pessoa', '$rg', '$nacionalidade', $adesao, $mensalidade)", $cnx)) {
        // echo "{sucesso:\"FAIL\", bug:\"".mysql_error($cnx)."\"}";
        $mensagem['sucesso'] = "FAIL";
        $mensagem['bug'] = mysql_error($cnx);
        echo json_encode($mensagem);
    }		
	else{
		$resId = mysql_insert_id($cnx);
        $mensagem['sucesso'] = "OK";
        $mensagem['cliente'] = $resId;
        echo json_encode($mensagem);
		// echo "{sucesso:\"OK\", cliente:\"$resId\"}";
	}
	return;
}

if($acao == 'alterar'){
	if(!empty($senha)){
		$sqlSenha = ", senha='".md5($senha)."' ";
	} else $sqlSenha = "";
	if(!mysql_query("UPDATE cliente SET nome='$nome', email='$email', apelido='$apelido', cpf='$cpf', 
		envia_sms='$envia_sms', sms_acada='$enviar_acada', celular='$celular', telefone1='$telefone1', telefone2='$telefone2', 
		cep='$cep', endereco='$endereco', bairro='$bairro', cidade='$cidade', estado='$estado', dia_vencimento=$venc, tipo_pessoa='$pessoa', rg='$rg', nacionalidade='$nacionalidade', valor_adesao=$adesao, valor_mensalidade=$mensalidade $sqlSenha WHERE id = '$codigo'", $cnx)){
        // echo "{sucesso:\"FAIL\", bug:\"".mysql_error($cnx)."\"}";
        $mensagem['sucesso'] = "FAIL";
        $mensagem['bug'] = mysql_error($cnx);
        echo json_encode($mensagem);
    }
	else{
        $mensagem['sucesso'] = "OK";
        $mensagem['cliente'] = $codigo;
        echo json_encode($mensagem);
        // echo "{sucesso:\"OK\", cliente:\"$codigo\"}";
	}
	return;
}

$id = strip_tags($_GET['id']);
if(!empty($id)){
	$resCliente = mysql_query("SELECT * FROM cliente WHERE id = '$id'", $cnx);
	if($resCliente !== false){
		$dataCliente = mysql_fetch_assoc($resCliente);
	}
}

?>
<div class="row get-code-window">

<?php
    // Variável que volta a tela anterior
    $anterior = ($dataCliente['admin'] == "S") ? "admin" : "usuarios";
?>
<div class="row">
    <a href="javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios.php?lista=<?=$anterior?>')" class="btn btn-default">&larr; VOLTAR PARA LISTA</a> 
    <!--<a href="javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios_form.php')" class="btn btn-default"><i class="fa fa-plus-square"></i> NOVO USUÁRIO</a>-->
</div>
<br>
	
    <form id="form_usuario" action="ajax/usuarios_form.php" method="post" class="" role="form">
    	<input type="hidden" name="acao" value="<?=empty($id)?'incluir':'alterar'?>"/>
        <input type="hidden" name="codigo" value="<?=$dataCliente['id']?>" id="cliente_id"/>
		<h3><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<b>DADOS DE USUÁRIO</h3><br>
<div class="table-responsive">
	<table id="tableEquip" class="equip table table-bordered table-striped">
	<thead><tr style="background: #F0F0F0;">
		<td><b>CODIGO</b></td>
		<td><b>TIPO DE USUARIO</b></td>
		<td><b>NOME</td>
		<td><b>TIPO DE PESSOA</b></td>
		<td><b>NACIONALIDADE</b></td>		
	</tr></thead>
	<tbody>
	<tr>
	<td><input type="text" disabled value="<?=$dataCliente['id']?>" class="form-control" id="codigo" size="10"></td>
	<td><select name="admin" id="admin" class="form-control" <?php if (!empty($id)) echo "disabled"; ?>>
        <option value="N" <?=$dataCliente['admin']=='N'?'selected=selected':''?>>USUÁRIO</option>
        <?php
        if ($representante != 'S'){
        $finalAdmin = ($dataCliente['admin']=='S' && $dataCliente['representante'] != 'S') ? ' selected=selected>ADMINISTRADOR</option>' : '>ADMINISTRADOR</option>';
        $finalRepre = ($dataCliente['representante']=='S') ? ' selected=selected>Representante</option>' : '>REPRESENTANTE</option>';
        echo "<option value='S' $finalAdmin";
        echo "<option value='R' $finalRepre";
        }
        ?>
        </select></td>
	<td><input type="text" value="<?=$dataCliente['nome']?>" name="nome" required size="50" id="nome" class="form-control"></td>
    <td><select name="tipo_pessoa" id="tipo_pessoa" class="form-control">
        <option value="F">F - PESSOA FÍSICA</option>
        <option value="J">J - PESSOA JURÍDICA</option>
        </select></td>
	<td><input type="text" name="nacionalidade" id="nacionalidade" value="<?=$dataCliente['nacionalidade']?>" required class="form-control"></td>
    </tr>
	</tbody>
	<thead><tr style="background: #F0F0F0;">		
		<td><b>LOGIN</b></td>
		<td><b>SENHA</b></td>
		<td><b>EMAIL</b></td>
		<td><b>ENVIA SMS?</b></td>
		<td><b>ENVIA SMS A CADA</b></td>		
	</tr></thead>	
	<tr>
	<tbody>	
    <td><input type="text" value="<?=$dataCliente['apelido']?>" name="login" id="login" required class="form-control"></td>
    <td><input type="text" name="senha" id="senha" class="form-control"></td>
	<td><input type="text" value="<?=$dataCliente['email']?>" name="email" size="50" id="email" class="form-control" required></td>
    <td><select name="envia_sms" id="envia_sms" class="form-control">
                        <option value="S" <?=$dataCliente['envia_sms']=='S'?'selected=selected':''?>>SIM</option>
                        <option value="N" <?=$dataCliente['envia_sms']=='N'?'selected=selected':''?>>NÃO</option>
                    </select></td>
	<td><input type="text" value="<?=$dataCliente['sms_acada']?>" name="enviar_acada" size="5" id="enviar_acada" class="form-control"></td>
	</tr>
	</tbody>
	<thead><tr style="background: #F0F0F0;">		
		<td><b>CPF/CNPJ</b></td>
		<td><b>RG</b></td>
		<td><b>ENDEREÇO</b></td>
		<td><b>BAIRRO</b></td>
		<td><b>CEP</b></td>		
	</tr></thead>
	<tr>
	<tbody>
	<td><input type="text" value="<?=$dataCliente['cpf']?>" name="cpf" size="20" id="cpf" class="form-control"></td>
	<td><input type="text" name="rg" id="rg" value="<?=$dataCliente['rg']?>" required class="form-control"></td>
	<td><input type="text" value="<?=$dataCliente['endereco']?>" name="endereco" size="50" id="endereco" class="form-control"></td>
    <td><input type="text" value="<?=$dataCliente['bairro']?>" name="bairro" id="bairro" class="form-control"></td>
	<td><input type="text" value="<?=$dataCliente['cep']?>" name="cep" size="15" id="cep" class="form-control"></td>
	</tr>
	</tbody>
	<thead><tr style="background: #F0F0F0;">		
		<td><b>TELEFONE 1</b></td>
		<td><b>TELEFONE 2</b></td>
		<td><b>CIDADE</b></td>
		<td><b>UF</b></td>
		<td><b>CELULAR</b></td>		
	</tr></thead>
	<tr><tbody>
	<td><input type="tel" value="<?=$dataCliente['telefone1']?>" name="telefone1" size="12" id="telefone1" class="form-control"></td>
	<td><input type="tel" value="<?=$dataCliente['telefone2']?>" name="telefone2" size="12" id="telefone2" class="form-control"></td>
	<td><input type="text" value="<?=$dataCliente['cidade']?>" name="cidade" id="cidade" class="form-control"></td>
	<td><input type="text" value="<?=$dataCliente['estado']?>" name="estado" maxlength="2" size="2" id="estado" class="form-control"></td>
	<td><input type="tel" value="<?=$dataCliente['celular']?>" name="celular" required size="12" id="celular" class="form-control"></td>
	</tr></tbody>
	<thead><tr style="background: #F0F0F0;">		
		<td><b>VALOR DE ADESÃO</b></td>
		<td><b>VALOR MENSALIDADE</b></td>		
		<td><b>DIA DO VENCIMENTO</b></td>
		<td><b>SALVAR</b></td>		
	</tr></thead>
	<tr><tbody>
	<td><input type="text" name="valor_adesao" id="valor_adesao" value="<?php echo str_replace('.', ',', $dataCliente['valor_adesao']); ?>" required class="form-control"></td>
	<td><input type="text" name="valor_mensalidade" id="valor_mensalidade" value="<?php echo str_replace('.', ',', $dataCliente['valor_mensalidade']); ?>" required class="form-control"></td>
	<td><select name="venc" id="venc" class="form-control">
                        <option value="1" <?php if ($dataCliente['dia_vencimento'] == 1) echo "selected"; ?>>1</option>
                        <option value="10" <?php if ($dataCliente['dia_vencimento'] == 10) echo "selected"; ?>>10</option>
                        <option value="20" <?php if ($dataCliente['dia_vencimento'] == 20) echo "selected"; ?>>20</option>
                    </select></td>
	<td><input type="submit" class="btn btn-success btn-lg" style="width: 100%;" value="SALVAR" class="form-control">
                    <img style="display:none" src="../imagens/executando.gif" id="usuarioFormExecutando">
                    <img id="usuarioFormSucesso" style="display:none" src="../imagens/sucesso.png">
                    <span id="formError" style="display:none; color:red; font-size:14px;"></span></td>
</tr>

		</tbody>
	</table>					
    <br>

	<?php if ($dataCliente['admin'] != 'S') { ?>    
    <div id="veiculos_clientes">
    	<h3><i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;&nbsp;<b>DADOS DE VEÍCULOS</h3><br>
        <a href="ajax/veiculo_form.php?cliente_id=<?=$dataCliente['id']?>" class="popupVeiculoForm btn btn-default" id="linkNovoVeiculo"><i class="fa fa-truck"></i> NOVO VEÍCULO</a>
		<? if(!empty($id)):?>
        <?
        $resBem = mysql_query("SELECT * FROM bem WHERE cliente = '$id'", $cnx);
        ?>
        
    	<ul class="list-group">
		<? $cont = 1; ?>
		<? while($dataBem = mysql_fetch_assoc($resBem)):?>
        <?
			if($cont%2 == 0)
	        	$style='list-group-item-info';
			else
				$style='';
		?>
            <br><br>
            <li class="list-group-item <?=$style?>">
                <p class="list-group-item-text" style="padding-top:0px">
                    <form action="veiculo_form.php" method="POST" role="form" id="veiculo_<?=$dataBem['id']?>" onsubmit="javascript:return(false);">
                        <input type="hidden" value="<?=$dataBem['id']?>" name="id" id="veiculoFormId_<?=$dataBem['id']?>">
                        
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormImei_<?=$dataBem['id']?>">IMEI</label>
                                <input type="text" class="form-control" value="<?=$dataBem['imei']?>" name="imei" size="15" id="veiculoFormImei_<?=$dataBem['id']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormNome_<?=$dataBem['id']?>">PLACA</label>
                                <input type="text" class="form-control" value="<?=$dataBem['name']?>" name="nome" size="14" id="veiculoFormNome_<?=$dataBem['id']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormApelido_<?=$dataBem['id']?>">IDENTIFICAÇÃO</label>
                                <input type="text" class="form-control" value="<?=$dataBem['apelido']?>" size="10" name="apelido" id="veiculoFormApelido_<?=$dataBem['id']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormIdentificacao_<?=$dataBem['id']?>">CHIP</label>
                                <input type="text" class="form-control" value="<?=$dataBem['identificacao']?>" name="identificacao" id="veiculoFormIdentificacao_<?=$dataBem['id']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormAtivo_<?=$dataBem['id']?>">ATIVO</label>
                                <select name="ativo" id="veiculoFormAtivo_<?=$dataBem['id']?>" class="form-control">
                                    <option value="S" <?=($dataBem['activated']=='S'?'selected=selected':'')?>>SIM</option>
                                    <option value="N" <?=($dataBem['activated']=='N'?'selected=selected':'')?>>NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormTipo_<?=$dataBem['id']?>">TIPO</label>
                                <select name="tipo" id="veiculoFormTipo_<?=$dataBem['id']?>" class="form-control">
                                    <option value="CARRO" <?=($dataBem['tipo']=='CARRO'?'selected=selected':'')?>>CARRO</option>
                                        <option value="MOTO" <?=($dataBem['tipo']=='MOTO'?'selected=selected':'')?>>MOTO</option>
                                        <option value="VAN" <?=($dataBem['tipo']=='VAN'?'selected=selected':'')?>>VAN</option>
                                        <option value="JET" <?=($dataBem['tipo']=='JET'?'selected=selected':'')?>>VEÍCULO AQUATICO</option>
                                        <option value="CAMINHAO" <?=($dataBem['tipo']=='CAMINHAO'?'selected=selected':'')?>>CAMINHÃO</option>
                                        <option value="TRATOR" <?=($dataBem['tipo']=='TRATOR'?'selected=selected':'')?>>TRATOR</option>
                                        <option value="ONIBUS" <?=($dataBem['tipo']=='ONIBUS'?'selected=selected':'')?>>ONIBUS</option>
                                        <option value="PICKUP" <?=($dataBem['tipo']=='PICKUP'?'selected=selected':'')?>>PICKUP</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormHodometro_<?=$dataBem['id']?>">HODÔMETRO</label>
                                <input type="text" class="form-control" value="<?=$dataBem['hodometro']?>" size="10" name="hodometro" id="veiculoFormHodometro_<?=$dataBem['id']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormModelo_<?=$dataBem['id']?>">RASTREADOR</label>
                                <select name="modelo_rastreador" id="veiculoFormModelo_<?=$dataBem['id']?>" class="form-control">
									<option value="tk102" <?=($dataBem['modelo_rastreador']=='tk102'?'selected=selected':'')?>>TK102</option>
                                    <option value="tk103" <?=($dataBem['modelo_rastreador']=='tk103'?'selected=selected':'')?>>TK103</option>
                                    <option value="tk104" <?=($dataBem['modelo_rastreador']=='tk104'?'selected=selected':'')?>>TK104</option>
									<option value="tk303" <?=($dataBem['modelo_rastreador']=='tk303'?'selected=selected':'')?>>TK303</option>
                                    <option value="crx1" <?=($dataBem['modelo_rastreador']=='crx1'?'selected=selected':'')?>>CRX1</option>
                                    <option value="gt06" <?=($dataBem['modelo_rastreador']=='gt06'?'selected=selected':'')?>>GT06</option>
                                    <option value="gt06n" <?=($dataBem['modelo_rastreador']=='gt06n'?'selected=selected':'')?>>GT06N</option>
									<option value="h02" <?=($dataBem['modelo_rastreador']=='h02'?'selected=selected':'')?>>H02</option>
                                    <option value="h08" <?=($dataBem['modelo_rastreador']=='h08'?'selected=selected':'')?>>H08</option>								
									<option value="xt009" <?=($dataBem['modelo_rastreador']=='tk103'?'selected=selected':'')?>>XT009</option>
									<option value="tk106a" <?=($dataBem['modelo_rastreador']=='tk106a'?'selected=selected':'')?>>TK106A</option>
									<option value="st200" <?=($dataBem['modelo_rastreador']=='st200'?'selected=selected':'')?>>ST200</option>
									<option value="st215" <?=($dataBem['modelo_rastreador']=='st215'?'selected=selected':'')?>>ST215</option>
									<option value="st340" <?=($dataBem['modelo_rastreador']=='st340'?'selected=selected':'')?>>ST340</option>
									
                                </select>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormModeloBem_<?=$dataBem['id']?>">MODELO</label>
                                <input name="modelo" class="form-control" type="text" maxlength="30" size="9" id="veiculoFormModeloBem_<?=$dataBem['id']?>" value="<?=$dataBem['modelo']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormMarca_<?=$dataBem['id']?>">MARCA</label>
                                <input name="marca" class="form-control" type="text" maxlength="30" size="9" id="veiculoFormMarca_<?=$dataBem['id']?>" value="<?=$dataBem['marca']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormCor_<?=$dataBem['id']?>">COR</label>
                                <input name="cor" class="form-control" type="text" maxlength="30" size="9" id="veiculoFormCor_<?=$dataBem['id']?>" value="<?=$dataBem['cor']?>">
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormAno_<?=$dataBem['id']?>">ANO</label>
                                <input name="ano" class="form-control" type="text" maxlength="4" size="5" id="veiculoFormAno_<?=$dataBem['id']?>" value="<?=$dataBem['ano']?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label for="veiculoFormEnviaSms_<?=$dataBem['id']?>">ENVIAR SMS</label>
                                <select name="envia_sms" id="veiculoFormEnviaSms_<?=$dataBem['id']?>" class="form-control">
                                    <option value="S" <?=($dataBem['envia_sms']=='S'?'selected=selected':'')?>>SIM</option>
                                    <option value="N" <?=($dataBem['envia_sms']=='N'?'selected=selected':'')?>>NÃO</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="veiculoRecarga_<?=$dataBem['id']?>">DATA RECARGA</label>
                                <input name="dt_recarga" class="form-control" type="date" maxlength="10" id="veiculoRecarga_<?=$dataBem['id']?>" value="<?=$dataBem['dt_recarga']?>">
                            </div>                            
                        </div>
						<div class="row"><br>
						<div class="form-group col-sm-2">
                                <button type="button" class="btn btn-primary btn-lg" onclick="javascript:atualizarVeiculo('<?=$dataBem['id']?>')">SALVAR</button>
                                <button type="button" class="btn btn-default btn-lg" onclick="javascript:excluirVeiculo('<?=$dataBem['id']?>')">EXLUIR</button>
                                <img style="display:none" src="../imagens/executando.gif" height="16" width="16" alt="Executando..." id="veiculoFormExecutando_<?=$dataBem['id']?>">
                                <img style="display:none" src="../imagens/sucesso.png" height="16" width="19" alt="Sucesso" id="veiculoFormSucesso_<?=$dataBem['id']?>">
                            </div>
						</div>
                    </form>
                </p>
            </li>
        <?
        	$cont++;
		?>
        <? endwhile;?>
        </ul>
        <? endif;?>
    </div>
    <? } ?>
</div>
<script type="text/javascript">
	$('#form_usuario').ajaxForm({
        dataType: "JSON",
		beforeSubmit:function(){
			$('#usuarioFormExecutando').show();
		},
		success:function(objSon){
			$('#usuarioFormExecutando').hide();
			try {
				// var objSon = eval('('+responseText+')');
				if(objSon.sucesso == 'OK'){
					$('#usuarioFormSucesso').show();
					setTimeout(function(){
                        $('#usuarioFormSucesso').hide();
                    }, 2000);
					
					$('#codigo').val(objSon.cliente);
					$('#cliente_id').val(objSon.cliente);
					$('#linkNovoVeiculo').attr('href', 'ajax/veiculo_form.php?cliente_id='+objSon.cliente);
				}
                else throw objSon.bug;
			}
            catch(e){
                $('#formError').html(e);
                $('#formError').show();
                setTimeout(function(){
                    $('#formError').hide();
                }, 5000);
			}
		}
	});
	
	
	function atualizarVeiculo(idVeiculo){
		var id = $('#veiculoFormId_'+idVeiculo).val();
		var imei = $('#veiculoFormImei_'+idVeiculo).val();
		var nome = $('#veiculoFormNome_'+idVeiculo).val();
		var apelido = $('#veiculoFormApelido_'+idVeiculo).val();
		var identificacao = $('#veiculoFormIdentificacao_'+idVeiculo).val();
		var ativo = $('#veiculoFormAtivo_'+idVeiculo).val();
		var tipo = $('#veiculoFormTipo_'+idVeiculo).val();
		var hodometro = $('#veiculoFormHodometro_'+idVeiculo).val();
        var modelo = $('#veiculoFormModelo_'+idVeiculo).val();  //Modelo do rastreador
		var modeloBem = $('#veiculoFormModeloBem_'+idVeiculo).val();  //Modelo do veiculo
		var marca = $('#veiculoFormMarca_'+idVeiculo).val();
		var cor = $('#veiculoFormCor_'+idVeiculo).val();
		var ano = $('#veiculoFormAno_'+idVeiculo).val();
		var enviaSms = $('#veiculoFormEnviaSms_'+idVeiculo).val();
        var dtRecarga = $('#veiculoRecarga_'+idVeiculo).val();		
		
		$('#veiculoFormExecutando_'+idVeiculo).show();
		$.post('ajax/veiculo_form.php', 
			{'acao':'alterar', 'id':id, 'imei':imei, 'name':nome, 'apelido':apelido, 'identificacao':identificacao, 
			'ativo':ativo, 'tipo':tipo, 'hodometro':hodometro, 'rastreador':modelo, 'modelo': modeloBem, 'marca':marca, 'cor':cor, 'ano':ano, 'envia_sms':enviaSms, 'dt_recarga':dtRecarga},
			function(data){
				$('#veiculoFormExecutando_'+idVeiculo).hide();
				if(data.indexOf('OK') > -1){
					$('#veiculoFormSucesso_'+idVeiculo).show();
					setTimeout(function(){$('#veiculoFormSucesso_'+idVeiculo).hide();}, 2000);
				}
			}
		);
	}
	
	function excluirVeiculo(idVeiculo){
		var id = $('#veiculoFormId_'+idVeiculo).val();
		var imei = $('#veiculoFormImei_'+idVeiculo).val();
		
		$('#veiculoFormExecutando_'+idVeiculo).show();
		$.post('ajax/veiculo_form.php', 
			{'acao':'excluir', 'id':id, 'imei':imei},
			function(data){
				$('#veiculoFormExecutando_'+idVeiculo).hide();
				if(data.indexOf('OK') > -1){
					var cliente_id = $('#cliente_id').val();
					carregarConteudo("#ui-tabs-1", "ajax/usuarios_form.php?acao=view&id="+cliente_id);
				}
			}
		);
	}
	
	$('#cep').blur(function(){
		var busca_cep = $('#cep').val();
		$.ajax({
			url : 'cep_novo.php', /* URL que será chamada */
			type : 'GET', /* Tipo da requisição */
			data: { cep: busca_cep }, /* dado que será enviado via POST */
			dataType: 'json', /* Tipo de transmissão */
			success: function(data){
				if(data.sucesso == 1){
					$('#endereco').val(data.rua);
					$('#bairro').val(data.bairro);
					$('#cidade').val(data.cidade);
					$('#estado').val(data.estado);
				}
			}
		});
		return false;
	});
	
	$('.popupVeiculoForm').magnificPopup({
		type: 'ajax',
		alignTop: true,
		closeOnBgClick:false,
		closeOnContentClick:false,
		overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
	});
	
	$('#admin').change(function(){
		if ($('#admin').val() == "S" || $('#admin').val() == "R") {
			$("#veiculos_clientes").hide();
		}
		else $("#veiculos_clientes").show();
	});
</script>
<?php
mysql_close($cnx);
?>