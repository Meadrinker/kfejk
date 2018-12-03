<?php

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController{

    /**
     * @Route("/tag/{tag}", name="tag")
     */
    public function tag($tag) {
        $limit = 2;
        $tagEntity = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByTag($tag, $limit, 0);
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByTag($tag);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = 1;
        $previousNumber = -1;

        return $this->render('tag/tag.html.twig', [
            'tag' => $tag,
            'images' => $tagEntity,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber
        ]);
    }

    /**
     * @Route("/tag/{tag}/{page}", name="tagPage", requirements={"page"="\d+"})
     */
    public function tagPage($tag, $page) {
        $limit = 2;
        $tagEntity = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByTag($tag, $limit, $this->calculateOffset($limit, $page));
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByTag($tag);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = $page + 1;
        $previousNumber = $page - 1;

        return $this->render('tag/tag.html.twig', [
            'tag' => $tag,
            'images' => $tagEntity,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber
        ]);
    }

    private function countPagesNumber($limit, $count) {
        return ceil($count / $limit);
    }

    private function calculateOffset($limit, $page) {
        return $limit * $page;
    }

}