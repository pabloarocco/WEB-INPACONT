<?php
	function post_captcha($user_response) {
			$fields_string = '';
			$fields = array('secret' => '6LfxddEUAAAAAJ0ApeLUW5Ri1Rsc-CCK7RIpUcij','response' => $user_response);
			foreach($fields as $key=>$value)
			$fields_string .= $key . '=' . $value . '&';
			$fields_string = rtrim($fields_string, '&');

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

			$result = curl_exec($ch);
			curl_close($ch);
		return json_decode($result, true);
	}
	// Agregarmos una variable CaptCha
	$res = post_captcha($_POST['g-recaptcha-response']);

	if( $_POST['nombre'] && $_POST['remitente'] && $_POST['mensaje'] && $res['success'] ){
		$remitente = 'info@inpacont.com.ar';
		$destinatario = 'info@inpacont.com.ar'; // en esta línea va el mail del destinatario.
		$asunto = 'Consulta'; // acá se puede modificar el asunto del mail
				
		$cuerpo = "<html><body><br><img src='http://inpacont.com.ar/img/logo.png' height='50'><br><br><h3>Nombre y apellido:</h3> " . $_POST["nombre"] ; 
		$cuerpo .= "<br><h3>Email:</h3> " . $_POST["remitente"] ;
		$cuerpo .= "<br><h3>Consulta:</h3> " . $_POST["mensaje"] . "<br><br><center>Mensaje enviado desde el sitio <a href='http://inpacont.com.ar'>Inpacont</a></center></body></html>";

		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=utf-8\n";
		$headers .= "X-Priority: 3\n";
		$headers .= "X-MSMail-Priority: Normal\n";
		$headers .= "X-Mailer: php\n";
		$headers .= "From: \"".$_POST['nombre']." \" <".$remitente.">\n";

		if( mail($destinatario, $asunto, $cuerpo, $headers) ){	
			$successmail = '<div class="alert alert-success" role="alert"><center><p><strong>Mensaje Enviado!</strong><p></center></div>';
		}	
		else{
			$errorMail= '<div class="alert alert-danger" role="alert"><center><p><strong>Hubo un error al enviar el mensaje! Intentalo nuevamente!</strong><p></center></div>';
		}
	}
	elseif( isset($_POST['enviar']) ){
		if (!$res['success']) {
			$errorCap = '<div class="alert alert-danger" role="alert"><center><p><strong>Completa el Captcha!</strong><p></center></div>';
		}
		else{
			$errorForm = '<div class="alert alert-danger" role="alert"><center><p><strong>Completa el Formulario!</strong><p></center></div>';
		}		
	}	
?>