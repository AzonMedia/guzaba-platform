<?php

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Guzaba2\Http\Method;
use Guzaba2\Mvc\Controller;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Authentication\Models\JWT_Token as Token;
// use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Psr\Http\Message\ResponseInterface;

class Auth extends Controller
{

    public const ROUTES = [
        GP::API_ROUTE_PREFIX.'/user-login'      => [
        //'/user-login'   => [
            Method::HTTP_GET_HEAD_OPT               => [self::class, 'main'],
            Method::HTTP_POST                       => [self::class, 'login'],
        ],
        GP::API_ROUTE_PREFIX.'/user-register'   => [
        Method::HTTP_POST                           => [self::class, 'register'],
        ],
    ];

    public function main(): ResponseInterface
    {
        $struct = [];

        $struct['username_placeholder'] = t::_('Username');
        $struct['password_placeholder'] = t::_('Password');
        $struct['login_button'] = t::_('Login');
        $struct['hooks']['afterRender'] = [
            'name' => 'text',
            'data' => ['text' => t::_('Your password must be 8-20 characters long, contain letters and numbers, and must not
        contain spaces, special characters, or emoji.')],
        ];

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    public function login(string $username, string $password): ResponseInterface
    {
        $Response = parent::get_structured_ok_response();
        $struct = &$Response->getBody()->getStructure();
        $struct['user_logged_in'] = false;

        $Request = parent::get_request();
        $headers = $Request->getHeaders();

        if (isset($headers['token'])) {
            try {
                $oldToken = new Token(['token_string' => $headers['token'][0]]);
            } catch (RecordNotFoundException $exception) {
                // do nothing; user is not logged
            }

            $Response = $Response->withoutHeader('token');
        }

        try {
            $User = new User([
                'user_name' => $username,
            ]);

            if (password_verify($password, $User->user_password)) {

                $Token = new Token();
                $Token->user_id = $User->user_id;
                $Token = $Token->generate_new_token();

                $struct['user_logged_in'] = true;
            } else {
                // do nothing; user is not logged
            }

        } catch (RecordNotFoundException $exception) {
            $struct['message'] = t::_('Wrong username or password!');
        }

        if (isset($Token)) {
            $Response = $Response->withHeader('token', $Token->token_string);
        } else {
            $Response = $Response->withStatus(\Guzaba2\Http\StatusCode::HTTP_FORBIDDEN);
        }

        return $Response;
    }
}
