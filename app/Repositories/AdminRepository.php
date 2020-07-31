<?php

namespace App\Repositories;

use App\Exceptions\BusinessException;
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
        $model = $this->m()->where('deleted_at', 0)->find($id);
        if (!$model) {
            throw new BusinessException('管理员不存在或已被删除');
        }
        return $model;
    }

}
