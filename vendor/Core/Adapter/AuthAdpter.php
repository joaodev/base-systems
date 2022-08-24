<?php

namespace Core\Adapter;

use Core\Di\Container;

class AuthAdpter
{
    public function getIdentity()
    {
        $userSession = $this->checkUserSession();
        return $userSession;
    }

    private function checkUserSession()
    {
        @session_start();
        if (!empty($_SESSION['EMAIL']) && !empty($_SESSION['PASS'])) {
            $email = $_SESSION['EMAIL'];
            $pass  = $_SESSION['PASS'];
        } else {
            $email = "";
            $pass  = "";
        }
        
        $collaborator = Container::getClass("User", 'app'); 
        $validated    = $collaborator->authByCrenditials($email, $pass);

        return $validated;
    }
}