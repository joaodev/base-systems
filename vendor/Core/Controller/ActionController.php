<?php

namespace Core\Controller;

use Core\Db\SecurityFilter;
use Core\Db\Logs;
use Core\Di\Container;

class ActionController
{
    protected $action;
    protected $namespace;
    protected $controller;
    protected $view;
    
    public function __construct()
    {
        if (!empty($_GET)) {
            self::dataValidation($_GET);
        }

        $this->view = new \stdClass();
    }
        
    public function indexAction()
    {
        return $this->render('index', false);
    }

    public function render($action, $layout = true)
    {
        $this->action = $action;

        $current = get_class($this);
        $exp_current_class = explode("\\", $current, 3);

        $this->namespace = $exp_current_class[0];
        $this->controller = strtolower($exp_current_class[2]);

        if ($layout == true && file_exists("../" . $this->namespace . "/view/layout/layout.phtml")) {
            include_once ("../" . $this->namespace . "/view/layout/layout.phtml");
        } else {
            $this->content();
        }
    }

    public function content()
    {
        $class_name = str_replace("controller", "", $this->controller);
        include_once ('../' . $this->namespace . '/view/' . $class_name . '/' . $this->action . '.phtml');
    }

    public static function redirect($module, $msgCallback = null)
    {
        $callback = "";

        if (!empty($msgCallback)) {
            $callback .= "?msg=" . $msgCallback;
        }

        return header("Location: " . baseUrl . $module . $callback);
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }
    
    public function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randstring;
    }

    public function formatDate($input_date)
    {
        if (!empty($input_date)) {
            $exp  = explode("-", $input_date, 3);
            $date = $exp[2] . '/' . $exp[1] . '/' . $exp[0];
            return $date;
        } else {
            return "";
        }
    }

    public function formatDateTime($input_date_time, $time = true)
    {
        if (empty($input_date_time)) {
            return $input_date_time;
        } else {
            $exp_dt   = explode(" ", $input_date_time, 2);
            $exp_date = explode("-", $exp_dt[0], 3);
            $date = $exp_date[2] . '/' . $exp_date[1] . '/' . $exp_date[0];

            if ($time == true) {
                $exp_time = explode(":", $exp_dt[1], 3);
                return $date . ' ' . $exp_time[0] . ':' . $exp_time[1];
            } else {
                return $date;
            }
        }
    }

    public function formatDateTimeWithLabels($input_date_time, $time = true)
    {
        if (empty($input_date_time)) {
            return $input_date_time;
        } else {
            $exp_dt   = explode(" ", $input_date_time, 2);
            $exp_date = explode("-", $exp_dt[0], 3);
            $date = $exp_date[2] . '/' . $exp_date[1] . '/' . $exp_date[0];

            if ($time == true) {
                $exp_time = explode(":", $exp_dt[1], 3);
                return $date . ' às ' . $exp_time[0] . ':' . $exp_time[1];
            } else {
                return $date;
            }
        }
    }

    public function moneyToDb($value) 
    {
        $value = trim($value);
        $value = str_replace(".","",$value);
        $value = str_replace(",",".",$value);
        return $value;
    }

    public static function dataValidation($data)
    {
        $securityFilter = new SecurityFilter();
        foreach ($data as $item) {
            if (!$securityFilter->secureData($item)) {
                session_destroy();
                return self::redirect('');
            }
        }
    }

    public function toLog($msg) 
    {
        $log = new Logs();
        return $log->toLog($msg);
    }

    public function inputMasked($val, $mask)
    {
        $maskared = '';
        $k = 0;
        
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }

        return $maskared;
    }

    public function dateToTimestamp($data){
        $ano = substr($data, 6,4);
        $mes = substr($data, 3,2);
        $dia = substr($data, 0,2);
        return mktime(0, 0, 0, $mes, $dia, $ano);  
    }

    public function acl($roleId, $resourceId, $moduleId)
    {
        $aclModel = Container::getClass("Acl", "app");
        return $aclModel->getGrantedPrivilege($_SESSION['COD'], $roleId, $resourceId, $moduleId);
    }

    public function getSiteConfig()
    {
        $configModel = Container::getClass("Config", "app");
        $configData  = $configModel->getOne();

        return $configData;
    }

    public function getUser($id)
    {
        $userModel = Container::getClass("PreCandidate", "app");
        $userData  = $userModel->getOne($id);

        return $userData;
    }

    public function slugGenerator($title)
    {
        $slug = preg_replace('/[0-9\@\.\;\"]+/', '', $title);
        $slug = str_replace(" ", "-", $slug);
        $slug = str_replace("&", "", $slug);
        $slug = str_replace("ç", "c", $slug);
        $slug = str_replace("Ç", "c", $slug);
        $slug = str_replace("/", "", $slug);
        $slug = strtolower($slug);

        $slug = preg_replace(
            array("/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            explode(" ","a A e E i I o O u U n N"),
            $slug
        );

        return $slug;
    }

    public function formatCellphone($cellphone)
    {
        if (!empty($cellphone)) {
            
            $cellphone = str_replace('(', '', $cellphone);
            $cellphone = str_replace(')', '', $cellphone);
            $cellphone = str_replace('-', '', $cellphone);

            return '55'.trim($cellphone);
        } else {
            return null;
        }
    }
}