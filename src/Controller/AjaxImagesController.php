<?php

namespace App\Controller;

use App\DataTables\DataTableImagesRequest;
use App\Entity\Image;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxImagesController extends AbstractController {


    /**
     * @Route("/admin/imagesgrid", name="admin_imagesgrid")
     */
    public function images(Request $request, FilterService $filterService) {
        $dataTableRequest = new DataTableImagesRequest($request->request->all());
        $draw = $dataTableRequest->getDraw();
        $search = $dataTableRequest->getSearch();
        $data = $this->prepareBaseData($dataTableRequest, $filterService);
        $total = $this->getDoctrine()->getRepository(Image::class)->countImages();
        $filtered = $this->getDoctrine()->getRepository(Image::class)->filteredImages($search);
        $data['recordsTotal'] = $total;
        $data['recordsFiltered'] = $filtered;
        $data['draw'] = $draw;

        return new JsonResponse($data);
    }

    private function thumbnail(FilterService $filterService, $image) {
        $imageURL = '/uploads/images/' . $image;
        $resourcePath = $filterService->getUrlOfFilteredImage($imageURL, 'thumb_97x97');
        $image = '<div class="thumbnail">';
        $image .= '<a data-fancybox href="'. $imageURL .'">';
        $image .= '<img src="'. $resourcePath .'">';
        $image .=  '</a></div>';
        return $image;
    }

    private function prepareBaseData ($dataTablesRequest, FilterService $filterService) {
        $limit = $dataTablesRequest->getLength();
        $offset = $dataTablesRequest->getStart();
        $dir = $dataTablesRequest->getOrderDir();
        $columnName = $dataTablesRequest->getOrderColumn();
        $search = $dataTablesRequest->getSearch();
        $imageEntity = $this->getDoctrine()->getRepository(Image::class)->sortImages($columnName, $dir, $search, $limit, $offset);
        $data['data'] = [];
        foreach ($imageEntity as $image) {
            $tagEntity = $image->getTags();
            $tags = [];
            $tagCounter = 0;
            foreach ($tagEntity as $tag) {
                $tags[$tagCounter] = $tag->getName();
                $tagCounter = $tagCounter + 1;
            }
            $preparedTags = implode(' ', $tags);
            $data['data'][] = [
                $image->getId(),
                $this->thumbnail($filterService, $image->getPicture()),
                $image->getTitle(),
                $image->getAuthor()->getUsername(),
                $image->getRatingPlus(),
                $image->getRatingMinus(),
                $image->getAccepted(),
                $image->getTime()->format('Y-m-d H:i:s'),
                $preparedTags,
                '<a href="images/delete/' . $image->getId() . '">DELETE</a>',
                '<a href="images/edit/' . $image->getId() . '">EDIT</a>'
            ];
        }
        return $data;
    }

}