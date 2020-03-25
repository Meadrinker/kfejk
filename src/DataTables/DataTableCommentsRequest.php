<?php

namespace App\DataTables;

class DataTableCommentsRequest extends DataTableRequest {

    public function __construct(array $data) {
        parent::__construct($data);
    }

    public function getOrderColumn() {
        if (isset($this->data['order'][0]['column'])) {
            $columnArray = [
                0 => 'c.id',
                1 => 'u.username',
                3 => 'c.text',
                4 => 'c.date'

            ];
            $columnName = $columnArray[$this->data['order'][0]['column']];
            return $columnName;
        }
        return null;
    }

}