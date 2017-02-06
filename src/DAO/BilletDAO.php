<?php

class BilletDAO extends DAO {

    public function create(Billet $billet) {
        $billetArray = array(
            'billet_title' => $billet->getTitle(),
            'billet_content' => $billet->getContent()
        );
        $this->getDb()->insert('t_billet', $billetArray);
        $id = $this->getDb()->lastInsertId();
        $billet->setId($id);
    }

    public function readAll() {
        $sql = 'select * from t_billet order by billet_id desc';
        $result = $this->getDb()->fetchAll($sql);
        $billets = array();
        foreach ($result as $row) {
            $idBillet = $row['billet_id'];
            $billets[$idBillet] = $this->buildDomainObject($row);
        }
        return $billets;
    }

    public function read($id) {
        $sql = 'select * from t_billet where billet_id = ?';
        $result = $this->getDb()->fetchAssoc($sql, array($id));
        if ($result) {
            return $this->buildDomainObject($result);
        } else {
            throw new \Exception ("No billet matching id " . $id);
        }
    }

    public function update($id) {
        $billetArray = array(
            'billet_title' => $billet->getTitle(),
            'billet_content' => $billet->getContent()
        );
        $this->getDb()->update('t_billet', $billetArray, array('billet_id' => $id));
    }
    
    public function delete($id) {
        $this->getDb()->delete('t_billet', array('billet_id' => $id));
    }
    
    public function buildDomainObject($row) {
        $billet = new Billet();
        $billet->setTitle($row['billet_title']);
        $billet->setContent($row['billet_content']);
        $billet->setId($row['billet_id']);
        return $billet;
    }
}