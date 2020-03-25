<?php

namespace App\Controller;

use App\DataTables\DataTableCommentsRequest;
use App\Entity\Comment;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxCommentsController extends AbstractController {


    /**
     * @Route("/admin/commentsgrid", name="admin_commentsgrid")
     */
    public function comments(Request $request, FilterService $filterService) {
        $dataTableRequest = new DataTableCommentsRequest($request->request->all());
        $draw = $dataTableRequest->getDraw();
        $search = $dataTableRequest->getSearch();
        $data = $this->prepareBaseData($dataTableRequest, $filterService);
        $total = $this->getDoctrine()->getRepository(Comment::class)->countComments();
        $filtered = $this->getDoctrine()->getRepository(Comment::class)->filteredComments($search);
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
        $commentEntity = $this->getDoctrine()->getRepository(Comment::class)->sortComments($columnName, $dir, $search, $limit, $offset);
        $data['data'] = [];
        foreach ($commentEntity as $comment) {
            $data['data'][] = [
                $comment->getId(),
                $comment->getUser()->getUsername(),
                $this->thumbnail($filterService, $comment->getImage()->getPicture()),
                $comment->getText(),
                $comment->getDate()->format('Y-m-d H:i:s'),
                '<a href="comments/delete/' . $comment->getId() . '">DELETE</a>',
                '<a href="comments/edit/' . $comment->getId() . '">EDIT</a>'
            ];
        }
        return $data;
    }

}