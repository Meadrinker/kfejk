<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditUsersController extends AbstractController {

    /**
     * @Route("/admin/users/edit/{id}", name="admin_users_edit", requirements={"id"="\d+"})
     */
    public function editUser(Request $request, $id) {
        $userEntity = $this->getDoctrine()->getRepository(User::class)->find($id);
        $form = $this->createForm(UserEditType::class, $userEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userEntity);
            $entityManager->flush();
            $this->addFlash('success','Zedytowano użytkownika!');
            return $this->redirectToRoute('admin_users_edit', array('id' => $id));
        }

        return $this->render('admin/usersEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}