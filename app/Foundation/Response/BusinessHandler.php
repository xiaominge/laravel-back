<?php

namespace App\Foundation\Response;

use App\Constant\BusinessCode;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * Class BusinessHandler
 * @package App\Foundation\Response
 */
class BusinessHandler
{
    /**
     * 200 OK - [GET]：服务器成功返回用户请求的数据，该操作是幂等的（Idempotent）。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ok($data = [], $message = "OK", $businessCode = BusinessCode::HTTP_OK, $headers = [])
    {
        return $this->succeed($data, $message, FoundationResponse::HTTP_OK, $businessCode, $headers);
    }

    /**
     * 201 CREATED - [POST/PUT/PATCH]：用户新建或修改数据成功。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($data = [], $message = "", $businessCode = BusinessCode::HTTP_CREATED, $headers = [])
    {
        $message = $message ? $message : 'Created';

        return $this->succeed($data, $message, FoundationResponse::HTTP_CREATED, $businessCode, $headers);
    }

    /**
     * 202 Accepted - [*]：表示一个请求已经进入后台排队（异步任务）
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accepted($data = [], $message = "", $businessCode = BusinessCode::HTTP_ACCEPTED, $headers = [])
    {
        $message = $message ? $message : 'Accepted';

        return $this->succeed($data, $message, FoundationResponse::HTTP_ACCEPTED, $businessCode, $headers);
    }

    /**
     * 204 NO CONTENT - [DELETE]：用户删除数据成功。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleted($data = [], $message = "", $businessCode = BusinessCode::HTTP_NO_CONTENT, $headers = [])
    {
        $message = $message ? $message : 'Deleted';

        return $this->succeed($data, $message, FoundationResponse::HTTP_NO_CONTENT, $businessCode, $headers);
    }

    /**
     * 400 INVALID REQUEST - [POST/PUT/PATCH]：用户发出的请求有错误，服务器没有进行新建或修改数据的操作，该操作是幂等的。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest($message = "", $data = [], $businessCode = BusinessCode::HTTP_BAD_REQUEST, $headers = [])
    {
        $message = $message ? $message : 'Bad Request';

        return $this->failed($message, $data, FoundationResponse::HTTP_BAD_REQUEST, $businessCode, $headers);
    }


    /**
     * 401 Unauthorized - [*]：表示用户没有权限（令牌、用户名、密码错误）。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized($message = "", $data = [], $businessCode = BusinessCode::HTTP_UNAUTHORIZED, $headers = [])
    {
        $message = $message ? $message : 'Unauthorized';

        return $this->failed($message, $data, FoundationResponse::HTTP_UNAUTHORIZED, $businessCode, $headers);
    }

    /**
     * 402 Parameters Missing - [POST/PUT/PATCH]：用户请求参数不全
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function parametersMissing($message = "", $data = [], $businessCode = BusinessCode::HTTP_PAYMENT_REQUIRED, $headers = [])
    {
        $message = $message ? $message : 'Parameters Missing';

        return $this->failed($message, $data, FoundationResponse::HTTP_PAYMENT_REQUIRED, $businessCode, $headers);
    }

    /**
     * 402 Parameters Error - [POST/PUT/PATCH]：用户请求参数错误
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function parametersError($message = "", $data = [], $businessCode = BusinessCode::HTTP_PAYMENT_REQUIRED, $headers = [])
    {
        $message = $message ? $message : 'Parameters Error';

        return $this->failed($message, $data, FoundationResponse::HTTP_PAYMENT_REQUIRED, $businessCode, $headers);
    }

    /**
     * 403 Forbidden - [*] 表示用户得到授权（与401错误相对），但是访问是被禁止的。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden($message = "", $data = [], $businessCode = BusinessCode::HTTP_FORBIDDEN, $headers = [])
    {
        $message = $message ? $message : 'Forbidden';

        return $this->failed($message, $data, FoundationResponse::HTTP_FORBIDDEN, $businessCode, $headers);
    }

    /**
     * 404 NOT FOUND - [*]：用户发出的请求针对的是不存在的记录，服务器没有进行操作，该操作是幂等的。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound($message = "", $data = [], $businessCode = BusinessCode::HTTP_NOT_FOUND, $headers = [])
    {
        $message = $message ? $message : 'Not Found';

        return $this->failed($message, $data, FoundationResponse::HTTP_NOT_FOUND, $businessCode, $headers);
    }

    /**
     * 406 Not Acceptable - [GET]：用户请求的格式不可得（比如用户请求JSON格式，但是只有XML格式）。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function notAcceptable($message = "", $data = [], $businessCode = BusinessCode::HTTP_NOT_ACCEPTABLE, $headers = [])
    {
        $message = $message ? $message : 'Request Format Error';

        return $this->failed($message, $data, FoundationResponse::HTTP_NOT_ACCEPTABLE, $businessCode, $headers);
    }

    /**
     * 410 Gone -[GET]：用户请求的资源被永久删除，且不会再得到的。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function gone($message = "", $data = [], $businessCode = BusinessCode::HTTP_GONE, $headers = [])
    {
        $message = $message ? $message : 'Resources Deleted';

        return $this->failed($message, $data, FoundationResponse::HTTP_GONE, $businessCode, $headers);
    }

    /**
     * 422 Unprocessable entity - [POST/PUT/PATCH] 当创建一个对象时，发生一个验证错误。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unprocessableEntity($message = "", $data = [], $businessCode = BusinessCode::HTTP_UNPROCESSABLE_ENTITY, $headers = [])
    {
        $message = $message ? $message : 'Created Validate Error';

        return $this->failed($message, $data, FoundationResponse::HTTP_UNPROCESSABLE_ENTITY, $businessCode, $headers);
    }

    /**
     * 500 INTERNAL SERVER ERROR - [*]：服务器发生错误，用户将无法判断发出的请求是否成功。
     *
     * @param array $data
     * @param string $message
     * @param int $businessCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalServerError($message = "", $data = [], $businessCode = BusinessCode::HTTP_INTERNAL_SERVER_ERROR, $headers = [])
    {
        $message = $message ? $message : 'Server Error';

        return $this->failed($message, $data, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $businessCode, $headers);
    }

    /**
     * 根据参数返回api的结果[成功]
     *
     * @param array $data
     * @param string $message
     * @param array $statusCode
     * @param int $businessCode
     * @param array $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function succeed($data, $message, $statusCode, $businessCode, $header = [])
    {
        $header['request_time'] = get_millisecond() - context()->get('request_start_time');

        $response = [
            'code' => $businessCode,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode, $header);
    }

    /**
     * 根据参数返回api的结果[失败]
     *
     * @param string $message
     * @param array $data
     * @param null $statusCode
     * @param int $businessCode
     * @param array $header
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed($message, $data, $statusCode, $businessCode, $header = [])
    {
        $header['request_time'] = request()->get('lq_request_start_time') - get_millisecond();

        $response = [
            'code' => $businessCode,
            'message' => $message,
            'data' => (object)$data,
        ];

        return response()->json($response, $statusCode, $header);
    }
}
