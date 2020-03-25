<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tag;
use App\Form\ImageEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminEditImagesController extends AbstractController {

    /**
     * @Route("/admin/images/edit/{id}", name="admin_images_edit", requirements={"id"="\d+"})
     */
    public function editImage(Request $request, $id) {
        $imageEntity = $this->getDoctrine()->getRepository(Image::class)->find($id);
        $tagEntity = $imageEntity->getTags();
        $implodedTags = $this->implodeTags($tagEntity);
        $form = $this->createForm(ImageEditType::class, $imageEntity, ['tags' => $implodedTags]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tags = $form->get('tags')->getData();
            $this->prepareTags($imageEntity->getTags(), $tags);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imageEntity);
            $entityManager->flush();
            $this->addFlash('success','Zedytowano obrazek!');
            return $this->redirectToRoute('admin_images_edit', array('id' => $id));
        }

        return $this->render('admin/imagesEdit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function implodeTags($tagEntity) {
        $tags = [];
        $tagCounter = 0;
        foreach ($tagEntity as $tag) {
            $tags[$tagCounter] = $tag->getName();
            $tagCounter = $tagCounter + 1;
        }
        $preparedTags = implode(' ', $tags);
        return $preparedTags;
    }

    private function prepareTags($tagsCollection, string $tags) {
        $tagsCollection->clear();
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

}