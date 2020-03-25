<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\Query\Expr\Join;

class UserRepository extends EntityRepository implements UserLoaderInterface {

    public function loadUserByUsername($username) {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deleteUser($id) {
        $results = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete(User::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function countUsers() {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(u.id)')
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();
        return $count;
    }

    public function sortUsers($column, $dir, $sort, $limit, $offset) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->innerJoin(Role::class ,'r', Join::WITH,'u.role = r.id');

        if (!empty($sort)) {
            $queryBuilder
                ->where('u.username LIKE :sort')
                ->orWhere('u.email LIKE :sort')
                ->setParameter('sort', '%'.$sort.'%');
        }

        $results = $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy($column, $dir)
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function filteredUsers($sort) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(Distinct u.id)')
            ->from(User::class, 'u')
            ->innerJoin(Role::class ,'r', Join::WITH,'u.role = r.id');

        if (!empty($sort)) {
            $queryBuilder
                ->where('u.username LIKE :sort')
                ->orWhere('u.email LIKE :sort')
                ->setParameter('sort', '%'.$sort.'%');
        }

        $results = $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();

        return $results;
    }
}