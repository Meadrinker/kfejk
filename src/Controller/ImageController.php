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

class ImageController extends AbstractController{ 

    /**
     * @Route("/image/{image}", name="image", requirements={"image"="\d+"})
     */
    public function image(int $image, Request $request, UserInterface $user = null) {
        $imageEntity = $this->getDoctrine()->getRepository(Image::class)->find($image);
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(array('image' => $image));
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setImage($imageEntity);
            $comment->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','Dodano komentarz!');
            return $this->redirectToRoute('image', array('image' => $image));
        }

        return $this->render('image/image.html.twig', [
            'imageEntity' => $imageEntity,
            'image' => $image,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }







}