<?php

namespace Core\Db;

class Crud extends InitDb
{
    public function create($dataPost)
    {
        try {
            $fields = [];
            $values = [];

            foreach ($dataPost as $field => $value) {
                $fields[] = $field;
                $values[] = $value;
            }

            $f = implode(",", $fields);
            $v = implode("','", $values);
            $v = "'" . $v . "'";

            $query = "INSERT INTO {$this->getTable()} ({$f}) VALUES ({$v})";
            $stmt = $this->openDb()->prepare($query);
            $stmt->execute();
            
            $lastId = $this->db->lastInsertId();
            $stmt = null; 
            $this->closeDb();

            return $lastId;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function update($data, $id, $idField, $secondParams = null)
    {
        try {
            $otherParam = "";
            if (!empty($secondParams['field']) && $secondParams['value']) {
                if ((int) $secondParams['value']) {
                    $otherParam = " AND {$secondParams['field']} = {$secondParams['value']}";
                } else {
                    $otherParam = " AND {$secondParams['field']} = '{$secondParams['value']}'";
                }
            }

            $query = "UPDATE {$this->getTable()} SET ";
            $totalItens = count($data);
            $counter = 0;

            foreach ($data as $label => $val) {
                $counter++;
                $comma = ($counter < $totalItens) ? ", " : "";
                $key = $label . "=:" . $label . $comma;
                $query .= $key;
            }

            $query .= " WHERE {$idField} =:id {$otherParam}";
            $stmt = $this->openDb()->prepare($query);

            foreach ($data as $column => $value) {
                $stmt->bindValue(":" . $column, $value);
            }
            $stmt->bindValue(":id", $id);
            $update = $stmt->execute();

            $stmt = null; 
            $this->closeDb();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function delete($id, $idField)
    {
        try {
            $query = "
                DELETE FROM {$this->getTable()}  
                WHERE {$idField} = :id
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":id", $id);
            $stmt->execute();

            $stmt = null; 
            $this->closeDb();

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}