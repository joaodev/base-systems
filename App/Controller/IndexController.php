<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;

class IndexController extends ActionController
{    
    private $userModel;
    private $customersModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = Container::getClass("User", "app");
        $this->customersModel = Container::getClass("Customers", "app");
    }
    
    public function indexAction()
    {
        $total_users = $this->userModel->totalUsers();
        $this->view->total_users = $total_users;

        $total_customers = $this->customersModel->totalCustomers();
        $this->view->total_customers = $total_customers;
        
        return $this->render('index');
    }
}