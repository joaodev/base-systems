<?php

namespace App\Controller;

use Core\Controller\ActionController;

class IndexController extends ActionController
{    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function indexAction()
    {
        return $this->render('index');
    }
}