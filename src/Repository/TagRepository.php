<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository {


    public function selectLimitedImagesByTag($tag) {
        $results =  $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Tag::class, 't')
            ->innerJoin('t.images','i')
            ->where('t.name = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();

        return $results;
    }


}