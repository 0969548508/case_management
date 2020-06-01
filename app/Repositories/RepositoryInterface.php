<?php

namespace App\Repositories;

interface RepositoryInterface {

    public function store(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);

    public function findById($id);

    public function getAll();

    public function findByColumn($columnName, $value, $sortColumn = null, $orderBy = 'ASC');
}