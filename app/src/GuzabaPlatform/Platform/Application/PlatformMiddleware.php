<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

// use Guzaba2\Application\Application;
use Guzaba2\Authorization\User;
use Guzaba2\Base\Base;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Http\Method;
use Guzaba2\Http\Server;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Kernel\Exceptions\ConfigurationException;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

// use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
use GuzabaPlatform\Platform\Authentication\Models\JwtToken as Token;


class PlatformMiddleware extends Base
    implements MiddlewareInterface
{

    protected const CONFIG_DEFAULTS = [
        'disable_locking_on_get'    => TRUE,
        'services'      => [
            'CurrentUser'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Custom logic for token
     *
     * @param ServerRequestInterface $Request
     * @param RequestHandlerInterface $Handler
     * @return ResponseInterface
     * @throws InvalidArgumentException
     * @throws RunTimeException
     * @throws ConfigurationException
     */
    public function process(ServerRequestInterface $Request, RequestHandlerInterface $Handler) : ResponseInterface
    {

        //disable the locking if the request does not involve updates
        //this must be the very first thing
        if ($Request->getMethodConstant() === Method::HTTP_GET && self::CONFIG_RUNTIME['disable_locking_on_get']) {
            ActiveRecord::disable_locking();
        }

        $headers = $Request->getHeaders();

        if (isset($headers['token'])) {
            try {

                $Token = new Token(['token_string' => $headers['token'][0]]);

                if ($Token->token_expiration_time > time()) {
                    // update token_expiration_time
                    $Token->update_token();
                    $current_user_id = $Token->user_id;
                }  else {
                    unset($Token);
                }
            } catch (RecordNotFoundException $exception) {
                // do nothing; user is NOT logged in
            }
        }

        if (!isset($Token)) {
            $Request = $Request->withoutHeader('token');
        }

        if (isset($current_user_id)) {
            $Context = Coroutine::getContext();
            $User = new User($current_user_id);
            $Context->CurrentUser->set($User);
        }

        $Response = $Handler->handle($Request);
        $response_token = $Response->getHeader('token');

        if (isset($Token) && empty($response_token)) {
            $Response = $Response->withHeader('token', $Token->token_string);
        } else {
            //do NOT remove token from header!
        }

        return $Response;
    }

}