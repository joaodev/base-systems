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
                SELECT c.id, c.name, c.email, c.phone, c.cellphone, 
                    c.customer_type, c.document_1, c.document_2,
                    c.postal_code, c.address, c.number, c.complement, 
                    c.neighborhood, c.city, c.state, 
                    c.status, c.created_at, c.updated_at,
                    u.name as username
                FROM {$this->getTable()} AS c
                INNER JOIN user AS u    
                    ON c.user_id = u.id 
                WHERE c.id = :id AND c.deleted = :deleted
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

    public function totalCustomers()
    {
        try {
            $query = " 
                SELECT id
                FROM {$this->getTable()}
                WHERE deleted = :deleted
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":deleted", '0');
            $stmt->execute();

            $result = $stmt->rowCount();

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}