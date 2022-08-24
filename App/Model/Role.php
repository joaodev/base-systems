<?php

namespace App\Model;

use Core\Db\Model;

class Role extends Model
{
    public function __construct()
    {
        $this->setTable('role');
    }

    public function getOne($id)
    {
        try {
            $query = "
                SELECT id, name, is_admin, created_at
                FROM {$this->getTable()}
                WHERE id = :id AND deleted = :deleted
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":id", $id);
            $stmt->bindValue(":deleted", '0');
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
                SELECT id, name, is_admin
                FROM {$this->getTable()}
                WHERE deleted = :deleted
                ORDER BY name ASC
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":deleted", '0');
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