<?php

namespace App\Model;

use Core\Db\Model;

class Acl extends Model
{
    public function __construct()
    {
        $this->setTable('acl');
    }

    public function getGrantedPrivilege($userId, $roleId, $resourceId, $moduleId) 
    {
        try {
            $query = "SELECT a.id, r.is_admin
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p  
                            ON a.privilege_id = p.id
                        INNER JOIN role AS r 
                            ON p.role_id = r.id
                        WHERE a.user_id = :user_id AND p.status = :pstatus AND a.status = :status
                        AND p.role_id = :role_id AND p.resource_id = :resource_id
                        AND p.module_id = :module_id";

                $stmt = $this->openDb()->prepare($query);
                $stmt->bindValue(":user_id", $userId);
                $stmt->bindValue(":pstatus", '1');
                $stmt->bindValue(":status", '1');
                $stmt->bindValue(":role_id", $roleId);
                $stmt->bindValue(":resource_id", $resourceId);
                $stmt->bindValue(":module_id", $moduleId);
                $stmt->execute();

                $rowCount = $stmt->rowCount();

                $stmt = null;
                $this->closeDb();

                if ($rowCount > 0) {
                    return true;
                } else {
                    return false;
                }
         
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getUserPrivileges($userId)
    {
        try {
            $query = "SELECT a.id, a.privilege_id, a.status,
                             p.id as pv, r.name as resourceName,
                             m.name as moduleName
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p  
                            ON a.privilege_id = p.id
                        INNER JOIN resource AS r
                            ON p.resource_id = r.id
                        INNER JOIN modules AS m
                            ON p.module_id = m.id
                        WHERE a.user_id = :user_id
                            AND p.status = :status";
            
            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":user_id", $userId);
            $stmt->bindValue(":status", '1');
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $results;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkDeletePermission($privilegeId)
    {
        try {
            $query = "SELECT a.status 
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p 
                            ON a.privilege_id = p.id
                        WHERE a.privilege_id = :privilege_id
                            AND a.status = :status";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":privilege_id", $privilegeId);
            $stmt->bindValue(":status", '1');
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