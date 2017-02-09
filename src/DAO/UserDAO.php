<?php

namespace writerblog\DAO;

use writerblog\Domain\User;

class UserDAO extends DAO {

    public function readAll() {
        $sql = "select * from t_user order by user_role, user_name";
        $result = $this->getDb()->fetchAll($sql);
        $dataUser = array();
        foreach ($result as $row) {
            $userId = $row['user_id'];
            $dataUser[$userId] = $this->buildDomainObject($row);
        }
        return $dataUser;
    }

    public function read($id) {
        $sql = "select * from t_user where user_id = ?";
        $result = $this->getDb()->fetchAssoc($sql, array($id));
        if ($result) {
            return $this->buildDomainObject($result);
        } else {
            throw new \Exception ("No user found matching id " . $id);
        }
    }

    public function buildDomainObject($row) {
        $user = new User();
        $user->setId($row['user_id']);
        $user->setUsername($row['user_name']);
        $user->setPassword($row['user_password']);
        $user->setSalt($row['user_salt']);
        $user->setRole($row['user_role']);        
        return $user;
    }
}