<?php

namespace App\Model;

use Core\Db\Model;

class Logs extends Model
{
    public function __construct()
    {
        $this->setTable('logs');
    }

    public function getAll()
    {
        try {
            $query = "
                 SELECT l.id, l.log_user_id, l.log_action, l.log_date, 
                        l.log_ip, l.log_user_agent, l.log_status, 
                        u.name as username
                FROM {$this->getTable()} AS l
                    INNER JOIN user AS u 
                        ON l.log_user_id = u.id
                ORDER BY l.log_date DESC";

            $stmt = $this->openDb()->prepare($query);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}