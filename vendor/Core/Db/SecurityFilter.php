<?php

namespace Core\Db;

use Core\Db\Logs;

class SecurityFilter
{
	private static function getMaliciousLabels()
    {
        return [
            "''", '""', '"', "'", ";", "*", 'SELECT', 'SET', 'LIKE', 'ADD', 
            'UNION', 'INSERT', 'DELETE', '%', '--', 'execut', 'DROP', 'EXECUTE', 
            'JOIN'
        ];
    }

    private static function splitOutput($output)
    {
        return preg_split('/ /', $output , -1, PREG_SPLIT_OFFSET_CAPTURE);
    }

    private function registerAttack($attack)
    {
        $log = new Logs();
        return $log->toLog('REQUISIÇÃO BLOQUEADA: ' . base64_encode($attack));
    }

    public function secureData($output)
    {
        if (!empty($output) && !is_array($output)) {
            if (
                $output == strip_tags($output) && 
                filter_var($output, FILTER_UNSAFE_RAW) 
            ) {
                $split_names = self::splitOutput($output);
                $malicious 	 = self::getMaliciousLabels();

                foreach ($split_names as $key => $split_name) {
                    if (!empty($split_name[0])) {
                        if (in_array($split_name[0], $malicious)) {
                            $attack = htmlentities($output);
                            $this->registerAttack($attack);
                            return false;
                        }
                    }
                }    

                return true;
            } else {
                $attack = htmlentities($output);
                $this->registerAttack($attack); 
                
                return false;
            }
        } else {
            return true;
        }
    }
}