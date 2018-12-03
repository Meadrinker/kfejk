<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {

    /**
     * @Route("/", name="index")
     */
    public function index() {
        $limit = 2;
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByAccept(1, $limit, 0);
        $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId();
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByAccept(1);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = 1;
        $previousNumber = -1;


        return $this->render('default/index.html.twig', [
            'images' => $images,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber,
            'random' => $random
        ]);
    }

    /**
     * @Route("/{page}", name="indexPage", requirements={"page"="\d+"})
     */
    public function indexPage($page) {
        $limit = 2;
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByAccept(1, $limit, $this->calculateOffset($limit, $page));
        $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId();
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByAccept(1);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = $page + 1;
        $previousNumber = $page - 1;

        return $this->render('default/index.html.twig', [
            'images' => $images,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber,
            'random' => $random
        ]);
    }

    /**
     * @Route("/image/accept/{id}", name="image_accept")
     */
    public function acceptImage($id) {
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);
        $acceptStatus = $image->getAccepted();

        if ($acceptStatus == 1) {
            $image->setAccepted(0);
        } else {
            $image->setAccepted(1);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($image);
        $entityManager->flush();
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/random/{id}", name="random")
     */
    public function random($id = null) {
        if (is_numeric($id)) {
            $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId($id);
        } else {
            $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId();
        }
        return $this->redirectToRoute('image', ['image' => $random]);
    }

    private function calculateOffset($limit, $page) {
        return $limit * $page;
    }

    private function countPagesNumber($limit, $count) {
        return ceil($count / $limit);
    }

}