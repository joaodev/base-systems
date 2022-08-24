<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;
use App\Interfaces\CrudInterface;

class RoleController extends ActionController implements CrudInterface
{
    private $model;
    private $modulesModel;
    private $privilegesModel;
    private $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->model = Container::getClass("Role", "app");
        $this->modulesModel = Container::getClass("Module", "app");
        $this->privilegesModel = Container::getClass("Privilege", "app");
        $this->userModel = Container::getClass("User", "app");
    }

    public function indexAction()
    {
        $data = $this->model->getAll();
        $this->view->data = $data;
        return $this->render('index', false);
    }

    public function createAction()
    {
        return $this->render('create', false);
    }

    private function savePrivilege($role, $resource, $module)
    {
        $data = [
            'role_id' => $role,
            'resource_id' => $resource,
            'module_id' => $module
        ];

        $crud = new Crud();
        $crud->setTable($this->privilegesModel->getTable());
        return $crud->create($data);
    }

    public function createProcessAction()
    {
        if (!empty($_POST)) {
            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->create($_POST);

            if ($transaction){
                $modules = $this->modulesModel->getAll();
                foreach ($modules as $module) {
                    if ($module['view_id'] != 0) {
                        $this->savePrivilege($transaction, $module['view_id'], $module['id']);
                    }

                    if ($module['create_id'] != 0) {
                        $this->savePrivilege($transaction, $module['create_id'], $module['id']);
                    }   

                    if ($module['update_id'] != 0) {
                        $this->savePrivilege($transaction, $module['update_id'], $module['id']);
                    }

                    if ($module['delete_id'] != 0) {
                        $this->savePrivilege($transaction, $module['delete_id'], $module['id']);
                    }
                }
                
                $this->toLog("Cadastrou o perfil de acesso: {$_POST['name']} #{$transaction}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Perfil cadastrado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Perfil não foi cadastrado.',
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

	public function readAction()
    {
        if (!empty($_POST['id'])) {
            $entity = $this->model->getOne($_POST['id']);
            $this->view->entity = $entity;
            return $this->render('read', false);
        } else {
            return false;
        }
    }

	public function updateAction()
    {
        if (!empty($_POST)) {
            $entity = $this->model->getOne($_POST['id']);
            $this->view->entity = $entity;

            return $this->render('update', false);
        } else {
            return false;
        }
    }

    public function updateProcessAction()
    {
        if (!empty($_POST)) {
            $_POST['updated_at'] = date('Y-m-d H:i:s');
            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->update($_POST, $_POST['id'], 'id');

            if ($transaction){
                $this->toLog("Atualizou o perfil de acesso: {$_POST['name']} #{$_POST['id']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Perfil atualizado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Perfil não foi atualizado.',
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
            $checkDeletePermission = $this->userModel->checkDeletePermission($_POST['id']);
            if ($checkDeletePermission) {
                $updateData = [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'deleted' => '1'
                ];

                $crud = new Crud();
                $crud->setTable($this->model->getTable());
                $transaction = $crud->update($updateData, $_POST['id'], 'id');

                if ($transaction){
                    $this->toLog("Removeu o perfil de acesso: #{$_POST['id']}");
                    $data  = [
                        'title' => 'Sucesso!', 
                        'msg'   => 'Perfil removido.',
                        'type'  => 'success',
                        'pos'   => 'top-right'
                    ];
                } else {
                    $data  = [
                        'title' => 'Erro!', 
                        'msg' => 'O Perfil não foi removido.',
                        'type' => 'error',
                        'pos'   => 'top-center'
                    ];
                }
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'Há usuários com este nível vinculado, desvincule para excluir.',
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

    public function fieldExistsAction()
    {
        if (!empty($_POST)) {
            $id     = (!empty($_POST['id']) ? $_POST['id'] : null);

            if (!empty($_POST['name'])) $field = 'name';
            
            $exists = $this->model->fieldExists($field, $_POST[$field], 'id', $id);
            if ($exists) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            return false;
        }
    }
}