<?php

namespace writerblog\DAO;

use writerblog\Domain\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserDAO extends DAO implements UserProviderInterface {

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

    public function save(User $user) {
        $userData = array(
            'user_name' => $user->getUsername(),
            'user_password' => $user->getPassword(),
            'user_salt' => $user->getSalt(),
            'user_role' => $user->getRole(),
        );
        if ($user->getId()) {
            $this->getDb()->update('t_user', $userData, array('user_id' => $user->getId()));
        } else {
            $this->getDb()->insert('t_user', $userData);
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    public function delete($id) {
        $this->getDb()->delete('t_user', array('user_id' => $id));
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "select * from t_user where user_name = ?";
        $result = $this->getDb()->fetchAssoc($sql, array($username));

        if ($result)
            return $this->buildDomainObject($result);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'writerblog\Domain\User' === $class;
    }
}