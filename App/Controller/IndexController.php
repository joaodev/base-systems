<?php

namespace App\Controller;

use Core\Controller\ActionController;
use Core\Di\Container;

class IndexController extends ActionController
{    
    private $userModel;
    private $customerModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = Container::getClass("User", "app");
        $this->customerModel = Container::getClass("Customers", "app");
    }
    
    public function indexAction()
    {
        $total_users = $this->userModel->totalUsers();
        $total_customers = $this->customerModel->totalCustomers();

        $this->view->total_users = $total_users;
        $this->view->total_customers = $total_customers;
        return $this->render('index');
    }
}