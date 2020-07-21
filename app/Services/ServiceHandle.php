<?php

namespace App\Services;

use App\Foundation\Service\ServiceHandle as ServiceHandleBase;

/**
 * @property PermissionService $permission
 */
class ServiceHandle extends ServiceHandleBase
{
    protected static $registerList = array(
        'permission' => PermissionService::class,
    );
}
