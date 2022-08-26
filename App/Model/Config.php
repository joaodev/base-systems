<?php

namespace App\Model;

use Core\Db\Model;

class Config extends Model
{
    public function __construct()
    {
        $this->setTable('site_config');
    }

    public function getOne()
    {
        try {
            $query = "
                SELECT id, site_title, primary_color, secondary_color, email,phone,
                        cellphone, full_address, logo, logo_icon, mail_host, mail_port,
                        mail_username, mail_password, mail_from_address, mail_to_address, updated_at
                FROM {$this->getTable()}
                WHERE id = :id
            ";

            $stmt = $this->openDb()->prepare($query);
            $stmt->bindValue(":id", 1);
            $stmt->execute();

            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $stmt = null;
            $this->closeDb();

            return $result;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}