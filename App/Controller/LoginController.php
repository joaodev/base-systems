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
                $_SESSION['COD']         = $credentials['id'];
	            $_SESSION['NAME']        = $credentials['name'];
	            $_SESSION['EMAIL']       = $credentials['email'];
	            $_SESSION['PASS']        = $credentials['password'];
	            $_SESSION['ROLE_NAME']   = $credentials['role'];
	            $_SESSION['ROLE']        = $credentials['role_id'];
	            $_SESSION['ROLE_ADM']    = $credentials['is_app'];
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
            $userId = $this->userModel->getIdByField('email', $_POST['email'], 'id');
            if ($userId > 0) {
                $user = $this->userModel->find($userId, 'id, name, email', 'id');
                $code = $this->randomString();

                $crud = new Crud();
                $crud->setTable($this->userModel->getTable());
                
                $updateCode = $crud->update([
                    'code' => md5($code),
                    'updated_at' => date('Y-m-d H:i:s')
                ], $userId, 'id');

                if ($updateCode) {
                    $seo = $this->getSiteSeo();
                    $config = $this->getSiteConfig();

                    $message = "
                        <p>Olá, " . $user['name'] . "!</p>
                        <p>Este é o seu código para validar sua alteração da sua senha:</p>
                        <p style='font-size: 30px;'><b>" . $code . "</b></p>
                    ";
        
                    $mail = new PHPMailer();
                    $mail->isSMTP();                                            
                    $mail->Host       = $config['mail_host'];
                    $mail->SMTPAuth   = true;                                 
                    $mail->Username   = $config['mail_username'];
                    $mail->Password   = $config['mail_password'];
                    $mail->Port       = $config['mail_port'];      
        
                    $mail->setFrom($config['mail_from_address'], $seo['title']);
                    $mail->addAddress($user['email']);
        
                    $message = wordwrap($message, 70, "\r\n");
                    $mail->isHTML(true);                                  
                    $mail->Subject = utf8_decode($seo['title'] . ' - CÓDIGO DE RECUPERAÇÃO');
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
            $userId = $this->userModel->getIdByField('code', $code, 'id');
            if ($userId > 0) {
                $crud = new Crud();
                $crud->setTable($this->userModel->getTable());
                $validatedCode = $crud->update([
                    'code_validated' => '1',
                    'updated_at' => date('Y-m-d H:i:s')
                ], $userId, 'id');
    
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
                    $userId = $this->userModel->getIdByField('code', $_POST['info'], 'id');
                    if ($userId > 0) {
                        $user = $this->userModel->find($userId, 'id, email, password, code, code_validated', 'id');
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
                            ], $user['id'], 'id');

                            if ($updatedPassword) {   
                                $credentials = $this->userModel
	        					    ->authByCrenditials($user['email'], $newPassword); 
                            
                                if ($credentials) {
                                    $_SESSION['COD']         = $credentials['id'];
                                    $_SESSION['NAME']        = $credentials['name'];
                                    $_SESSION['EMAIL']       = $credentials['email'];
                                    $_SESSION['PASS']        = $newPassword;
                                    $_SESSION['ROLE_NAME']   = $credentials['role'];
                                    $_SESSION['ROLE']        = $credentials['role_id'];
                                    $_SESSION['ROLE_ADM']    = $credentials['is_app'];
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