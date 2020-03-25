<?php

namespace App\DataTables;

class DataTableImagesRequest extends DataTableRequest {

    public function __construct(array $data) {
        parent::__construct($data);
    }

    public function getOrderColumn() {
        if (isset($this->data['order'][0]['column'])) {
            $columnArray = [
                0 => 'i.id',
                2 => 'i.title',
                3 => 'u.username',
                4 => 'i.ratingPlus',
                5 => 'i.ratingMinus',
                6 => 'i.accepted',
                7 => 'i.time'

            ];
            $columnName = $columnArray[$this->data['order'][0]['column']];
            return $columnName;
        }
        return null;
    }

}