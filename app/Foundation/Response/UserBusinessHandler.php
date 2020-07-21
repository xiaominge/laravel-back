<?php

namespace App\Foundation\Response;

use App\Constant\UserBusinessCode;
use App\Constant\BusinessCode;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class UserBusinessHandler
{
    public function success($data = [], $msg = '操作成功!', $businessCode = BusinessCode::HTTP_OK)
    {
        return business_handler()->succeed($data, $msg, FoundationResponse::HTTP_OK, $businessCode);
    }

    public function fail($msg = '操作失败!', $data = [], $businessCode = BusinessCode::HTTP_INTERNAL_SERVER_ERROR)
    {
        return business_handler()->failed($msg, $data, FoundationResponse::HTTP_OK, $businessCode);
    }

    /**
     * 数据库错误
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateDbError($message = 'db error', $data = [])
    {
        return business_handler()->internalServerError($message, $data, UserBusinessCode::SERVICE_UPDATE_DB_ERROR);
    }

    /**
     * 找不到用户
     *
     * @param string $message
     * @param array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFoundUser($message = '找不到用户', $data = [])
    {
        return business_handler()->notFound($message, $data, UserBusinessCode::USER_NOT_FOUND);
    }
}
