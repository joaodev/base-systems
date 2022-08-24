<?php
	require '../classes/PHPMailer/PHPMailerAutoload.php';
	
	$Mailer = new PHPMailer();
	
	//Define que será usado SMTP
	$Mailer->IsSMTP();
	
	//Enviar e-mail em HTML
	$Mailer->isHTML(true);
	
	//Aceitar carasteres especiais
	$Mailer->Charset = 'UTF-8';
	
	//Configurações
	$Mailer->SMTPAuth = true;
	$Mailer->SMTPSecure = 'ssl';
	
	//nome do servidor
	$Mailer->Host = 'mail.genpro.com.br';
	//Porta de saida de e-mail 
	$Mailer->Port = 587;
	
	//Dados do e-mail de saida - autenticação
	$Mailer->Username = 'paulo.sistemas@genpro.com.br';
	$Mailer->Password = 'p@ul059';
	
	//E-mail remetente (deve ser o mesmo de quem fez a autenticação)
	$Mailer->From = 'paulo.sistemas@genpro.com.br';
	
	//Nome do Remetente
	$Mailer->FromName = 'SEPAC';
	
	//Assunto da mensagem
	$Mailer->Subject = 'Titulo - teste email';
	
	//Corpo da Mensagem
	$Mailer->Body = 'Conteudo do E-mail';
	
	//Corpo da mensagem em texto
	$Mailer->AltBody = 'conteudo do E-mail em texto';
	
	
	//Destinatarios
	$address = array('leo@genpro.com.br');
	while (list ($key, $val) = each ($address)) {
		$Mailer->AddAddress($val);
	}
	
	if($Mailer->Send()){
		echo "E-mail enviado com sucesso";
	}else{
		echo "Erro no envio do e-mail: " . $Mailer->ErrorInfo;
	}

?>