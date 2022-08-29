<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;
use Core\Db\Crud;

class LogsController extends ActionController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = Container::getClass("Logs", "app");
    }

    public function indexAction()
    {
        $data = $this->model->getAll();
        $this->view->data = $data;
        return $this->render('index', false);
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

}