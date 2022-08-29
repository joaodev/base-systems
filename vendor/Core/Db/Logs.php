<?php

namespace Core\Db;

use Core\Db\Crud;
use Core\Db\Model;

class Logs extends InitDb
{
	public function toLog($msg)
	{
		try {
			$model = new Model();
			$dataPost = [
				'uuid' => $model->NewUUID(),
				'log_user_uuid' => (!empty($_SESSION['COD']) ? $_SESSION['COD'] : ''),
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
		$ipAddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipAddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipAddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipAddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipAddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipAddress = 'UNKNOWN';
			
		return $ipAddress;
    }
}