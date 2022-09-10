<?php

namespace App\Model;

use Core\Db\Model;
use Core\Db\Bcrypt;

class User extends Model
{
    public function __construct()
    {
        $this->setTable('user');
    }

    public function getOne($uuid)
    {
        try {
            $query = "
                SELECT u.uuid, u.name, u.email, u.status, u.role_uuid, u.whatsapp,
                        u.cellphone, u.job_role, r.name as role, u.phone,
                        r.is_admin, u.created_at, u.updated_at, u.file,
                        u.postal_code, u.address, u.number, u.complement, 
                        u.neighborhood, u.city, u.state, u.document_1, u.document_2 
                FROM {$this->getTable()} AS u
                INNER JOIN role AS r
                    ON u.role_uuid = r.uuid
                WHERE u.uuid = :uuid AND u.deleted = '0'
                ORDER BY u.name ASC
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
                SELECT u.uuid, u.name, u.email, u.status, u.file, u.phone, u.whatsapp,
                        u.cellphone, u.job_role, r.name as role, u.created_at, u.updated_at,
                        u.postal_code, u.address, u.number, u.complement, 
                        u.neighborhood, u.city, u.state, u.document_1, u.document_2
                FROM {$this->getTable()} AS u
                INNER JOIN role AS r
                    ON u.role_uuid = r.uuid
                WHERE u.deleted = '0'
                ORDER BY u.name ASC
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

    public function totalUsers()
    {
        try {
            $query = "
                SELECT uuid
                FROM {$this->getTable()} 
                WHERE deleted = '0'
            ";

            $stmt = $this->openDb()->query($query);
            $result = $stmt->rowCount();

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
  
    public function getAllByRoleUuid($uuid)
    {
        try {
            $query = "
                SELECT u.uuid, u.name, u.email, u.status, u.file,  u.phone,
                        u.cellphone, u.job_role, r.name as role, u.created_at
                FROM {$this->getTable()} AS u
                INNER JOIN role AS r
                    ON u.role_uuid = r.uuid
                WHERE u.role_uuid = :role_uuid AND u.deleted = '0'
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":role_uuid", $uuid);
            $stmt->execute();

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
  
    public function authByCrenditials($email, $password)
    {
    	try {
            if (!empty($email) && !empty($password)) {
                $query = "
                    SELECT u.uuid, u.name, u.email, u.password, u.file,
                            r.name as role, u.role_uuid, r.is_admin
                    FROM {$this->getTable()} AS u
                    INNER JOIN role AS r
                        ON u.role_uuid = r.uuid
                    WHERE u.email=:email AND u.password=:password
                        AND u.status = :status
                        AND u.deleted = :deleted
                ";

                $stmt = $this->openDb()->prepare($query);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":password", $password);
                $stmt->bindValue(":status", '1');
                $stmt->bindValue(":deleted", '0');
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $result = $stmt->fetch(\PDO::FETCH_ASSOC);
                } else {
                    $result = false;
                }

                $stmt = null;
                $this->closeDb();

                return $result;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function findByCrenditials($email, $password)
    {
        if (!empty($email) && !empty($password)) {
            try {
                $query = "
                    SELECT u.uuid, u.name, u.email, u.password, u.file,
                            r.name as role, u.role_uuid, r.is_admin
                    FROM {$this->getTable()} AS u
                    INNER JOIN role AS r
                        ON u.role_uuid = r.uuid
                    WHERE u.email=:email
                        AND u.status = :status
                        AND u.deleted = :deleted
                ";

                $stmt = $this->openDb()->prepare($query);
                $stmt->bindValue(":email", $email);
                $stmt->bindValue(":status", '1');
                $stmt->bindValue(":deleted", '0');
                $stmt->execute();
              
                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                    if (Bcrypt::check($password, $user['password'])) {
                        $data = $user;
                    } else {
                        $data = false;
                    }
                } else {
                    $data = false;
                }

                $stmt = null;
                $this->closeDb();

                return $data;
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        } else {
            return false;
        }
    }
    
    public function checkDeletePermission($roleUuid)
    {
        try {
            $query = "SELECT role_uuid 
                        FROM {$this->getTable()}
                        WHERE role_uuid = :role_uuid AND deleted = :deleted";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":role_uuid", $roleUuid);
            $stmt->bindValue(":deleted", '0');
            $stmt->execute();

            $results = $stmt->rowCount();   
            
            $stmt = null;
            $this->closeDb();

            if ($results > 0) {
                return false;
            } else {
                return true;
            }            
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}