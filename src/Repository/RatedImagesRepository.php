<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\RatedImages;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;

class RatedImagesRepository extends EntityRepository {

    public function insertVote($imageId, $userId) {
        $query = "INSERT INTO rated_images (image_id, user_id) VALUES ('$imageId','$userId') ";
        $stmt = $this
            ->getEntityManager()
            ->getConnection()
            ->prepare($query);
        $stmt->execute();
    }

}