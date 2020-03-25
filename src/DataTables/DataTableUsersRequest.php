<?php

namespace App\DataTables;

class DataTableUsersRequest extends DataTableRequest {

    public function __construct(array $data) {
        parent::__construct($data);
    }

    public function getOrderColumn() {
        if (isset($this->data['order'][0]['column'])) {
            $columnArray = [
                0 => 'u.id',
                1 => 'u.username',
                2 => 'u.password',
                3 => 'u.email',
                4 => 'r.name'
            ];
            $columnName = $columnArray[$this->data['order'][0]['column']];
            return $columnName;
        }
        return null;
    }

}