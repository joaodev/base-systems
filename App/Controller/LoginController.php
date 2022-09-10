<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Db\Bcrypt;
use Core\Db\Crud;
use Core\Di\Container;
use PHPMailer;

class LoginController extends ActionController
{
	private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = Container::getClass("User", "app");

        if (!empty($_POST)) {
            self::dataValidation($_POST);
        }
    }

    public function indexAction()
    {
        return $this->render('index', false);
    }

    public function authAction()
    {
    	if (!empty($_POST)) {
        	$email = $_POST['email'];
	        $password  = $_POST['password'];

	        $credentials = $this->userModel
	        					->findByCrenditials($email, $password); 

	        if ($credentials) {
                $_SESSION['COD']         = $credentials['uuid'];
	            $_SESSION['NAME']        = $credentials['name'];
	            $_SESSION['EMAIL']       = $credentials['email'];
	            $_SESSION['PASS']        = $credentials['password'];
	            $_SESSION['ROLE_NAME']   = $credentials['role'];
	            $_SESSION['ROLE']        = $credentials['role_uuid'];
	            $_SESSION['ROLE_ADM']    = $credentials['is_admin'];
	            $_SESSION['PHOTO']       = $credentials['file'];

                $this->toLog("Fez login no sistema");

	            return self::redirect('');
	        } else {
	            return self::redirect('', 'usuario-invalido');
	        }
        } else {
        	return self::redirect('');
        }
    }

    public function logoutAction()
    {
        $this->toLog("Fez logout do sistema");

        unset($_SESSION['COD']);
        unset($_SESSION['NAME']);
        unset($_SESSION['EMAIL']);
        unset($_SESSION['PASS']);
        unset($_SESSION['ROLE_NAME']);
        unset($_SESSION['ROLE']);
        unset($_SESSION['PHOTO']);
        session_destroy();

        return self::redirect('');
    }

    public function forgotPasswordAction()
    {
        if (!empty($_POST)) {
            $userUuid = $this->userModel->getUuidByField('email', $_POST['email'], 'uuid');
            if ($userUuid > 0) {
                $user = $this->userModel->find($userUuid, 'uuid, name, email', 'uuid');
                $code = $this->randomString();

                $crud = new Crud();
                $crud->setTable($this->userModel->getTable());
                
                $updateCode = $crud->update([
                    'code' => md5($code),
                    'updated_at' => date('Y-m-d H:i:s')
                ], $userUuid, 'uuid');

                if ($updateCode) {
                    $config = $this->getSiteConfig();
                    $message = '<!DOCTYPE html>
                                <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
                                <head>
                                    <meta charset="UTF-8">
                                    <meta name="viewport" content="width=device-width,initial-scale=1">
                                    <meta name="x-apple-disable-message-reformatting">
                                    <title></title>
                                    <!--[if mso]>
                                    <noscript>
                                        <xml>
                                            <o:OfficeDocumentSettings>
                                                <o:PixelsPerInch>96</o:PixelsPerInch>
                                            </o:OfficeDocumentSettings>
                                        </xml>
                                    </noscript>
                                    <![endif]-->
                                    <style>
                                        table, td, div, h1, p {font-family: Arial, sans-serif;}
                                    </style>
                                </head>
                                <body style="margin:0;padding:0;">
                                    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
                                        <tr>
                                            <td align="center" style="padding:0;">
                                                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                                                    <tr>
                                                        <td align="center" style="padding:10px 0 10px 0;background:'.$config['primary_color'].';">
                                                            <h1 style="color: white; text-shadow: black 0.1em 0.1em 0.2em;">'.$config['site_title'].'</h1>
                                                            <img src="'. baseUrl . 'public/uploads/logo/' . $config['logo'].'" 
                                                                alt="'.$config['site_title'].'" width="300" style="height:auto;display:block;" />
                                                            <h2 style="color: white; text-shadow: black 0.1em 0.1em 0.2em;">Código de Verificação</h2>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:36px 30px 42px 30px;">
                                                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                                <tr>
                                                                    <td style="padding:0 0 10px 0;color:#153643;">
                                                                        <p>Olá, '.$user['name'].', tudo bem? </p>
                                                                        <p>Este é o seu código para validar sua alteração de senha:</p>
                                                                        <br>
                                                                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">'.$code.'</h1>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="padding:0;">
                                                                        <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                                                            <tr>
                                                                                <td style="width:260px;padding:0;vertical-align:top;color:#153643;">
                                                                                    <p style="margin:0 0 12px 0;font-size:11px;line-height:15px;font-family:Arial,sans-serif;">*Esta é uma mensagem automática, não responda este email.</p>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="padding:30px;background:'.$config['primary_color'].';">
                                                            <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                                                <tr>
                                                                    <td style="padding:0;width:100%;" align="right">
                                                                        <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                                                            &reg; '.$config['site_title'].'<br/>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </body>
                                </html>';   

                    $mail = new PHPMailer();
                    $mail->isSMTP();                                            
                    $mail->Host       = $config['mail_host'];
                    $mail->SMTPAuth   = true;                                 
                    $mail->Username   = $config['mail_username'];
                    $mail->Password   = $config['mail_password'];
                    $mail->Port       = $config['mail_port'];      
        
                    $mail->setFrom($config['mail_from_address'], $config['site_title']);
                    $mail->addAddress($user['email']);
        
                    $message = wordwrap($message, 70, "\r\n");
                    $mail->isHTML(true);                                  
                    $mail->Subject = utf8_decode('Código de Verificação' . $config['site_title']);
                    $mail->Body    = utf8_decode($message);
                    $mail->send();

                    $this->toLog("{$user['name']} solicitou um código para recuperação de senha.");
                }
            } 
            
            return self::redirect('validar-codigo');
        } else {
            return $this->render('forgot-password', false);
        }
    }
    
    public function codeValidationAction()
    {
        if (!empty($_POST)) {
            $code = md5($_POST['code']);
            $userUuid = $this->userModel->getUuidByField('code', $code, 'uuid');
            if ($userUuid > 0) {
                $crud = new Crud();
                $crud->setTable($this->userModel->getTable());
                $validatedCode = $crud->update([
                    'code_validated' => '1',
                    'updated_at' => date('Y-m-d H:i:s')
                ], $userUuid, 'uuid');
    
                if ($validatedCode) {
                    $this->view->code = $code;
                    return $this->render('change-password', false);
                } else {
                    return self::redirect('validar-codigo', 'codigo-invalido');
                }
            } else {
                return self::redirect('validar-codigo', 'codigo-invalido');
            }
        } else {
            return $this->render('code-validation', false);
        }
    }

    public function changePasswordAction()
    {
        if (!empty($_POST)) {
            if (empty($_POST['info'])) {
                return self::redirect('validar-codigo', 'codigo-invalido');
            } else {
                if ($_POST['password'] != $_POST['confirmation']) {
                    $this->view->code = $_POST['info'];
                    $this->view->errorPasswords = 'Senhas incorretas!';
                    return $this->render('change-password', false);
                } else {
                    $userUuid = $this->userModel->getUuidByField('code', $_POST['info'], 'uuid');
                    if ($userUuid > 0) {
                        $user = $this->userModel->find($userUuid, 'uuid, email, password, code, code_validated', 'uuid');
                        if ($user['code'] != $_POST['info'] || $user['code_validated'] != '1') {
                            return self::redirect('validar-codigo', 'codigo-invalido');
                        } else {
                            $crud = new Crud();
                            $crud->setTable($this->userModel->getTable());
                            
                            $newPassword = Bcrypt::hash($_POST['password']);
                            $updatedPassword = $crud->update([
                                'password' => $newPassword,
                                'code' => null,
                                'code_validated' => '0',
                                'updated_at' => date('Y-m-d H:i:s')
                            ], $user['uuid'], 'uuid');

                            if ($updatedPassword) {   
                                $credentials = $this->userModel
	        					    ->authByCrenditials($user['email'], $newPassword); 
                            
                                if ($credentials) {
                                    $_SESSION['COD']         = $credentials['uuid'];
                                    $_SESSION['NAME']        = $credentials['name'];
                                    $_SESSION['EMAIL']       = $credentials['email'];
                                    $_SESSION['PASS']        = $newPassword;
                                    $_SESSION['ROLE_NAME']   = $credentials['role'];
                                    $_SESSION['ROLE']        = $credentials['role_uuid'];
                                    $_SESSION['ROLE_ADM']    = $credentials['is_admin'];
                                    $_SESSION['PHOTO']       = $credentials['file'];
                                    
                                    $this->toLog("Atualizou a senha e fez o login no sistema.");

                                    return self::redirect('');
                                } else {
                                    return self::redirect('', 'usuario-invalido');
                                }
                            } else {
                                return self::redirect('validar-codigo', 'codigo-invalido');
                            }
                        }
                    } else {
                        return self::redirect('validar-codigo', 'codigo-invalido');
                    }
                }
            }
        } else {
            return self::redirect('validar-codigo', 'codigo-invalido');
        }
    }
}