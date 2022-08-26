<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;

class PoliticsController extends ActionController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = Container::getClass("Politics", "app");
    }

    public function indexAction()
    {
        $entity = $this->model->getOne();
        $this->view->entity = $entity;
        return $this->render('index', false);
    }

    public function updateProcessAction()
    {
        if (!empty($_POST)) {
            $postData = [
                'description' => $_POST['description'],
            ];
            
            $crud = new Crud();
            $crud->setTable($this->model->getTable());
            $transaction = $crud->update($postData, $_POST['uuid'], 'uuid');

            if ($transaction){
                $this->toLog("Atualizou o conteúdo da política de privacidade");
                $data  = [
                    'title' => 'Sucesso!', 
                    'msg'   => 'Informação atualizada.',
                    'type'  => 'success',
                    'pos'   => 'top-right'
                ];
            } else {
                $data  = [
                    'title' => 'Erro!', 
                    'msg' => 'Os dados não foram atualizados.',
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