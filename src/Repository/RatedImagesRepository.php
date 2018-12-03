<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

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