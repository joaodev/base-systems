<?php

namespace App\Model;

use Core\Db\Model;

class Customers extends Model
{
    public function __construct()
    {
        $this->setTable('customers');
    }

    public function getOne($id)
    {
        try {
            $query = "
                SELECT id, name, email, phone, cellphone, 
                    customer_type, document_1, document_2,
                    postal_code, address, number, complement, 
                    neighborhood, city, state, 
                    status, created_at, updated_at, user_id
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
                SELECT id, name, email, phone, cellphone, 
                    customer_type, document_1, document_2, 
                    postal_code, address, number, complement, 
                    neighborhood, city, state, 
                    status, created_at, updated_at, user_id
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