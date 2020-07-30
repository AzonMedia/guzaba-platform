<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

use Guzaba2\Base\Base;
use Azonmedia\Http\Body\Stream;
use Guzaba2\Http\Response;
use Azonmedia\Http\StatusCode;
use Guzaba2\Swoole\Server;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthCheckMiddleware extends Base
implements MiddlewareInterface
{
    /**
     *
     *
     * @param ServerRequestInterface $Request
     * @param RequestHandlerInterface $Handler
     * @return ResponseInterface
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function process(ServerRequestInterface $Request, RequestHandlerInterface $Handler) : ResponseInterface
    {

//        $content_type = 'application/json';
//
//        //if request method is options return ok for now
//        if ($Request->getMethodConstant() === \Guzaba2\Http\Method::HTTP_OPTIONS) {//ONLY FOR DEVELOPMENT USE
//            $Body = new Stream();
//            $output = ['code' => 1, 'message' => 'OK'];
//            $json_output = json_encode($output);
//            $Body->write($json_output);
//            $Response = new Response(StatusCode::HTTP_OK, ['Content-Type' => $content_type], $Body);
//            return $Response;
//        }
//        if ($Request->hasHeader('Authorization')) {
//            $auth = $Request->getHeader('Authorization');
//            if (is_array($auth) && isset($auth[0])) {
//                $token = $auth[0];
//
//                $Connection = self::ConnectionFactory()->get_connection(\GuzabaPlatform\Platform\Application\MysqlConnectionI::class, $CONNECTION);
//
//                $sql = "SELECT kids_token, 123 AS b FROM kids WHERE kids_token = '{$token}'";
//
//                $Statement = $Connection->prepare($sql);
//                $Statement->execute();
//
//                $row = $Statement->fetchRow();
//
//                if (! empty($row)) {
//                    $Request = $Request->withAttribute('token', $row['kids_token']);
//                } else {
//                    // if (false) {//check requests that is not need authorization
//                        $Body = new Stream();
//                        $output = ['code' => -100, 'message' => 'need authorization.'];
//                        $json_output = json_encode($output);
//                        $Body->write($json_output);
//                        // self::ConnectionFactory()->free_connection($Connection);
//                        return new Response(StatusCode::HTTP_UNAUTHORIZED, ['Content-Type' => $content_type], $Body);
//                    // }
//                }
//
//                // self::ConnectionFactory()->free_connection($Connection);
//            }
//        }


        $Response = $Handler->handle($Request);

        return $Response;

        // return $Request;
    }
}