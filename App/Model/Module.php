<?php

namespace App\Model;

use Core\Db\Model;

class Module extends Model
{
    public function __construct()
    {
        $this->setTable('modules');
    }

    public function getOne($uuid)
    {
        try {
            $query = "
                SELECT uuid, name, created_at
                FROM {$this->getTable()}
                WHERE uuid = :uuid
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":uuid", $uuid);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getAll()
    {
        try {
            $query = "
                SELECT uuid, name, view_uuid, 
                        create_uuid, update_uuid, delete_uuid
                FROM {$this->getTable()}
            ";

            $stmt = $this->openDb()->query($query);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}