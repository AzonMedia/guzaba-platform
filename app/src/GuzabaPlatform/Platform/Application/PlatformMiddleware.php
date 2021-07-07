<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;

// use Guzaba2\Application\Application;
use Azonmedia\Http\Method;
use Azonmedia\Http\StatusCode;
use Azonmedia\Http\Body\Stream;
use Guzaba2\Authorization\Interfaces\UserInterface;
use Guzaba2\Http\Body\Structured;
use Guzaba2\Kernel\Kernel;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Guzaba2\Base\Base;
use Guzaba2\Http\Response;
use Guzaba2\Base\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
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
use GuzabaPlatform\Platform\Authentication\Models\JwtToken;
use Psr\Log\LogLevel;


class PlatformMiddleware extends Base
    implements MiddlewareInterface
{

    protected const CONFIG_DEFAULTS = [
        'disable_locking_on_get'    => TRUE,
        'services'      => [
            'CurrentUser'
        ],
        'class_dependencies'        => [ //dependencies on other classes
            //interface                 => implementation
            UserInterface::class    => User::class,
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * Custom logic for token
     *
     * @param ServerRequestInterface $Request
     * @param RequestHandlerInterface $Handler
     * @return ResponseInterface
     * @throws ConfigurationException
     * @throws InvalidArgumentException
     * @throws RunTimeException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\LogicException
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $Request, RequestHandlerInterface $Handler) : ResponseInterface
    {

        //if ($Request->getMethodConstant() === \Guzaba2\Http\Method::HTTP_OPTIONS) {//ONLY FOR DEVELOPMENT USE
        if (Method::get_method_constant($Request) === \Guzaba2\Http\Method::HTTP_OPTIONS) {//ONLY FOR DEVELOPMENT USE
            $content_type = 'application/json';

            $Body = new Stream();
            $output = ['code' => 1, 'message' => 'OK'];
            $json_output = json_encode($output);
            $Body->write($json_output);
            $Response = new Response(StatusCode::HTTP_OK, ['Content-Type' => $content_type], $Body);
            return $Response;
        }

        //disable the locking if the request does not involve updates
        //this must be the very first thing
        //if ($Request->getMethodConstant() === Method::HTTP_GET && self::CONFIG_RUNTIME['disable_locking_on_get']) {
        if (Method::get_method_constant($Request) === Method::HTTP_GET && self::CONFIG_RUNTIME['disable_locking_on_get']) {

            ActiveRecord::disable_locking();
        }

        $headers = $Request->getHeaders();

        if (isset($headers['token'])) {
            try {

                $Token = new JwtToken(['token_string' => $headers['token'][0]]);

                if ($Token->token_expiration_time > time()) {
                    // update token_expiration_time
                    $Token->update_token();
                    $current_user_uuid = $Token->user_uuid;
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

        if (isset($current_user_uuid)) {
            $user_class = self::CONFIG_RUNTIME['class_dependencies'][UserInterface::class];
            $User = new $user_class($current_user_uuid, TRUE, TRUE);
            if (!$User->user_is_disabled) {
                //if the user is not disabled set the current user
                self::get_service('CurrentUser')->set($User);
            } else {
                //otherwise just emit a message to the console and return forbidden request
                Kernel::log(sprintf(t::_('Request to %1$s was performed by disabled user %2$s (UUID %3$s). Returning forbidden content...'), $Request->getUri()->getPath(), $User->user_name, $User->get_uuid() ), LogLevel::NOTICE);
                $structure['message'] = sprintf(t::_('The user %1$s (UUID %2$s) is disabled.'), $User->user_name, $User->get_uuid() );
                $Response = new Response(StatusCode::HTTP_FORBIDDEN, [], new Structured($structure));
                return $Response;
            }
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