<?php

namespace Core\Db;

class Model extends InitDb
{
    public function getTable()
    {
        try {
            return $this->table;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function setTable($table)
    {
        try {
            $this->table = $table;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function find($uuid, $stringFields, $uuidField, $view = null)
    {
        try {
            if (!empty($view)) {
                $table = $view;
            } else {
                $table = $this->getTable();
            }   

            $query = "
                SELECT {$stringFields}
                FROM {$table} 
                WHERE {$uuidField} = :uuid
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

    
    public function findActive($uuid, $stringFields, $uuidField, $view = null)
    {
        try {
            if (!empty($view)) {
                $table = $view;
            } else {
                $table = $this->getTable();
            }   

            $query = "
                SELECT {$stringFields}
                FROM {$table} 
                WHERE {$uuidField} = :uuid
                AND deleted = :deleted
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

    public function findAll($stringFields)
    {
        try {
            $query = "
                SELECT {$stringFields}
                FROM {$this->getTable()} 
                WHERE deleted = :deleted
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

    public function findAllBy($stringFields, $whereField, $whereValue)
    {
        try {
            $query = "
                SELECT {$stringFields}
                FROM {$this->getTable()} 
                WHERE 
                    {$whereField} = :whereValue
                    AND deleted = :deleted
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":whereValue", $whereValue);
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

    public function findAllActives($stringFields)
    {
        try {
            $query = "
                SELECT {$stringFields}
                FROM {$this->getTable()} 
                WHERE deleted = :deleted
                AND status = :status
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":deleted", '0');
            $stmt->bindValue(":status", '1');
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function fieldExists($field, $value, $uuidField, $uuid = null)
    {   
        try {
            if (!empty($uuid)) {
                $where = " AND {$uuidField} != '{$uuid}' ";
            } else { $where = ""; }

            $query = "
                SELECT {$uuidField} FROM {$this->getTable()} 
                WHERE {$field} = :value {$where}";
            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":value", $value);
            $stmt->execute();

            if ($stmt->rowCount() >= 1) {
                $result = true;
            } else {
                $result = false;
            }

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUuidByField($field, $value, $uuidField) {
        try {
            $query = "
                SELECT {$uuidField} FROM {$this->getTable()} 
                WHERE {$field} = :value";
            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":value", $value);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(\PDO::FETCH_ASSOC);
                $result = $data[$uuidField];
            } else {
                $result = 0;
            }

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function UuidGenerator($lenght = 10) {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        
        return substr(bin2hex($bytes), 0, $lenght);
    }

    public function NewUUID()
    {
        return self::UuidGenerator(8) . '-' 
                . self::UuidGenerator(4) . '-'
                . self::UuidGenerator(4) . '-'
                . self::UuidGenerator(4) . '-'
                . self::UuidGenerator(12);
    } 
}