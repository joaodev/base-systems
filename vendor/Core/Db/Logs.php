<?php

namespace Core\Db;

use Core\Db\Crud;

class Logs extends InitDb
{
	public function toLog($msg)
	{
		try {
			$dataPost = [
				'log_user_id' => (!empty($_SESSION['COD']) ? $_SESSION['COD'] : 0),
				'log_action' => $msg,
				'log_date' => date('Y-m-d H:i:s'),
				'log_ip' => self::getUserIp(),
				'log_user_agent' => $_SERVER["HTTP_USER_AGENT"], 
				'log_status' => http_response_code()
			];

			$crud = New Crud();
			$crud->setTable('logs');
			
			return $crud->create($dataPost);
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
	
    private static function getUserIp()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}