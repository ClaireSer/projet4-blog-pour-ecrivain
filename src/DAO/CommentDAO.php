<?php

namespace writerblog\DAO;

use writerblog\Domain\Comment;
use writerblog\DAO\BilletDAO;
use writerblog\DAO\UserDAO;

class CommentDAO extends DAO {
    
    private $billetDAO;
    private $userDAO;
    
    public function setBilletDAO(BilletDAO $billetDAO) {
        $this->billetDAO = $billetDAO;
        return $this;
    }
    public function setUserDAO(UserDAO $userDAO) {
        $this->userDAO = $userDAO;
        return $this;
    }


    public function readAllByIdBillet($idBillet) {
        $sql = "select * from t_comment where billet_id = ? order by com_id desc";
        $result = $this->getDb()->fetchAll($sql, array($idBillet));
        $dataComment = array();
        foreach ($result as $row) {
            $commentId = $row['com_id'];
            $dataComment[$commentId] = $this->buildDomainObject($row);
        }
        return $dataComment;
    }

    public function buildDomainObject($row) {
        $comment = new Comment();
        $comment->setId($row['com_id']);
        $comment->setContent($row['com_content']);
        if (array_key_exists('billet_id', $row)) {
            $idBillet = $row['billet_id'];
            $billet = $this->billetDAO->read($idBillet);
            $comment->setBillet($billet);
        }
        if (array_key_exists('user_id', $row)) {
            $userId = $row['user_id'];
            $author = $this->userDAO->read($userId);
            $comment->setAuthor($author);
        }
        return $comment;
    }
}
