<?php
interface ExceptionHandlerInterface
{
    /**
     * 记录日志
     * @param Throwable $e
     * @return mixed
     */
    public function report(Throwable $e);

    /**
     * 渲染返回
     * @param Request $request
     * @param Throwable $e
     * @return Response
     */

    public function render(Request $request, Throwable $e) : Response
    {
        // Apidoc异常处理响应
        if ($exception instanceof \hg\apidoc\exception\HttpException) {
            return response(json_encode([
                "code" => $exception->getCode(),
                "message" => $exception->getMessage(),
            ],JSON_UNESCAPED_UNICODE), $exception->getStatusCode());
        }
    }
}
