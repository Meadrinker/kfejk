<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Role;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController {

    /**
     * @Route("/", name="index")
     */
    public function index() {
        $limit = 2;
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImages($limit, 0);
        $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId();
        $count = $this->getDoctrine()->getRepository(Image::class)->countImages();
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
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImages($limit, $this->calculateOffset($limit, $page));
        $random = $this->getDoctrine()->getRepository(Image::class)->randomImageId();
        $count = $this->getDoctrine()->getRepository(Image::class)->countImages();
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