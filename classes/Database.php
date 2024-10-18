<?php

class Database
{
    /**
     * @var mysqli
     */
    private $db;

    public function __construct($connectionData)
    {
        $this->db = new mysqli(
            $connectionData['serverName'],
            $connectionData['username'],
            $connectionData['password'],
            $connectionData['database']
        );
        if ($this->db->connect_error) {
            die('Connection to the database failed: ' . $this->db->connect_error);
        }
    }

    public function getDb(): mysqli
    {
        return $this->db;
    }
}