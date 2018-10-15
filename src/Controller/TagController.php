<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TagController extends AbstractController{

    /**
     * @Route("/tag/{tag}", name="tag")
     */
    public function image($tag) {
        $tagEntity = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(array('name' => $tag));


        return $this->render('tag/tag.html.twig', [
            'images' => $tagEntity->getImages()
        ]);
    }







}