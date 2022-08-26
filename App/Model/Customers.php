<?php

namespace App\Model;

use Core\Db\Model;

class Customers extends Model
{
    public function __construct()
    {
        $this->setTable('customers');
    }

    public function getOne($uuid)
    {
        try {
            $query = "
                SELECT c.uuid, c.name, c.email, c.phone, c.cellphone, 
                    c.document_1, c.document_2, c.whatsapp, 
                    c.postal_code, c.address, c.number, c.complement, 
                    c.neighborhood, c.city, c.state, 
                    c.status, c.created_at, c.updated_at,
                    u.name as username
                FROM {$this->getTable()} AS c
                INNER JOIN user AS u    
                    ON c.user_uuid = u.uuid 
                WHERE c.uuid = :uuid AND c.deleted = :deleted
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":uuid", $uuid);
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
                SELECT uuid, name, email, phone, cellphone, 
                    document_1, document_2, whatsapp, 
                    postal_code, address, number, complement, 
                    neighborhood, city, state, 
                    status, created_at, updated_at, user_uuid
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
                SELECT uuid
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