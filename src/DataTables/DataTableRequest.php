<?php

namespace App\DataTables;

class DataTableRequest {

    private $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function getDraw() {
        if (isset($this->data['draw'])) {
            return (int) $this->data['draw'];
        }
        return null;
    }

    public function getLength() {
        if (isset($this->data['length'])) {
            return $this->data['length'];
        }
        return null;
    }

    public function getStart() {
        if (isset($this->data['start'])) {
            return $this->data['start'];
        }
        return null;
    }

    public function getOrderDir() {
        if (isset($this->data['order'][0]['dir'])) {
            return $this->data['order'][0]['dir'];
        }
        return null;
    }

    public function getOrderColumn() {
        if (isset($this->data['order'][0]['column'])) {
            $columnArray = [
                0 => 'i.id',
                1 => 'i.picture',
                2 => 'i.title',
                3 => 'u.username',
                4 => 'i.ratingPlus',
                5 => 'i.ratingMinus',
                6 => 'i.accepted',
                7 => 'i.time',

            ];
            $columnName = $columnArray[$this->data['order'][0]['column']];
            return $columnName;
        }
        return null;
    }

    public function getSearch() {
        if (isset($this->data['search']['value'])) {
            return $this->data['search']['value'];
        }
        return null;
    }

}