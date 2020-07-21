<?php

namespace App\Repositories;

use App\Foundation\Repository\RepositoryHandler as RepositoryHandlerBase;

/**
 * @property RoleRepository $role
 * @property AdminRepository $admin
 * @property PermissionRepository $permission
 */
class RepositoryHandler extends RepositoryHandlerBase
{
    /**
     * @var string[]
     */
    protected static $registerList = [
        'admin' => AdminRepository::class,
        'role' => RoleRepository::class,
        'permission' => PermissionRepository::class,
    ];
}
