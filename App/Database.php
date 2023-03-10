<?php

// Class and its methods are discussed in the README.md

class Database
{
    private $conn;

    public function __construct()
    {
        $host = "127.0.0.1";
        $username = "root";
        $password = "1234";
        $database = "venture_labs_db";

        // Create connection
        $this->conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getInstance() {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}