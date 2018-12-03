<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\ImageAddType;
use App\Form\MailType;
use App\Form\NewPasswordType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController {

    /**
     * @Route("/user", name="user")
     */
    public function user() {

        return $this->render('user/user.html.twig', [
        ]);
    }

    /**
     * @Route("/user/password", name="password")
     */
    public function password(Request $request, UserInterface $user, UserPasswordEncoderInterface $encoder) {

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success','Zmieniono hasło');
            return $this->redirectToRoute('password');
        }

        return $this->render('user/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/mail", name="mail")
     */
    public function mail(Request $request, UserInterface $user) {
        $userId = $user->getId();
        $userEntity = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $form = $this->createForm(MailType::class, $userEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userEntity);
            $entityManager->flush();
            $this->addFlash('success','Zmieniono maila');
            return $this->redirectToRoute('mail');
        }

        return $this->render('user/mail.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/addimage", name="addimage")
     */
    public function addImage(Request $request, UserInterface $user) {

        $image = new Image();
        $form = $this->createForm(ImageAddType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tags = $form->get('tags')->getData();
            $this->prepareTags($image->getTags(), $tags);
            $file = $form->get('picture')->getData();
            $fileExtension = $file->guessExtension();
            $fileName = $this->generateUniqueFileName().'.'.$fileExtension;
            $file->move($this->getParameter('images_directory'), $fileName);
            $image->setPicture($fileName);
            $image->setAuthor($user);
            $image->setRatingPlus(0);
            $image->setRatingMinus(0);
            $image->setAccepted(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($image);
            $entityManager->flush();
            $this->addFlash('success','Dodano obrazek! :D');
            return $this->redirectToRoute('addimage');
        }

        return $this->render('user/addImage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/imagesmanage", name="imagesManage")
     */
    public function imagesManage(UserInterface $user) {
        $limit = 2;
        $userId = $user->getId();
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByUser($userId, $limit, 0);
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByUser($userId);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = 1;
        $previousNumber = -1;

        return $this->render('user/imagesManage.html.twig', [
            'images' => $images,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber
        ]);
    }

    /**
     * @Route("/user/image/delete/{id}", name="user_image_delete")
     */
    public function deleteImage($id) {
        $this->getDoctrine()->getRepository(Image::class)->deleteImage($id);
        return $this->redirectToRoute('imagesManage');
    }

    /**
     * @Route("/user/imagesmanage/{page}", name="imagesManagePage", requirements={"page"="\d+"})
     */
    public function imagesManagePage(UserInterface $user, $page) {
        $limit = 2;
        $userId = $user->getId();
        $images = $this->getDoctrine()->getRepository(Image::class)->selectLimitedImagesByUser($userId, $limit, $this->calculateOffset($limit, $page));
        $count = $this->getDoctrine()->getRepository(Image::class)->countImagesByUser($userId);
        $imagesCount = $count[0][1];
        $pagesNumber = $this->countPagesNumber($limit, $imagesCount);
        $nextNumber = $page + 1;
        $previousNumber = $page - 1;

        return $this->render('user/imagesManage.html.twig', [
            'images' => $images,
            'nextNumber' => $nextNumber,
            'previousNumber' => $previousNumber,
            'pagesNumber' => $pagesNumber
        ]);
    }

    private function prepareTags(ArrayCollection $tagsCollection, string $tags) {
        $tagsExploded = explode(" ", $tags);
        foreach ($tagsExploded as $tag) {
            $tagData = $this->getDoctrine()->getRepository(Tag::class)->findOneBy(['name' => $tag]);
            if ($tagData == null) {
                $tagData = new Tag();
                $tagData->setName($tag);
            }
            $tagsCollection->add($tagData);
        }
        return $tagsCollection;
    }

    private function generateUniqueFileName() {
        return md5(uniqid());
    }

    private function countPagesNumber($limit, $count) {
        return ceil($count / $limit);
    }

    private function calculateOffset($limit, $page) {
        return $limit * $page;
    }

}