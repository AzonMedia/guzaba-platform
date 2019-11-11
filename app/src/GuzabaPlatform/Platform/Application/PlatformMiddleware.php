<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

// use Guzaba2\Application\Application;
use Guzaba2\Authorization\User;
use Guzaba2\Base\Base;
use Guzaba2\Http\Method;
use Guzaba2\Http\Server;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
// use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
use GuzabaPlatform\Platform\Authentication\Models\JWT_Token as Token;

class PlatformMiddleware extends Base
    implements MiddlewareInterface
{

    protected const CONFIG_DEFAULTS = [
        'disable_locking_on_get'    => TRUE,
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Custom logic for token
     *
     * @param ServerRequestInterface $Request
     * @param RequestHandlerInterface $Handler
     * @return ResponseInterface
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     */
    public function process(ServerRequestInterface $Request, RequestHandlerInterface $Handler) : ResponseInterface
    {

        //disable the locking if the request does not involve updates
        //this must be the very first thing
        if ($Request->getMethodConstant() === Method::HTTP_GET && self::CONFIG_RUNTIME['disable_locking_on_get']) {
            ActiveRecord::disable_locking();
        }

//        $headers = $Request->getHeaders();
//
//        $Context = Coroutine::getContext();
//        $Context->current_user_id = 1;
//
//        if (isset($headers['token'])) {
//            try {
//
//                $Token = new Token(['token_string' => $headers['token'][0]]);
//
//                if ($Token->token_expiration_time > time()) {
//                    // update token_expiration_time
//                    $Token->update_token();
//                    $Context = Coroutine::getContext();
//                    $Context->current_user_id = $Token->user_id;
//                }  else {
//                    unset($Token);
//                }
//            } catch (RecordNotFoundException $exception) {
//                // do nothing; user is NOT logged in
//            }
//        }
//
//        if (isset($Token)) {
//            $Request = $Request->withHeader('token', $Token->token_string);
//        } else {
//            $Request = $Request->withoutHeader('token');
//        }
//
//        $Response = $Handler->handle($Request);
//        $response_token = $Response->getHeaders();
//
//        if (isset($Token) && !isset($response_token['token'])) {
//            $Response = $Response->withHeader('token', $Token->token_string);
//        } else {
//            //do NOT remove token from header!
//        }
//

//        $Response = $Handler->handle($Request);


        $current_user_id = \Guzaba2\Authorization\User::get_default_current_user_id();
        //TODO add code that reads the current user id from JWT
        //$current_user_id = 0;//set here from JWT

        $Request = $Request->withAttribute('current_user_id', $current_user_id);//not really used but can be set in case the Middleware after that needs it
        $Context = Coroutine::getContext();
        $User = new User($current_user_id);
        $Context->CurrentUser->set($User);



        $Response = $Handler->handle($Request);
        
        return $Response;
    }

}