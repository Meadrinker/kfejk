<?php

namespace App\DataTables;

abstract class DataTableRequest {

    protected $data;

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

    abstract public function getOrderColumn();

    public function getSearch() {
        if (isset($this->data['search']['value'])) {
            return $this->data['search']['value'];
        }
        return null;
    }

}