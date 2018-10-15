<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;

class ImageRepository extends EntityRepository {


    public function selectLimitedImages($limit, $offset) {
        $results =  $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function countImages() {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(i.id)')
            ->from(Image::class, 'i')
            ->getQuery()
            ->getResult();

        return $count;
    }

    public function selectId() {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i.id')
            ->from(Image::class, 'i')
            ->getQuery()
            ->getResult();

        return $count;
    }

    public function randomImageId(int $imageId = null) {
        $query = 'select id from images order by rand() limit 1';
        if ($imageId !== null) {
            $query = 'select id from images where id <> ' . $imageId . ' order by rand() limit 1';
        }
        $stmt = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }

    public function ratePlus(int $imageId) {
        $query = "UPDATE images SET rating_plus = rating_plus+1 WHERE id = '$imageId'";
        $stmt = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare($query);
        $stmt->execute();
    }

    public function rateMinus(int $imageId) {
        $query = "UPDATE images SET rating_minus = rating_minus+1 WHERE id = '$imageId'";
        $stmt = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare($query);
        $stmt->execute();
    }

}