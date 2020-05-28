<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Guzaba2\Authorization\Role;
use Guzaba2\Http\Method;
use Guzaba2\Http\StatusCode;
use Guzaba2\Mvc\ActiveRecordController;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use GuzabaPlatform\Platform\Authentication\Models\JwtToken as Token;
use GuzabaPlatform\Platform\Authentication\Models\User;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Kernel\Kernel;
use const GuzabaPlatform\Platform\Authentication\Roles\ADMINISTRATOR;

class Auth extends BaseController
{

//    public const ROUTES = [
//        GP::API_ROUTE_PREFIX . '/user-login' => [
//            Method::HTTP_GET_HEAD_OPT => [self::class, 'main'],
//            Method::HTTP_POST => [self::class, 'login'],
//        ],
//        GP::API_ROUTE_PREFIX . '/user-register' => [
//            Method::HTTP_POST => [self::class, 'register'],
//        ],
//    ];

    protected const CONFIG_DEFAULTS = [
        'routes'    => [
            '/user/login' => [
                Method::HTTP_GET    => [self::class, 'login_get'],
                Method::HTTP_POST   => [self::class, 'login_post'],
            ],
            '/user/register' => [
                Method::HTTP_POST   => [self::class, 'register_post'],
            ],
        ]
    ];

    protected const CONFIG_RUNTIME = [];

    public function login_get(): ResponseInterface
    {
        $struct = [];

        $struct['username_placeholder'] = t::_('Username');
        $struct['password_placeholder'] = t::_('Password');
        $struct['login_button'] = t::_('Login');
//        $struct['hooks']['_after_main'] = [
//            'name' => 'text',
//            'data' => ['text' => t::_('Your password must be 8-20 characters long, contain letters and numbers, and must not
//        contain spaces, special characters, or emoji.')],
//        ];

//a test hook
//        $struct['hooks']['_after_main'][] = [
//            //'name'  => 'text',
//            //'name' => '@GuzabaPlatform.Platform/views/hooks/templates/text.vue',
//            'name' => '@GuzabaPlatform.Platform/views/hooks/templates/TextHook.vue',
//            //'name' => '/home/local/PROJECTS/guzaba-platform-skeleton/guzaba-platform-skeleton/vendor/guzaba-platform/guzaba-platform/app/public_src/src/views/hooks/templates/text.vue',
//            'data' => ['text' => t::_('Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.')],
//        ];

        //$struct['password_text'] = t::_('Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.');

        $Response = self::get_structured_ok_response($struct);
        return $Response;
    }

    public function login_post(string $username, string $password): ResponseInterface
    {
        $Response = self::get_structured_ok_response();
        $struct =& $Response->getBody()->getStructure();
        $struct['user_logged_in'] = false;

        $Request = self::get_request();
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
                'user_name'         => $username,
                //'user_is_disabled'  => 0,//prevent the login for disabled users
            ]);

            if ($User->user_is_disabled) {
                $struct['message'] = sprintf(t::_('The user %1$s is disabled.'), $User->user_name);
            } elseif ($User->verify_password($password)) {

                $Token = new Token();
                $Token->user_uuid = $User->get_uuid();
                $Token = $Token->generate_new_token();

                if ($User->is_member_of(new Role(ADMINISTRATOR))) {
                    $struct['route'] = '/admin';
                } else {
                    $struct['route'] = '/';
                }

                $struct['user_logged_in'] = true;
            } else {
                // do nothing; user is not logged
                $struct['message'] = t::_('Wrong username or password!');
            }

        } catch (RecordNotFoundException $exception) {
            $struct['message'] = t::_('Wrong username or password!');
        }

        if (isset($Token)) {
            $Response = $Response->withHeader('token', $Token->token_string);
        } else {
            $Response = $Response->withStatus(StatusCode::HTTP_FORBIDDEN);
        }

        return $Response;
    }

    public function register_get(): ResponseInterface
    {
        $struct = [];
        return self::get_structured_ok_response($struct);
    }

    /**
     * @param string $user_email
     * @param string $user_name
     * @param string $user_password
     * @param string $user_password_confirmation
     * @return ResponseInterface
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\LogicException
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     * @throws \Guzaba2\Kernel\Exceptions\ConfigurationException
     * @throws \Guzaba2\Orm\Exceptions\MultipleValidationFailedException
     * @throws \ReflectionException
     * @throws \Guzaba2\Coroutine\Exceptions\ContextDestroyedException
     */
    public function register_post(string $user_email, string $user_name, string $user_password, string $user_password_confirmation): ResponseInterface
    {
        $struct = [];
        $User = new User();
        $User->user_email = $user_email;
        $User->user_name = $user_name;
        $User->set_password($user_password, $user_password_confirmation);
//        try {
//            $User->write();
//        } catch (\Throwable $E) {
//            print $E->getMessage().PHP_EOL;
//            print $E->getTraceAsString();
//        }
        $User->write();

        $struct['message'] = sprintf(t::_('The user %1$s was created successfully.'), $User->user_name);
        $struct['uuid'] = $User->get_uuid();
        return self::get_structured_ok_response($struct);
    }

//    public function register(string $email, string $username, string $password)
//    {
//        $Response = parent::get_structured_ok_response();
//        $struct = &$Response->getBody()->getStructure();
//
//        try {
//            $user = new User(['user_email' => $email]);
//            $struct['message'] = t::_('Email already exist!');
//            $Response = $Response->withStatus(StatusCode::HTTP_FOUND);
//        } catch(RecordNotFoundException $E) {
//            $user = new User();
//            $user->user_email = $email;
//            $user->user_name = $username;
//            $user->user_password = $password;
//            $user->save();
//
//            $Response = $Response->withStatus(StatusCode::HTTP_CREATED);
//            $struct['message'] = t::_('User was registered successful.');
//        }
//        return $Response;
//    }
}
