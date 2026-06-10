<?php

namespace Repository;

use PDO;




class AdminRepository
{
    private PDO $conn;
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }


}