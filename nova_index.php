<?
include('seguranca.php');
require_once 'config.php';
include_once 'usuario/config.php';

$cnx = mysql_connect($DB_SERVER, $DB_USER, $DB_PASS);
mysql_select_db($DB_NAME);
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
        <meta charset="utf-8" />
        <title>WEBARCH - PLATAFORMA DE RASTREAMENTO</title>
		
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
		
		<!-- SISTEMA ANTIGO -->
			  <link href="/css/jquery-ui.css" type="text/css" rel="stylesheet" />
			  <link href="/css/magnific-popup.css" type="text/css" rel="stylesheet" />
			  <link href="/css/nova.css" type="text/css" rel="stylesheet" />
			  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
			  <script type="text/javascript" src="javascript/jquery-1.7.min.js"></script>
			  <script type="text/javascript" src="javascript/jquery-ui.js"></script>
			  <script type="text/javascript" src="javascript/jquery.form.min.js"></script>
			  <script type="text/javascript" src="javascript/jquery.validate.min.js"></script>
			  <script type="text/javascript" src="javascript/painelAdmin.js"></script>
			  <script type="text/javascript" src="javascript/jquery.magnific-popup.min.js"></script>
			  <script type="text/javascript" src="/javascript/spin.min.js"></script>
			  <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
			  <script src="http://maps.google.com/maps/api/js?sensor=false"></script>
			  <script src="javascript/polygon.min.js" type="text/javascript"></script>
			  <script src="javascript/latlong.js" type="text/javascript"></script>
			  <script src="javascript/geo.js" type="text/javascript"></script>
		<!-- FIM SISTEMA ANTIGO -->
		
        <link href="http://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css" />        
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />       
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/jqvmap/jqvmap/jqvmap.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">        
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />      
        <link href="assets/layouts/layout5/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout5/css/custom.min.css" rel="stylesheet" type="text/css" />       
        <link rel="shortcut icon" href="favicon.ico" />
		
		<script type="text/javascript">
		   $(function() {
			 $( "#tabs" ).tabs({
			  beforeLoad: function( event, ui ) {
			   ui.jqXHR.error(function() {
				ui.panel.html(
				  "Não foi possível carregar esta aba. Atualizar a página pode solucionar este problema." +
				  "Caso o  problema persista, entre em contato com o administrador." );
			  });
			 }
		   });
		   });
		</script>
		
		
</head>
    <body class="page-header-fixed page-sidebar-closed-hide-logo">      
        <div class="wrapper">           
            <header class="page-header">
                <nav class="navbar mega-menu" role="navigation">
                    <div class="container-fluid">
                        <div class="clearfix navbar-fixed-top">                         
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="toggle-icon">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </span>
                            </button>
                            <a id="index" class="page-logo" href="index.html">
                                <img src="assets/layouts/layout5/img/logo.png" alt="Logo"> </a>
                            
                                
                                    
                               
                            
                            <div class="topbar-actions">
                                <div class="btn-group-img btn-group">
                                    <button type="button" class="btn btn-sm md-skip">
                                        <span color="#fff">SEJA BEM-VINDO, ADMINISTRADOR</span>
                                    </button>                                    
                                </div>
								<div class="btn-group-notification btn-group" id="header_notification_bar">
                                    <button type="button" class="btn green btn-sm md-skip dropdown-toggle" data-hover="dropdown">
                                        <a href="logout.php"><i class="fa fa-power-off fa-lg" aria-hidden="true" title="SAIR"></i></a>                                        
                                    </button>
                                </div>
                            </div>
                        </div>
						
                        <div id="tabs" class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">                           
						<?php require_once 'config.php'; ?>							
							<ul class="nav navbar-nav">
                                
								<li class="dropdown dropdown-fw active open selected">
									<a href="ajax/usuarios.php" class="text-uppercase"><i class="fa fa-user"></i>Usuários</a>
								</li>								
								
								<li class="dropdown dropdown-fw">
								<?php if ($representante == 'N') 
									echo"<a href='ajax/equipamentos.php' class='text-uppercase'><i class='fa fa-male fa-lg' aria-hidden='true'></i>clientes</a>";
								?>
								</li>											
																						
								<li class="dropdown dropdown-fw">
								<?php if ($representante == 'N') 
									echo "<a href='ajax/preferencias_form.php' class='text-uppercase'><i class='fa fa-check-square-o fa-lg' aria-hidden='true'></i>Controle</a>"; 
								?>
								</li>
								
								<li class="dropdown dropdown-fw">
								<?php if ($cliente == 'master') 
									echo "<a href='ajax/preferencias_smtp.php?id=".$id_admin." class='text-uppercase''><i class='fa fa-paper-plane fa-lg' aria-hidden='true'></i>Email</a>"; 
								?>
								</li>
								
								<li class="dropdown dropdown-fw">
								<?php if ($cliente == 'master') 
									echo "<a href='ajax/preferencias_rastreadores.php?id=".$id_admin." class='text-uppercase''><i class='fa fa-crosshairs fa-lg' aria-hidden='true'></i>RASTREADORES</a>"; 
								?>
								</li>
								
								<li class="dropdown dropdown-fw">
								<?php if ($cliente == 'master') 
									echo "<a href='ajax/usuarios_master.php' class='text-uppercase'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i>Dados</a>"; 
								?>
								</li>       
                                
                            </ul>
                        
						</div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!--/container-->
                </nav>
            </header>
            <!-- END HEADER 
            <div class="container-fluid">
                <div class="page-content">
                    teste
				</div>
			</div>-->
		</div>
		       
        
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
       
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>       
        <script src="assets/layouts/layout5/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    
</body>



<!-- Mirrored from www.keenthemes.com/preview/metronic/theme/admin_5/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Apr 2016 20:31:24 GMT -->
</html>