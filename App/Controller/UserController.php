<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;
use App\Interfaces\CrudInterface;
use Core\Db\Bcrypt;

class UserController extends ActionController implements CrudInterface
{
    private $model;
    private $roleModel;
    private $privilegeModel;
    private $aclModel;

    public function __construct()
    {
        parent::__construct();
        $this->model          = Container::getClass("User", "app");
        $this->roleModel      = Container::getClass("Role", "app");
        $this->privilegeModel = Container::getClass("Privilege", "app");
        $this->aclModel       = Container::getClass("Acl", "app");
    }

    public function indexAction()
    {
        $data = $this->model->getAll();
        $this->view->data = $data;
        return $this->render('index', false);
    }

    public function createAction()
    {
        $roles = $this->roleModel->getAll(null);
        $this->view->roles = $roles;
        return $this->render('create', false);
    }

    public function createProcessAction()
    {
        if (!empty($_POST)) {
            if (!empty($_POST['role_uuid'])) {
                $role = $this->roleModel->getOne($_POST['role_uuid']);
                if (!$role) {
                    $data  = [
                        'title' => 'Erro!', 
                        'msg' => 'Nível de acesso inválido.',
                        'type' => 'error',
                        'pos'   => 'top-center'
                    ];
                    
                    echo json_encode($data);
                    return true;
                }
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'Nível de acesso inválido.',
                    'type' => 'error',
                    'pos'   => 'top-center'
                ];
                
                echo json_encode($data);
                return true;
            }

            if ($_POST['password'] != $_POST['confirmation']) {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'Senhas incorretas.',
                    'type' => 'error',
                    'pos'   => 'top-center'
                ];
            } else {
                unset($_POST['confirmation']);
                $_POST['password'] = Bcrypt::hash($_POST['password']);
    
                $_POST['uuid'] = $this->model->NewUUID();
                $crud = new Crud();
                $crud->setTable($this->model->getTable());
                $transaction = $crud->create($_POST);
    
                if ($transaction){
                    $privileges = $this->privilegeModel->getAllByRoleUuid($_POST['role_uuid']);
                    foreach ($privileges as $privilege) {
                        $aclData = [
                            'uuid' => $this->privilegeModel->NewUUID(),
                            'user_uuid' => $_POST['uuid'],
                            'privilege_uuid' => $privilege['uuid']
                        ];
    
                        $crud->setTable($this->aclModel->getTable());
                        $crud->create($aclData);
                    }
    
                    $this->toLog("Cadastrou o usuário: {$_POST['name']} #{$_POST['uuid']}");
                    $data  = [
                        'title' => 'Sucesso!', 
                        'msg'   => 'Usuário cadastrado.',
                        'type'  => 'success',
                        'pos'   => 'top-right'
                    ];
                } else {
                    $data  = [
                        'title' => 'Erro!', 
                        'msg' => 'O Usuário não foi cadastrado.',
                        'type' => 'error',
                        'pos'   => 'top-center'
                    ];
                }
            }

            echo json_encode($data);
            return true;
        } else {
            return false;
        }
    }

	public function readAction()
    {
        if (!empty($_POST['uuid'])) {
            $entity = $this->model->getOne($_POST['uuid']);
            $this->view->entity = $entity;
            return $this->render('read', false);
        } else {
            return false;
        }
    }

	public function updateAction()
    {
        if (!empty($_POST)) {
            $roles = $this->roleModel->getAll(null);
            $this->view->roles = $roles;

            $entity = $this->model->getOne($_POST['uuid']);
            $this->view->entity = $entity;

            return $this->render('update', false);
        } else {
            return false;
        }
    }

    public function updateProcessAction()
    {
        if (!empty($_POST)) {
            if (!empty($_POST['password']) && !empty($_POST['confirmation'])) {
                if ($_POST['password'] != $_POST['confirmation']) {
                    $data  = [
                        'title' => 'Erro!', 
                        'msg' => 'Senhas incorretas.',
                        'type' => 'error',
                        'pos'   => 'top-center'
                    ];
                } else {
                    unset($_POST['confirmation']);
                    $_POST['password'] = Bcrypt::hash($_POST['password']);
                }
            } else {
                unset($_POST['password']);
                unset($_POST['confirmation']);
            }

            if (!empty($_POST['role_uuid'])) {
                unset($_POST['role_uuid']);
            }
            
            $_POST['updated_at'] = date('Y-m-d H:i:s');
            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->update($_POST, $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Atualizou o usuário: {$_POST['name']} #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Usuário atualizado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Usuário não foi atualizado.',
                    'type' => 'error',
                    'pos'   => 'top-center'
                ];
            }

            echo json_encode($data);
            return true;
        } else {
            return false;
        }
    }

	public function deleteAction()
    {
        if (!empty($_POST)) {
            if (($_POST['uuid'] != $_SESSION['COD'])) {
                $updateData = [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'deleted' => '1'
                ];

                $crud = new Crud();
                $crud->setTable($this->model->getTable());
                $transaction = $crud->update($updateData, $_POST['uuid'], 'uuid');

                if ($transaction){
                    $this->toLog("Removeu o usuário: #{$_POST['uuid']}");
                    $data  = [
                        'title' => 'Sucesso!', 
                        'msg'   => 'Usuário removido.',
                        'type'  => 'success',
                        'pos'   => 'top-right'
                    ];
                } else {
                    $data  = [
                        'title' => 'Erro!', 
                        'msg' => 'O Usuário não foi removido.',
                        'type' => 'error',
                        'pos'   => 'top-center'
                    ];
                }
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Usuário está logado.',
                    'type' => 'error',
                    'pos'   => 'top-center'
                ];
            }
        } else {
            return false;
        }

        echo json_encode($data);
        return true;
    }

    public function fieldExistsAction()
    {
        if (!empty($_POST)) {
            $uuid     = (!empty($_POST['uuid']) ? $_POST['uuid'] : null);

            if (!empty($_POST['name'])) $field      = 'name';
            if (!empty($_POST['email'])) $field     = 'email';
            if (!empty($_POST['cellphone'])) $field = 'cellphone';
            
            $exists = $this->model->fieldExists($field, $_POST[$field], 'uuid', $uuid);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            return false;
        }
    }

    public function aclAction()
    {
        if (!empty($_POST)) {
            $data = $this->aclModel->getUserPrivileges($_POST['uuid']);
            $this->view->data = $data;
            return $this->render('acl', false);
        } else {
            return false;
        }
    }

    public function alterPrivilegeAction()
    {
        if (!empty($_POST)) {
            $crud = new Crud();
            $crud->setTable($this->aclModel->getTable());
            $transaction = $crud->update($_POST, $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Permissões de usuário atualizadas: #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Privilégio atualizado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Privilégio não foi atualizado.',
                    'type' => 'error',
                    'pos'   => 'top-center'
                ];
            }

            echo json_encode($data);
            return true;
        } else {
            return false;
        }
    }
}