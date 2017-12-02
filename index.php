<?php
  include_once 'usuario/config.php';
  $cnx = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS);
  mysql_select_db($DB_NAME);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>:: LOGIN ::</title>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/main.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/plugins.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/login.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="assets/css/fontawesome/font-awesome.min.css">	
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="assets/js/libs/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/libs/lodash.compat.min.js"></script>
	<script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
	<script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
	<script type="text/javascript" src="assets/js/login.js"></script>
	<script>
	$(document).ready(function(){
		"use strict";
		
		Login.init(); // Init login JavaScript
	});
	</script>
	<style>
	body {
    background: url(imagens/bg.jpg) no-repeat center top fixed;
 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
</style>

</head>
<body class="login">										
	
	<div class="box">
		<div class="content">
<div class="logo">
		<img src="assets/img/logo.png" alt="logo" style="width:170px;" />		
	</div>		
			<form class="form-vertical login-form" action="usuario/account_login.php" method="post">
				<input name="admin" type="hidden" value="18">
				<input name="grupo" type="hidden" value="2"><br>
				<div id="output" class="col-xs-12">
                    <?php
                    if (isset($_GET['error'])) {
                        echo "<script>$('#output').addClass('alert alert-danger animated fadeInUp'); setTimeout(function () { $('#output').slideUp(); }, 5000); </script>";
                        echo "Usuário ou senha incorretos.";
                    }
                    elseif (isset($_GET['desativado'])) {
                        echo "<script>$('#output').addClass('alert alert-danger animated fadeInUp'); setTimeout(function () { $('#output').slideUp(); }, 5000); </script>";
                        echo "Conta inativa! Acesso não autorizado.";
                    }
                    ?>
                </div>								
				<div class="alert fade in alert-danger" style="display: none;">
					<i class="icon-remove close" data-dismiss="alert"></i>
					Por favor entre com usuário e senha.
				</div>
				
				<div class="form-group">
					<div class="input-icon">
						<i class="icon-user"></i>
						<input type="text" name="auth_user" class="form-control" placeholder="Usuário" autofocus="autofocus" data-rule-required="true" data-msg-required="Por favor, insira seu nome de usuário." />
					</div>
				</div>
				<div class="form-group">
					<div class="input-icon">
						<i class="icon-lock"></i>
						<input type="password" name="auth_pw" class="form-control" placeholder="Senha" data-rule-required="true" data-msg-required="Por favor, insira sua senha." />
					</div>
				</div>
				<div class="form-actions">
					<label class="checkbox pull-left"><input type="checkbox" class="uniform" name="remember"> Lembrar-me</label>
					<button type="submit" class="submit btn btn-primary pull-right">
						ACESSAR <i class="icon-angle-right"></i>
					</button>
				</div>
			</form>			
		</div>
		
		<div class="inner-box">
			<div class="content">
				<i class="icon-remove close hide-default"></i>
				<a href="#" class="forgot-password-link">Esqueceu a senha?</a>
				<form class="form-vertical forgot-password-form hide-default" action="login.html" method="post">
					<div class="form-group">
						<div class="input-icon">
							<i class="icon-envelope"></i>
							<input type="text" id="txtEmail" name="auth_email" class="form-control" placeholder="Insira o endereço de e-mail" data-rule-required="true" data-rule-email="true" data-msg-required="Por favor digite o seu e-mail." />
						</div>
					</div>
					<button id="btnChangeLogin" type="submit" class="submit btn btn-default btn-block">
						Redefinir sua senha
					</button>
				</form>
				<div class="forgot-password-done hide-default">
					<i class="icon-ok success-icon"></i> <!-- Error-Alternative: <i class="icon-remove danger-icon"></i> -->
					<span>Recuperação de senha concluída, veja sua caixa de e-mail.</span>
				</div>
			</div> 
		</div>
	</div>
	<div class="footer">
		<p font color="#fff">WEBARCH - PLATAFORMA DE RASTREAMENTO</P>
	</div>
</body>
<script src="javascript/bootstrap.min.js"></script>
<script src="javascript/jquery-1.10.2.js"></script>
<script src="javascript/bootstrap.js"></script>
<script src="javascript/jquery.validate.min.js"></script>
<script src="javascript/bootstrap-waitingfor.js"></script>
</html>