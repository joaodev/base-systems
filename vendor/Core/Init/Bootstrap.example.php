<?php
/*
namespace Core\Init;

use Core\Adapter\AuthAdpter;
use Core\Controller\ActionController;

class Bootstrap
{
    private $project_url;
    private $routes;

    public function __construct($routes, $projectUrl)
    {
        $this->routes = $routes;
        $this->project_url = $projectUrl;
        $this->run($this->getUrl());
    }

    protected function run($url)
    {
        foreach ($this->routes as $namespace => $route) {
            array_walk($this->routes[$namespace], function ($router) use ($url) {
                $request_url = $this->project_url . $router['route'];
    
                if ($request_url == $url) {
                    if ($router['namespace'] == "app") {
     
                        if ($url == $this->project_url . "/admin/login")
                            return header("Location: " . $this->project_url);

                        $auth = new AuthAdpter();
                        if (!$auth->getIdentity()) {
                            $login = new \App\Controller\LoginController();

                            if ($url != $this->project_url . "/auth") {
                                if ($url != $this->project_url . "/esqueci-a-senha") {
                                    if ($url != $this->project_url . "/validar-codigo") {
                                        if ($url != $this->project_url . "/alterar-senha") {
                                            return $login->indexAction();
                                        }
                                    }
                                }
                            } else {
                                return $login->authAction();
                            }
                        } else {
                            if ($url == $this->project_url . "/auth")
                                return ActionController::redirect('/inicio');
                        }
                    }

                    $namespace = ucfirst($router['namespace']);
                    $controllerHasTwoNames = strpos($router['controller'], '-');

                    if ($controllerHasTwoNames) {
                        $controller_exp = explode('-', $router['controller'], 2);
                        $controllerName = ucfirst($controller_exp[0]) . ucfirst($controller_exp[1]);
                    } else {
                        $controllerName = ucfirst($router['controller']);
                    }

                    $class = $namespace . "\\Controller\\" . $controllerName . "Controller";
                    $controller = new $class;

                    $hasTwoNames = strpos($router['action'], '-');

                    if ($hasTwoNames) {
                        $exp = explode('-', $router['action'], 2);
                        $actionName = $exp[0] . ucfirst($exp[1]);
                    } else {
                        $actionName = $router['action'];
                    }

                    $action = $actionName . "Action";
                    
                    return $controller->$action();
                }
            });
        }
    }

    public function setProjectUrl($projectUrl)
    {
        $this->project_url = $projectUrl;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function getDb()
    {
        $server = "127.0.0.1";
        $db     = "";
        $user   = "";
        $pass   = ""; 
        $port   = "";

        $db = new \PDO(
            "mysql:host={$server};port={$port};dbname={$db}", $user, $pass
        );

        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");   

        return $db;
    }
}
*/