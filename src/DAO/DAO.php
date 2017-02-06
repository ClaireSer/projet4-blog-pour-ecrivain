<?php

abstract class DAO {
    private $db;

    public function getDb() {
        return $this->db;
    }

    protected abstract function buildDomainObject($row);

}