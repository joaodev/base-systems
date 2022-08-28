<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;

class PrivilegeController extends ActionController
{
    private $model;
    private $roleModel;

    public function __construct()
    {
        parent::__construct();
        $this->model     = Container::getClass("Privilege", "app");
        $this->roleModel = Container::getClass("Role", "app");
    }

    public function indexAction()
    {
        $role = $this->roleModel->getOne($_POST['uuid']);
        $this->view->role = $role;
        
        $data = $this->model->getAllByRoleUuid($_POST['uuid']);
        $this->view->data = $data;

        return $this->render('index', false);
    }

    public function changePrivilegeAction()
    {
        if (!empty($_POST)) {
            $update = [
                'status' => $_POST['status'],
            ];

            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->update($update, $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Acão aplicada ao privilegio #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Ação realizada.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'A ação não foi realizada.',
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