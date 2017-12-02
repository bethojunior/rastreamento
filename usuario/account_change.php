<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <title>Trocar Senha</title>
        <meta charset="ISO-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="imagens/favicon.ico">
        <!-- Bootstrap CSS -->
        <link href="../css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="../css/login.css">
        <!-- jQuery -->
        <script src="../javascript/jquery-1.10.2.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="login-container col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3">
                <div class="avatar" class="col-xs-12">
                <?php
                    require_once 'config.php';
                    echo "<b>TROCA DE SENHA</b>";
                ?>
                </div>
                <div class="form-box">
                    <form>
                    	<h4>
                        	Para sua segurança o sistema irá gerar uma nova senha e enviar para seu e-mail cadastrado.
                            <br><br>
							por favor informe no campo abaixo o seu e-mail.
						</h4>
                        <input  id="txtEmail" name="auth_email" type="text" placeholder="e-mail">
                        <button id="btnChangeLogin" class="btn btn-primary btn-block login" type="button">Trocar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<!-- Bootstrap JavaScript -->
<script src="../javascript/bootstrap.min.js"></script>
<script src="../javascript/jquery-1.10.2.js"></script>
<script src="../javascript/bootstrap.js"></script>
<script src="../javascript/jquery.validate.min.js"></script>
<script src="../javascript/bootstrap-waitingfor.js"></script>
<script type="text/javascript">
$("#btnChangeLogin").click(function(){
	
	myApp.showPleaseWait();
	
	if ($("#txtEmail").val()=='')
	{
		alert("Por favor informe seu e-mail.");
	}
	else
	{
		verifyEmail();
	}
	
	function verifyEmail()
	{		
		$.ajax({
			type: "POST",
			url: 'verify_email.php',
			data: {email: $("#txtEmail").val()},
			success: function (response) {			   
			   if (response!='')
			   {
				   changePassword(response);
			   }
			   else
			   {
				   alert('E-mail não encontrado. Por favor verifique ou entre em contato com o suporte.');
				   $("#txtEmail").empty();
				   myApp.hidePleaseWait();
			   }
			},
			error: function(ajaxError){
			}			
		});        
	}	
	
	function changePassword(obj)
	{
		$.ajax({
			type: "POST",
			url: 'change_password.php',
			data: {id: obj, email: $("#txtEmail").val() },
			success: function (response) {
				if (response=='OK')
			   	{
				   alert('Sua nova senha foi enviada para seu e-mail. Por favor verifique sua caixa de entrada e caixa de spam.');
				   alert('Redirecionando para a página de login.');
				   window.location="../";
				   
			   }
			   else
			   {
				   alert(response);
				   myApp.hidePleaseWait();
			   }
			},
			error: function(ajaxError){
			}			
		});
	}
});

var myApp;
myApp = myApp || (function () {
    var pleaseWaitDiv = $('<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1>Processing...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div></div>');
    return {
        showPleaseWait: function() {
            pleaseWaitDiv.modal();
        },
        hidePleaseWait: function () {
            pleaseWaitDiv.modal('hide');
        },

    };
})();

</script>