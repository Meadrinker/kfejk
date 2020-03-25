<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditCommentsController extends AbstractController {

    /**
     * @Route("/admin/comments/edit/{id}", name="admin_comments_edit", requirements={"id"="\d+"})
     */
    public function editComment(Request $request, $id) {
        $commentEntity = $this->getDoctrine()->getRepository(Comment::class)->find($id);
        $form = $this->createForm(CommentEditType::class, $commentEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentEntity);
            $entityManager->flush();
            $this->addFlash('success','Zedytowano komentarz!');
            return $this->redirectToRoute('admin_comments_edit', array('id' => $id));
        }

        return $this->render('admin/commentsEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}