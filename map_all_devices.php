<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WEBARCH - PLATAFORMA DE RASTREAMENTO</title>

<style type="text/css">
html, body {
	height:98%;
}
#map_canvas {
	height: 699px;
	width: auto;
	margin-left: -8px;
	margin-right: -8px;
	margin-top: -8px;
}
.bordatable {
	color: #333;
}
.bordatable {
	width: 98%;
	background-color: transparent;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	color: #000;
}
.textodescricao {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	color: #003399;
	text-decoration: none;
	font-weight: bold;
}
textodados {
	font-family: Verdana, Geneva, sans-serif;
}
.titulo {
	color: #000;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	text-align: center;
}
.linkAddress:active{
	color: #000;
	font-family: Verdana, Geneva, sans-serif;
	font-size: 10px;
	text-align: center;
}
.labels {
	font-family: inherit;
 	color: red;
 	font-size: 9px;
 	text-align: left;
 	font-weight:bold;
 	width: 80px;
}
#logo{
	position:absolute;
	top:0px;
	left:0px;
	height:30px;
}

</style>

</head>
<body onload="initialize();">
	<div id="map_canvas" align="center">
    </div>    
</body>
</html>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<script type="text/javascript" src="javascript/jquery-1.7.min.js"></script>
<script src="javascript/markerwithlabel.js" type="text/javascript"></script>
<script>

var latlng = new google.maps.LatLng(-13.496473,-55.722656);
var myOptions = {
	zoom: 10,	
	center: latlng,
	navigationControl: true,
	rotateControl: 45,
	rotateControlOptions: {
        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
        position: google.maps.ControlPosition.LEFT_CENTER
    },
	mapTypeControl: true,
    mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
        position: google.maps.ControlPosition.RIGHT_CENTER
    },
    zoomControl: true,
    zoomControlOptions: {
        position: google.maps.ControlPosition.LEFT_CENTER
    },
    scaleControl: true,
    streetViewControl: true,
    streetViewControlOptions: {
        position: google.maps.ControlPosition.LEFT_CENTER
    },
	mapTypeId: google.maps.MapTypeId.SATELLITE,
	mapTypeId: google.maps.MapTypeId.ROADMAP
};
var_map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

function imagemSinal (sinal) {
  switch (sinal) {
	case 'R':
	  var caminho = 'imagens/status_rastreando.png';
	break;

	case 'D':
	  var caminho = 'imagens/status_desligado.png';
	break;

	case 'S':
	  //var caminho = 'imagens/status_sem_sinal.png';
	  var caminho = 'imagens/status_rastreando.png';
	break;
  }

  return ("<img src='" + caminho + "' alt='Status do Sinal'>");
}

