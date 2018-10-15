<?php
/**
 * Created by PhpStorm.
 * User: dawid
 * Date: 05.10.18
 * Time: 12:36
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\RatedImages;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class AjaxController extends AbstractController {

    /**
     * @Route("/ajax/image/plus", name="ajax_image_plus")
     */
    public function addPlus(Request $request, UserInterface $user) {
        $imageId = $request->get('image_id');
        $userId = $user->getId();
//        $image = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
//        $ratedImage = new RatedImages();
//        $ratedImage->setUser($user);
//        $ratedImage->setImage($image);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $this->getDoctrine()->getRepository(RatedImages::class)->insertVote($imageId, $userId);
            $this->getDoctrine()->getRepository(Image::class)->ratePlus($imageId);
            $entityManager->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->getConnection()->rollBack();
            return new JsonResponse(array('status' => false));
        }
        $entityManager->clear(Image::class);
        $image = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
//        die($imageId);
        return new JsonResponse(array('status' => true, 'amount' => $image->getRatingPlus()));

    }

    /**
     * @Route("/ajax/image/minus", name="ajax_image_minus")
     */
    public function addMinus(Request $request, UserInterface $user) {
        $imageId = $request->get('image_id');
        $userId = $user->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();
        try {
            $this->getDoctrine()->getRepository(RatedImages::class)->insertVote($imageId, $userId);
            $this->getDoctrine()->getRepository(Image::class)->rateMinus($imageId);
            $entityManager->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->getConnection()->rollBack();
            return new JsonResponse(array('status' => false));
        }
        $entityManager->clear(Image::class);
        $image = $this->getDoctrine()->getRepository(Image::class)->find($imageId);
//        die($imageId);
        return new JsonResponse(array('status' => true, 'amount' => $image->getRatingMinus()));
    }
}