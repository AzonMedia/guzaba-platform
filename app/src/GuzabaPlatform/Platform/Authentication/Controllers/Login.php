<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Guzaba2\Http\Method;
use Guzaba2\Mvc\ActiveRecordController;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\BaseController;
use Psr\Http\Message\ResponseInterface;
use GuzabaPlatform\Platform\Authentication\Models\User;
// use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
use GuzabaPlatform\Platform\Authentication\Models\JwtToken as Token;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;

use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;

//not-used
class Login extends BaseController
{

//    public const ROUTES = [
//        GP::API_ROUTE_PREFIX.'/login'   => [
//        //'/login'   => [
//            Method::HTTP_GET_HEAD_OPT       => [self::class, 'main'],
//            Method::HTTP_POST               => [self::class, 'login'],
//        ],
//    ];

    protected const CONFIG_DEFAULTS = [
        'routes'    => [
            '/login-not-used'   => [
                //'/login'   => [
                Method::HTTP_GET_HEAD_OPT       => [self::class, 'main'],
                Method::HTTP_POST               => [self::class, 'login'],
            ],
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {
        $struct = [];

        $beforeRender_templates = [
            // '',
            'template_header1',
            'template_header2',
        ];

        $afterRender_templates = [
            // '',
            'template_footer1',
            'template_footer2',
        ];

        if (rand(1, 100) <= 50) {
            $struct['hooks']['beforeRender']['name'] = $beforeRender_templates[array_rand($beforeRender_templates)];
            $struct['hooks']['beforeRender']['data'] = [
                'var1' => 12,
                'var2' => 'string 1',
                'var3' => 'string 2',
            ];
        }

        $struct['hooks']['afterRender']['name'] = $afterRender_templates[array_rand($afterRender_templates)];
        $struct['hooks']['afterRender']['data'] = [
            'var1' => 4638412,
            'var2' => 481,
            'var3' => 'again string',
        ];

        $struct['username_placeholder'] = t::_('Username');
        $struct['password_placeholder'] = t::_('Password');
        $struct['login_button'] = t::_('Login');

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    public function login(string $username, string $password) : ResponseInterface
    {
        $struct = [];
        $struct['user_logged_in'] = FALSE;

        $Request = parent::get_request();
        $headers = $Request->getHeaders();

        if (isset($headers['token'])) {
            try {
                $oldToken = new Token(['token_string' => $headers['token'][0]]);
                // $oldToken->delete();
            } catch (RecordNotFoundException $exception) {
                // do nothing; user is not logged
            }

            $Response = $Response->withoutHeader('token');
        }

        try {
            $User = new User([
                'user_name' => $username
            ], TRUE, TRUE);

            if (password_verify($password, $User->user_password)) {

                $Token = new Token();
                $Token->user_id = $User->user_id;
                $Token = $Token->generate_new_token();

                $struct['user_logged_in'] = TRUE;
            } else {
                // do nothing; user is not logged
            }

        } catch (RecordNotFoundException $exception) {
            $struct['message'] = t::_('Wrong username or password!');
        }

        $Response = parent::get_structured_ok_response($struct);

        if (isset($Token)) {
            $Response = $Response->withHeader('token', $Token->token_string);
        }

        return $Response;
    }
}
