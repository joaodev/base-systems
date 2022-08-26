<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;
use App\Interfaces\CrudInterface;

class CustomersController extends ActionController implements CrudInterface
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = Container::getClass("Customers", "app");
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
    
    public function createProcessAction()
    {
        if (!empty($_POST)) {
            if ($_POST['document_1'] == '___.___.___-__') {
                $_POST['document_1'] = null;
            }

            if ($_POST['document_2'] == '__.___.___/____-__') {
                $_POST['document_2'] = null;
            }
            
            $_POST['uuid'] = $this->model->NewUUID();
            $_POST['user_uuid'] = $_SESSION['COD'];
            $crud = new Crud();
            $crud->setTable($this->model->getTable());

            $transaction = $crud->create($_POST);
            if ($transaction){
                $this->toLog("Cadastrou o Cliente {$_POST['name']} #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Cliente cadastrado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Cliente não foi cadastrado.',
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
    
    public function updateAction()
    {
        if (!empty($_POST['uuid'])) {
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
            if ($_POST['document_1'] == '___.___.___-__') {
                $_POST['document_1'] = null;
            }

            if ($_POST['document_2'] == '__.___.___/____-__') {
                $_POST['document_2'] = null;
            }

            $_POST['updated_at'] = date('Y-m-d H:i:s');
            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->update($_POST, $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Atualizou o Cliente {$_POST['name']} #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Cliente atualizado.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Cliente não foi atualizado.',
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
        if (!empty($_POST['uuid'])) {
            $entity = $this->model->getOne($_POST['uuid']);
            $this->view->entity = $entity;
            return $this->render('read', false);
        } else {
            return false;
        }
    }

	public function deleteAction()
    {
        if (!empty($_POST)) {
            $crud = new Crud();
            $crud->setTable($this->model->getTable());

            $transaction = $crud->update([
                'deleted' => '1',
                'updated_at' => date('Y-m-d H:i:s')
            ], $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Removeu o Cliente #{$_POST['uuid']}");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Cliente removido.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'O Cliente não foi removido.',
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
            $uuid     = (!empty($_POST['uuid']) ? $_POST['uuid'] : null);

            if (!empty($_POST['name'])) $field = 'name';
            if (!empty($_POST['document_1'])) $field = 'document_1';
            if (!empty($_POST['document_2'])) $field = 'document_2';
            if (!empty($_POST['email'])) $field = 'email';
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
}