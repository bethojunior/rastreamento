<?php 
include('../seguranca.php');
include('../usuario/config.php');
error_reporting(0);

if ($cliente != "master" && $cliente != "admin") {
	header("Location: /logout.php");
	//header("Location: http://localhost/sistema/logout.php");
}
if ($cliente!="master")
$sqlCliente = " AND id_admin = $id_admin";

$cnx = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS) or die("Could not connect: " . mysql_error());
mysql_select_db($DB_NAME, $cnx);

$countCliente = 0;
$res = mysql_query("SELECT count(*) AS countCliente FROM cliente WHERE master = 'N' $sqlCliente", $cnx);

for ($i=0; $i < 1; $i++) {
	$row = mysql_fetch_assoc($res);	
	$countCliente = (int)$row['countCliente'];
}

$countIMEI = 0;
$res = mysql_query("SELECT count(*) AS countIMEI FROM bem", $cnx);

for ($i=0; $i < 1; $i++) {
	$row = mysql_fetch_assoc($res);	
	$countIMEI = (int)$row['countIMEI'];
}
						
?>
<br /><br />
<?php
$tipoLista = $_GET['lista'];
$masterListaUsuarios = "N";

if ($tipoLista == null or $tipoLista == "usuario") {
	$masterListaUsuarios = "N";
}
elseif ($tipoLista != null and $tipoLista == "admin") {
	$masterListaUsuarios = "S";
}

?>					

