<?php

namespace App\Constant;

class UserBusinessCode
{
    const AUTH_TOKEN_MISSING = 1000001; // token不存在
    const AUTH_TOKEN_ERROR = 1000002; // token错误

    const FILE_EXISTS_FALSE = 1000003; // 文件不存在
    const REQUEST_PARAMETERS_VERIFY_FAILED = 1000004; // 参数校验失败

    const SERVICE_UNKNOWN_FORBID = 1000005; // 服务端未知错误
    const SERVICE_UPDATE_DB_ERROR = 1000006; // 修改数据库失败

    const USER_NOT_FOUND = 1000007; // 找不到用户
}
