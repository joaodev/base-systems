<?php

namespace App\Model;

use Core\Db\Model;

class Acl extends Model
{
    public function __construct()
    {
        $this->setTable('acl');
    }

    public function getGrantedPrivilege($userUuid, $roleUuid, $resourceUuid, $moduleUuid) 
    {
        try {
            $query = "SELECT a.uuid, r.is_admin
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p  
                            ON a.privilege_uuid = p.uuid
                        INNER JOIN role AS r 
                            ON p.role_uuid = r.uuid
                        WHERE a.user_uuid = :user_uuid AND p.status = :pstatus AND a.status = :status
                        AND p.role_uuid = :role_uuid AND p.resource_uuid = :resource_uuid
                        AND p.module_uuid = :module_uuid";

                $stmt = $this->openDb()->prepare($query);
                $stmt->bindValue(":user_uuid", $userUuid);
                $stmt->bindValue(":pstatus", '1');
                $stmt->bindValue(":status", '1');
                $stmt->bindValue(":role_uuid", $roleUuid);
                $stmt->bindValue(":resource_uuid", $resourceUuid);
                $stmt->bindValue(":module_uuid", $moduleUuid);
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

    public function getUserPrivileges($userUuid)
    {
        try {
            $query = "SELECT a.uuid, a.privilege_uuid, a.status,
                             p.uuid as pv, r.name as resourceName,
                             m.name as moduleName
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p  
                            ON a.privilege_uuid = p.uuid
                        INNER JOIN resource AS r
                            ON p.resource_uuid = r.uuid
                        INNER JOIN modules AS m
                            ON p.module_uuid = m.uuid
                        WHERE a.user_uuid = :user_uuid
                            AND p.status = :status";
            
            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":user_uuid", $userUuid);
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

    public function checkDeletePermission($privilegeUuid)
    {
        try {
            $query = "SELECT a.status 
                        FROM {$this->getTable()} AS a
                        INNER JOIN privilege AS p 
                            ON a.privilege_uuid = p.uuid
                        WHERE a.privilege_uuid = :privilege_uuid
                            AND a.status = :status";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":privilege_uuid", $privilegeUuid);
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