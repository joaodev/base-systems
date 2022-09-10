<?php

namespace App\Model;

use Core\Db\Model;

class Files extends Model
{
    public function __construct()
    {
        $this->setTable('files');
    }
}