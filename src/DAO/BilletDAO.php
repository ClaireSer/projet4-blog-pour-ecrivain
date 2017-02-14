<?php

namespace writerblog\DAO;

use writerblog\Domain\Billet;

class BilletDAO extends DAO {

    public function create(Billet $billet) {
        $billetArray = array(
            'billet_title' => $billet->getTitle(),
            'billet_content' => $billet->getContent(),
            'billet_dateAjout' => $billet->getDateAjout(),
            'billet_nbComments' => $billet->getNbComments()
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

    public function update(Billet $billet) {
        $billetArray = array(
            'billet_title' => $billet->getTitle(),
            'billet_content' => $billet->getContent(),
            'billet_dateAjout' => $billet->getDateAjout(),
            'billet_dateModif' => $billet->getDateModif(),
            'billet_nbComments' => $billet->getNbComments()
        );
        $this->getDb()->update('t_billet', $billetArray, array('billet_id' => $billet->getId()));
    }
    
    public function deleteBillet($id) {
        $this->getDb()->delete('t_billet', array('billet_id' => $id));
    }
    
    public function buildDomainObject($row) {
        $billet = new Billet();
        $billet->setId($row['billet_id']);
        $billet->setTitle($row['billet_title']);
        $billet->setContent($row['billet_content']);
        $billet->setDateAjout($row['billet_dateAjout']);
        $billet->setDateModif($row['billet_dateModif']);
        $billet->setNbComments($row['billet_nbComments']);
        return $billet;
    }

    public function countComments($idBillet) {
        $sql = 'select * from t_comment where billet_id = ?';
        return $this->getDb()->executeQuery($sql, array($idBillet))->rowCount();
    }   
}