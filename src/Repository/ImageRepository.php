<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

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

    public function selectLimitedImagesByTag($tag, $limit, $offset) {
        $results =  $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->innerJoin('i.tags', 't')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('i.id', 'DESC')
            ->where('t.name = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();

        return $results;
    }



    public function sortImages($column, $dir, $sort, $limit, $offset) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->innerJoin('i.tags', 't');

            if (!empty($sort)) {
                $queryBuilder
                    ->where('i.title LIKE :sort')
                        ->orWhere('u.username LIKE :sort')
                        ->orWhere('t.name LIKE :sort')
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

    public function filteredImages($sort) {
        $queryBuilder = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(Distinct i.id)')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->innerJoin('i.tags', 't');

        if (!empty($sort)) {
            $queryBuilder
                ->where('i.title LIKE :sort')
                ->orWhere('u.username LIKE :sort')
                ->orWhere('t.name LIKE :sort')
                ->setParameter('sort', '%'.$sort.'%');
        }

        $results = $queryBuilder
            ->getQuery()
            ->getSingleScalarResult();

        return $results;
    }

    public function selectLimitedImagesByUser($user, $limit, $offset) {
        $results =  $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('i.id', 'DESC')
            ->where('i.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $results;
    }

    public function selectLimitedImagesByAccept($accepted, $limit, $offset) {
        $results =  $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('i')
            ->from(Image::class, 'i')
            ->innerJoin(User::class ,'u', Join::WITH,'i.author = u.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('i.id', 'DESC')
            ->where('i.accepted = :accepted')
            ->setParameter('accepted', $accepted)
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
            ->getSingleScalarResult();
        return $count;
    }

    public function countImagesByTag($tag) {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(i.id)')
            ->from(Image::class, 'i')
            ->innerJoin('i.tags', 't')
            ->where('t.name = :tag')
            ->setParameter('tag', $tag)
            ->getQuery()
            ->getResult();

        return $count;
    }

    public function countImagesByUser($user) {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(i.id)')
            ->from(Image::class, 'i')
            ->where('i.author = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $count;
    }

    public function countImagesByAccept($accepted) {
        $count = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('count(i.id)')
            ->from(Image::class, 'i')
            ->where('i.accepted = :accepted')
            ->setParameter('accepted', $accepted)
            ->getQuery()
            ->getResult();

        return $count;
    }

    public function deleteImage($id) {
        $results = $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->delete(Image::class, 'i')
            ->where('i.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();

        return $results;
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