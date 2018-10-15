<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthorizationController extends AbstractController {

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder) {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole($this->getDoctrine()->getManager()->getReference(Role::class, 2));
            $password = $form->get('password')->getData();
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
//            echo "<pre>";
//            \Doctrine\Common\Util\Debug::dump($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Rejestracja poszła pomyślnie! :)!');
            return $this->redirectToRoute('register');
        }

        return $this->render('authorization/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils) {

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('authorization/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }



}