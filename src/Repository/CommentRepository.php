<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class CommentRepository extends EntityRepository {

    public function sortComments($column, $dir, $sort, $limit, $offset) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('c')
            ->from(Comment::class, 'c')
            ->innerJoin(User::class ,'u', Join::WITH,'c.user = u.id')
            ->innerJoin(Image::class ,'i', Join::WITH,'c.image = i.id');

        if (!empty($sort)) {
            $queryBuilder
                ->where('u.username LIKE :sort')
                ->orWhere('c.text LIKE :sort')
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

    public function filteredComments($sort) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(Distinct c.id)')
            ->from(Comment::class, 'c')
            ->innerJoin(User::class ,'u', Join::WITH,'c.user = u.id')
            ->innerJoin(Image::class ,'i', Join::WITH,'c.image = i.id');

        if (!empty($sort)) {
            $queryBuilder
                ->where('u.username LIKE :sort')
                ->orWhere('c.text LIKE :sort')
                ->setParameter('sort', '%'.$sort.'%');
        }

        $results = $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();

        return $results;
    }

    public function countComments() {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(c.id)')
            ->from(Comment::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();
        return $count;
    }

    public function deleteComment($id) {
        $results = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete(Comment::class, 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $results;
    }
}