<a href='javascript:void(0)' onclick="javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios.php?lista=usuarios')" class="btn btn-default"><i class="fa fa-list-ul" aria-hidden="true"></i> LISTAR USUÁRIOS</a>  
<?
if($_SESSION['clienteSession'] == 'master'){
?>
<a href='javascript:void(0)' onclick="javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios.php?lista=admin')" class="btn btn-default"><i class="fa fa-list-ul" aria-hidden="true"></i> LISTAR ADMINISTRADORES</a> 
<?
}
?>
<a href='javascript:void(0)' onclick="javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios_form.php')" class="btn btn-default"><i class="fa fa-plus-square"></i> NOVO USUÁRIO</a>
    <form name="listaTodosUsuarios" method="post" action="">
    <br />				
    
    <table cellspacing="6" cellpadding="0">
            <tr>
                <td colspan="5">
                    <br />
                </td>
            </tr>					
        <?php 
        //Montando listagem
        $resUsu = mysql_query("SELECT CAST(c.id AS DECIMAL(10,0)) as id, c.id as codCliente, c.email, c.nome, c.ativo, 
                                    (select x.nome from cliente x where x.id = c.id_admin limit 1) as subAdmin,
                                    (select count(*) from bem where cliente = c.id) as qtFrota, envia_sms, sms_acada, 
                                    celular, dt_ultm_sms, cpf, endereco, bairro, cidade, estado, cep, tipo_plano, telefone1, telefone2
                              from cliente c 
                              where c.master = 'N' and admin = '$masterListaUsuarios' $sqlCliente
                              order by c.id DESC", $cnx);
        
        if (mysql_num_rows($resUsu) == 0) {
            echo "<tr><td colspan='5'><b>NENHUM ITEM ENCONTRADO.</b></td> </tr>";
        } else {
            echo "<b>RESULTADOS ENCONTRADOS:</b> ". mysql_num_rows($resUsu);
            $cabFrota = $masterListaUsuarios == "N"? "FROTA" : "DADOS";
            $adminPor = $masterListaUsuarios == "N"? "<td><b>ADMINISTRADO POR</td>" : "";
              echo "<div class='table-responsive'>
					<table id='tableEquip' class='equip table table-bordered table-striped'>
					<thead>
					<tr style='background: #009DC7;'>
                        <td><b>CÓDIGO</td>
                        <td><b>E-MAIL</td>
                        <b>$adminPor
                        <td><b>NOME DO CLIENTE</td>
                        <td><b>ATIVO?</td>
                        <td><b>$cabFrota</td>
                        <td><b>OPÇÕES</td>
                    </tr>
					</thead>
					<tbody>";
        }
        
        for ($i=0; $i < mysql_num_rows($resUsu); $i++) {
            $rowUsu = mysql_fetch_assoc($resUsu);
			
				
            echo "<tr>";
                echo "<td><b><input disabled maxlength='10' size='5' id='listaCodigoCliente". $rowUsu[id] ."' name='listaCodigoCliente". $rowUsu[id] ."' type='text' value='". $rowUsu[codCliente] ."' class='form-control' /></td>";
                echo "<td><input disabled id='listaEmailCliente". $rowUsu[id] ."' name='listaEmailCliente". $rowUsu[id] ."' type='text' value='". $rowUsu[email] ."' class='form-control' /></td>";
                if ($masterListaUsuarios == "N")
                    echo "<td><input disabled size='25' id='listaSubAdmin". $rowUsu[id] ."' name='listaSubAdmin". $rowUsu[id] ."' type='text' value='". $rowUsu[subAdmin] ."' class='form-control' /></td>";
                echo "<td><input disabled size='25' id='listaNomeCliente". $rowUsu[id] ."' name='listaNomeCliente". $rowUsu[id] ."' type='text' value='". $rowUsu[nome] ."' class='form-control' /></td>";
                echo "<div class='form-group'><td><select id='listaAtivoCliente". $rowUsu[id] ."' name='listaAtivoCliente". $rowUsu[id] ."' class='form-control' >";
                    if ($rowUsu[ativo] == 'S') {
                        echo "<option selected value='S'>SIM</option>
                              <option value='N'>NÃO</option>";
                    } else {
                        echo "<option value='S'>SIM</option>
                              <option selected value='N'>NÃO</option>";
                    }
                    echo "</select>";
               
                echo "<td> <div style='width:86px'><a href='javascript:void(0);'>";
                if ($masterListaUsuarios == "N") {
                    
					?>                             
                   <label size='0' style='cursor: pointer;' title='EDITAR CLIENTE' alt='EDITAR CLIENTE' href='javascript:void(0)' onclick='javascript:carregarConteudo("#ui-tabs-1", "ajax/usuarios_form.php?acao=view&id=<?=$rowUsu[id]?>")' >VEÍCULOS</label> <sup><span class="badge"><?=$rowUsu[qtFrota]?></span></sup></div></td>
							
            <?php
				} else
                    echo "<img border=0 src='../imagens/editar.png' width='50' title='EDITAR DADOS ADMINISTRADOR' alt='EDITAR DADOS ADMINISTRADOR' onclick=\"javascript:carregarConteudo('#ui-tabs-1', 'ajax/usuarios_form.php?acao=view&id=". $rowUsu[id] ."');\" /> </a> </div></td>";								

                echo "<td>";
                        
						//echo "<img id='imgPrefCliente". $rowUsu[id] ."' src='../imagens/prefer14.png' title='Preferencias...' alt='Preferencias...' style='cursor:pointer' onclick='javascript:exibirPref(".$rowUsu[id].")' />&nbsp;";
                        
														
						echo "<img id='imgContrato". $rowUsu[id] ."' src='../imagens/contrato.png' title='IMPRIMIR CONTRATO' alt='IMPRIMIR CONTRATO' style='cursor:pointer' onclick='javascript:exibirContrato(".$rowUsu[id].")' width='24' height='24' />&nbsp;";
                        
						echo "<img id='imgFormulario". $rowUsu[id] ."' src='../imagens/formulario.png' title='IMPRIMIR FORMULARIO DE INSTALAÇÃO' alt='IMPRIMIR FORMULARIO DE INSTALAÇÃO' style='cursor:pointer' onclick='javascript:exibirForm(".$rowUsu[id].")' width='24' height='24' />&nbsp;";
                        echo "<img src='../imagens/salvar.png' style='cursor: pointer;' title='SALVAR DADOS' alt='SALVAR DADOS' onclick='salvarUsuarioAdmin(". $rowUsu[id] .");' width='24' height='24' /> ";
                        echo "<img id='imgExecutandoCliente". $rowUsu[id] ."' style='display:none' src='../imagens/executando.gif' title='EXECUTANDO...' alt='EXECUTANDO...' />";
                        echo "<img id='imgSucessoCliente". $rowUsu[id] ."' style='display:none' src='../imagens/sucesso.png' title='ALTERAÇÃO SALVA' alt='ALTERAÇÃO SALVA' />";                
						echo "<a href='javascript:void(0);'><img border=0 src='../imagens/delete.png' title='EXCLUIR CONTA' alt='EXCLUIR CONTA' onclick='excluirUsuarioAdmin_Novo(". $rowUsu[id] .");' width='24' height='24' /></a>";
						echo "<img id='imgExcluindoCliente". $rowUsu[id] ."' style='display:none' src='../imagens/executando.gif' title='EXECUTANDO...' alt='EXECUTANDO...' />";
                echo "</td>";
            echo "</tr>";
            //
			//echo "<tr>";
           // echo "<td>";
           // echo "<table width='100%' id='prefCliente".$rowUsu[id]."' style='display:none; text-align:left' align='left'>";
            //echo "<tr><th width='160'>Celular</th><th width='160'>Telefone 1</th><th width='160'>Telefone 2</th><th width='100'>Envia SMS?</th><th width='160'>Enviar a Cada (min)</th><th>Dt. Ultimo Envio</th></tr>";
            //echo "<td><input type='text' name='celular' id='clienteCelular".$rowUsu[id]."' value='".$rowUsu[celular]."' maxlength='15'/></td>";
            //echo "<td><input type='text' name='telefone1' id='clienteTelefone1".$rowUsu[id]."' value='".$rowUsu[telefone1]."' maxlength='15'/></td>";
            //echo "<td><input type='text' name='telefone2' id='clienteTelefone2".$rowUsu[id]."' value='".$rowUsu[telefone2]."' maxlength='15'/></td>";
            //echo "<td><select name='enviar_sms' id='clienteEnviarSms".$rowUsu[id]."'><option value='S' ".($rowUsu[envia_sms]=='S'?"selected=selected":"").">Sim</option><option value='N' ".($rowUsu[envia_sms]=='N'?"selected=selected":"").">Não</option></select></td>";
            //echo "<td><input type='text' name='enviar_acada' id='clienteEnviarACada".$rowUsu[id]."' value='".$rowUsu[sms_acada]."' maxlength='4'/></td>";
            //echo "<td><input type='text' name='dt_ultm_sms' id='clienteDtUltmSms".$rowUsu[id]."' value='".$rowUsu[dt_ultm_sms]."' disabled='disabled'/></td>";
            //echo "</table>";
            //echo "<table width='100%' id='prefDadosCliente".$rowUsu[id]."' style='display:none; text-align:left' align='left'>";
            //echo "<tr><th width='160'>CPF/CNPJ</th><th width='100'>Endereço</th><th width='160'>Bairro</th><th>Cidade</th><th>Estado</th><th>CEP</th><th>Tipo Plano</th></tr>";
            //echo "<td><input type='text' name='cpf' id='clienteCpf".$rowUsu[id]."' value='".$rowUsu[cpf]."' maxlength='14' size='15'/></td>";
            //echo "<td><input type='text' name='endereco' id='clienteEndereco".$rowUsu[id]."' value='".$rowUsu[endereco]."' maxlength='150'/></td>";
            //echo "<td><input type='text' name='bairro' id='clienteBairro".$rowUsu[id]."' value='".$rowUsu[bairro]."' maxlength='60'/></td>";
           // echo "<td><input type='text' name='cidade' id='clienteCidade".$rowUsu[id]."' value='".$rowUsu[cidade]."' maxlength='80'/></td>";
           // echo "<td><input type='text' name='estado' id='clienteEstado".$rowUsu[id]."' value='".$rowUsu[estado]."' maxlength='2' size='3'/></td>";
            //echo "<td><input type='text' name='cep' id='clienteCep".$rowUsu[id]."' value='".$rowUsu[cep]."' maxlength='9' size='10'/></td>";
            //echo "<td><select name='tipo' id='clienteTipoPlano".$rowUsu[id]."'><option value='CARRO' ".($rowUsu[tipo_pano]=='CARRO'?"selected=selected":"").">Carro</option><option value='MOTO'  ".($rowUsu[tipo_pano]=='MOTO'?"selected=selected":"").">Moto</option><option value='PLUS' ".($rowUsu[tipo_pano]=='PLUS'?"selected=selected":"").">Plus</option></select></td>";
            //echo "<td>";
            //echo "<img src='../imagens/salvar.png' title='Salvar dados' alt='Salvar dados' onclick='salvarUsuarioAdminPref(". $rowUsu[id] .");' style='cursor:pointer' /> ";
            //echo "<img id='imgExecutandoClientePref". $rowUsu[id] ."' style='display:none' src='../imagens/executando.gif' title='Executando...' alt='Executando...' />";
            //echo "<img id='imgSucessoClientePref". $rowUsu[id] ."' style='display:none' src='../imagens/sucesso.png' title='Alteração salva' alt='Alteração salva' />";
           // echo "</td>";
            //echo "</table>";
            //echo "</td>";
           // echo "</tr>";
        }
        ?>
    </table>
    </form>
    
<div id="usuarios_form"></div>

<script type="text/javascript">
$('.popupUsuariosForm').magnificPopup({
		type: 'ajax',
		alignTop: true,
		closeOnBgClick:false,
		closeOnContentClick:false,
		overflowY: 'scroll' // as we know that popup content is tall we set scroll overflow by default to avoid jump
	});

function exibirContrato (idCliente) {
    var printWindow = window.open('contrato.php?cliente=' + idCliente, 'Imprimir', 'left=200, top=200, width=950, height=500, scrollbars=1');
    printWindow.addEventListener('load', function(){
        printWindow.print();
        // printWindow.close();
    }, true);
}

function exibirForm (idCliente) {
    var printWindow = window.open('relatorio_instalacao.php?cliente=' + idCliente, 'Imprimir', 'left=200, top=200, width=950, height=500, scrollbars=1');
    printWindow.addEventListener('load', function(){
        printWindow.print();
        // printWindow.close();
    }, true);
}
</script>

<?php
mysql_close($cnx);
?>