<?php

namespace App\Repositories;

use App\Foundation\Repository\Repository;
use App\Models\Admin;

class AdminRepository extends Repository
{

    public function model()
    {
        return Admin::class;
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

}