function initialize() {
	$.ajax({
	  url: "services.php",
	  type: "GET",
	  data: { service: 'mapAllDevices' },
	  success: function (aDados) {
		//console.log(aDados);
		var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var markers = new Array();  
		var enderecos = new Array();
		var endereco;
			
		var aDados = eval('('+aDados+')');

		for (var i = 0; i < aDados.length; i++) {
		  var dados = aDados[i];

		  if (dados.sinal == 'D') {
			var imgTipo = '_inativo.png';
		  }
		  else if (dados.block == 'S') var imgTipo = '_bloqueado.png';
		  else var imgTipo = '_ativo.png';
		  
		  switch (dados.tipo) {     
			case 'MOTO':
			  var image = 'imagens/marker_moto' + imgTipo;
			break;
			
			case 'CARRO':
			  var image = 'imagens/marker_carro' + imgTipo;
			break;
			
			case 'JET':
			  var image = 'imagens/marker_jet' + imgTipo;
			break;
			
			case 'CAMINHAO':
			  var image = 'imagens/marker_caminhao' + imgTipo;
			break;
			
			case 'VAN':
			  var image = 'imagens/marker_van' + imgTipo;
			break;
			
			case 'PICKUP':
			  var image = 'imagens/marker_pickup' + imgTipo;
			break;
			
			case 'ONIBUS':
			  var image = 'imagens/marker_onibus' + imgTipo;
			break;
			
			case 'TRATOR':
			  var image = 'imagens/marker_trator' + imgTipo;
			break;
			
			default:
			  var image = 'imagens/marker_carro' + imgTipo;
			break;
		  }
		  
			var myLatLng = new google.maps.LatLng(dados.latitude, dados.longitude);
			
			/*
                        marker = new google.maps.Marker({
				position: myLatLng,
				map: var_map,
				icon: image
			});
			*/

     var marker = new MarkerWithLabel({
       position: myLatLng,
       map: var_map,
       draggable: true,
       raiseOnDrag: true,
       labelContent: aDados[i]["name"],
       icon: image,
       animation: google.maps.Animation.DROP,
       labelAnchor: new google.maps.Point(20, 11),
       labelClass: "labels", // the CSS class for the label
       labelInBackground: false
     });


			markers.push(marker);
			
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				// OBTÉM O ENDEREÇO
				return function() {
					infowindow.setContent('	<table width="340" height="185" border="0" cellpadding="0" cellspacing="0"> '+
					'	 <tr> '+
					'	  <td width="320" height="19" align="center" bgcolor="transparent"><strong class="titulo"></br>DETALHES DO VEÍCULO</strong></td>'+
					'	 </tr> '+
					'	 <tr> '+
					'	  <td bgcolor="transparent"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="4" class="bordatable"> '+
					'	   <tr> '+
					'		<td width="60%" style="text-transform: uppercase;"><b>PLACA:</b> '+' '+aDados[i]["name"]+'</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class=""><b>ENDEREÇO:</b> '+' <a href=\"#\" class="linkAddress" onclick=javascript:getAddressGMaps('+aDados[i]["latitude"]+','+aDados[i]["longitude"]+')>VISUALIZAR ENDEREÇO </a></td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class=""><b>IMEI:</b> '+' '+aDados[i]["imei"]+'</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class=""><b>CHIP:</b> '+' '+aDados[i]["identificacao"]+'</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class="" style="text-transform: uppercase;"><b>IDENTIFICAÇÃO:</b> '+' '+aDados[i]["apelido"]+'</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class="" style="text-transform: uppercase;"><b>MODELO RASTREADOR:</b> '+' '+aDados[i]["modelo"]+'</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class=""><b>VELOCIDADE NESTE PONTO:</b> '+' '+aDados[i]["velocidade"]+' Km/h</td> '+
					'	   </tr> '+
					'	   <tr> '+
					'		<td class=""><b>TOTAL DE KM RODADOS:</b> '+' '+aDados[i]["hodometro"]+' Km</td> '+
					'	   </tr> '+
					'	 </table></td> '+
					'	 </tr> '+
					'	</table> ');

					infowindow.open(var_map, marker);
				}
			})(marker, i));
			
		}
		function AutoCenter() {
			var bounds = new google.maps.LatLngBounds();
			$.each(markers, function (index, marker) {
				bounds.extend(marker.position);
			});
			var_map.fitBounds(bounds);
			var_map.zetZoom(10);
		}
		AutoCenter(); 
		
		/*
		var_map.panTo(myLatLng);
		*/
		// Caso seja visualização de GRUPO, define um zoom menor
		// Se for apenas um veículo, define um zoom maior e coloca as informações do hodometro
		if (aDados.length > 1) var_map.setZoom(10);
		else {
		  $('li.status-sinal').html(imagemSinal(dados.sinal));
		  //var_map.setZoom(15);
		  exibirListagemHistorico(imei);
		  $.ajax({
			url: "menu_hodometro.php",
			type: "GET",
			data: { acao: 'hodometro_atual', imei: imei },
			dataType: "JSON",
			success: function (infoHodometro) {
			  $('#hod_atual').val(infoHodometro.hodometro);
			  $('#alerta_hodometro').val(infoHodometro.alerta_hodometro);
			}
		  });
		}			
	  }
	});
}

function getAddress(lat,lng)
{
	console.log('clicou');
	var latlngCoords = new google.maps.LatLng(lat, lng);
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': latlngCoords}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
		  //aDados[i].endereco = results[0].formatted_address;
		  endereco = results[0].formatted_address;
		  console.log(endereco);
		  alert(endereco);
		}
		else
		{
			console.log(status+i);
		}
	});

}

function getAddressGMaps(lat,long)
	{
		var myLatLng = new google.maps.LatLng(lat, long);
		  
		// OBTÉM O ENDEREÇO
		geocoder = new google.maps.Geocoder();
		geocoder.geocode({'latLng': myLatLng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				alert(results[0].formatted_address);
			}
			else 
			{
				alert('Desculpe, não consegui identificar o endereço. Por favor tente novamente em instantes.');
				//return google.maps.GeocoderStatus;
			}
		});
	}
	

</script>