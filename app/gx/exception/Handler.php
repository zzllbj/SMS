<?php
namespace app\gx\exception;

use Throwable;
use Webman\Exception\ExceptionHandler;
use Webman\Http\Request;
use Webman\Http\Response;

/**
 * Class Handler
 * @package app\gx\exception
 */
class Handler extends ExceptionHandler
{
    public function render(Request $request, Throwable $exception): Response
    {
        $debug = config('app.debug', true);
        $code  = $exception->getCode();
        $json  = [
            'Code'    => $code ? $code : 500,
            'Message' => $debug ? $exception->getMessage() : 'Server internal error',
            'Type'    => 'Failed',
        ];
        if ($debug) {
            $json['Request_Url']      = $request->uri();
            $json['Request_Method']   = $request->method();
            $json['Timestamp']        = date('Y-m-d H:i:s');
            $json['Client_ip']        = $request->getRealIp();
            $json['Request_param']    = $request->all();
            $json['Exception_Handle'] = get_class($exception);
            $json['Exception_Info']   = [
                'Code'    => $exception->getCode(),
                'Message' => $exception->getMessage(),
                'File'    => $exception->getFile(),
                'Line'    => $exception->getLine(),
                'Trace'   => explode("\n", $exception->getTraceAsString()),
            ];
        }
        return new Response(200, ['Content-Type' => 'application/json;charset=utf-8'], json_encode($json));
    }
}
