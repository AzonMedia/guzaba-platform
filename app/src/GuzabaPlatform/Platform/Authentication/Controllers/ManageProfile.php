<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Mvc\Controller;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Translator\Translator as t;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Database\Exceptions\DuplicateKeyException;
use Guzaba2\Database\Exceptions\ForeignKeyConstraintException;
use GuzabaPlatform\Platform\Authentication\Models\User;

class ManageProfile extends Controller
{

    public const ROUTES = [
        GP::API_ROUTE_PREFIX.'/manage-profile'   => [
        //'/user-login'   => [
            Method::HTTP_GET_HEAD_OPT       => [self::class, 'main'],
            Method::HTTP_POST               => [self::class, 'update'],
        ],
    ];

    private User $User;


    public function _init() : ResponseInterface
    {
        $Context = Coroutine::getContext();

        // TODO use $Context->current_user_id instead of 1
        $user_id = $Context->current_user_id;
        // $user_id = 1;

        if (0 === $user_id) {
            //there is no API redirect - instead return an error
            $Response = self::get_structured_unauthorized_response();
            return $Response;
        }

        try {
            $this->User = new User($user_id);
        } catch (RecordNotFoundException $exception) {
            //there is no API redirect - instead return an error
            $Response = self::get_structured_notfound_response();
            return $Response;
        }
    }

    public function main() : ResponseInterface
    {
        $struct = [];

        $struct['user_name'] = $this->User->user_name;
        $struct['user_email'] = $this->User->user_email;

        $struct['username_placeholder'] = t::_('Username');
        $struct['email_placeholder'] = t::_('Email');
        $struct['save_button'] = t::_('Save');

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    public function update(string $user_name, string $user_email) : ResponseInterface
    {
        $struct = [];

        try {
            $this->User->user_name = $user_name;
            $this->User->user_email = $user_email;
            $this->User->save();

            $struct['message'] = t::_('Your profile is saved!');
        } catch (DuplicateKeyException $exception) {
            $struct['message'] = $exception->getMessage();
        } catch (ForeignKeyConstraintException $exception) {
            $struct['message'] = $exception->getMessage();
        }

        $Response = parent::get_structured_ok_response($struct);

        return $Response;
    }
}
