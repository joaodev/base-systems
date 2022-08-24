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

    public function find($id, $stringFields, $idField, $view = null)
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
                WHERE {$idField} = :id
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":id", $id);
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
                WHERE deleted=:deleted
                ORDER BY id DESC
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

    public function findAllActives($stringFields)
    {
        try {
            $query = "
                SELECT {$stringFields}
                FROM {$this->getTable()} 
                WHERE deleted=:deleted
                AND status=:status
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

    public function fieldExists($field, $value, $idField, $id = null)
    {   
        try {
            if (!empty($id)) {
                $where = " AND {$idField} != {$id} ";
            } else { $where = ""; }

            $query = "
                SELECT {$idField} FROM {$this->getTable()} 
                WHERE {$field}=:value {$where}";
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

    public function getIdByField($field, $value, $idField) {
        try {
            $query = "
                SELECT {$idField} FROM {$this->getTable()} 
                WHERE {$field}=:value";
            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":value", $value);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(\PDO::FETCH_ASSOC);
                $result = $data[$idField];
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
}