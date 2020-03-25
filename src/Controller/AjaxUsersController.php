<?php

namespace App\Controller;

use App\DataTables\DataTableUsersRequest;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AjaxUsersController extends AbstractController {


    /**
     * @Route("/admin/usersgrid", name="admin_usersgrid")
     */
    public function users(Request $request) {
        $dataTableRequest = new DataTableUsersRequest($request->request->all());
        $draw = $dataTableRequest->getDraw();
        $search = $dataTableRequest->getSearch();
        $data = $this->prepareBaseData($dataTableRequest);
        $total = $this->getDoctrine()->getRepository(User::class)->countUsers();
        $filtered = $this->getDoctrine()->getRepository(User::class)->filteredUsers($search);
        $data['recordsTotal'] = $total;
        $data['recordsFiltered'] = $filtered;
        $data['draw'] = $draw;

        return new JsonResponse($data);
    }

    private function prepareBaseData ($dataTablesRequest) {
        $limit = $dataTablesRequest->getLength();
        $offset = $dataTablesRequest->getStart();
        $dir = $dataTablesRequest->getOrderDir();
        $columnName = $dataTablesRequest->getOrderColumn();
        $search = $dataTablesRequest->getSearch();
        $userEntity = $this->getDoctrine()->getRepository(User::class)->sortUsers($columnName, $dir, $search, $limit, $offset);
        $data['data'] = [];
        foreach ($userEntity as $user) {
            $data['data'][] = [
                $user->getId(),
                $user->getUsername(),
                $user->getPassword(),
                $user->getEmail(),
                $user->getRole()->getName(),
                '<a href="users/delete/' . $user->getId() . '">DELETE</a>',
                '<a href="users/edit/' . $user->getId() . '">EDIT</a>'
            ];
        }
        return $data;
    }

}