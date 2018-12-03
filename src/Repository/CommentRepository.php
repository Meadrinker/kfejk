<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository {

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