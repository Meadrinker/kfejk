<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController {

    /**
     * @Route("/admin", name="admin")
     */
    public function user() {

        return $this->render('admin/admin.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function users() {
        $userEntity = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'userEntity' => $userEntity
        ]);
    }

    /**
     * @Route("/admin/comments", name="admin_comments")
     */
    public function comments() {
        $commentEntity = $this->getDoctrine()->getRepository(Comment::class)->findAll();

        return $this->render('admin/comments.html.twig', [
            'commentEntity' => $commentEntity
        ]);
    }

    /**
     * @Route("/admin/images", name="admin_images")
     */
    public function images() {

        return $this->render('admin/images.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/images/delete/{id}", name="admin_images_delete")
     */
    public function deleteImage($id) {
        $this->getDoctrine()->getRepository(Image::class)->deleteImage($id);
        return $this->redirectToRoute('admin_images');
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     */
    public function deleteUser($id) {
        $this->getDoctrine()->getRepository(User::class)->deleteUser($id);
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/comments/delete/{id}", name="admin_comments_delete")
     */
    public function deleteComment($id) {
        $this->getDoctrine()->getRepository(Comment::class)->deleteComment($id);
        return $this->redirectToRoute('admin_comments');
    }

}