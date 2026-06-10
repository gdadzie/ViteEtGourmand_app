<?php

namespace Repository;

use PDO;

class EmployesRepository
{
    private PDO $conn;
    public function __construct(PDO $conn){
        $this->conn = $conn;
    }




}