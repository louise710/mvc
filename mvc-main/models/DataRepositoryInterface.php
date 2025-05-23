<?php

namespace Models;

interface DataRepositoryInterface
{
    public function getAll();
    public function get($id);
    public function create(array $data);
    public function update($id,array $data);
    public function delete($id);
}
