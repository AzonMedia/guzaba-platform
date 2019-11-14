<?php

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use Psr\Http\Message\ResponseInterface;
use Guzaba2\Mvc\Controller;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Translator\Translator as t;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\InstanceValidationFailedFailedException;
use Guzaba2\Database\Exceptions\DuplicateKeyException;
use Guzaba2\Database\Exceptions\ForeignKeyConstraintException;
use GuzabaPlatform\Platform\Authentication\Models\User;

class PasswordReset extends Controller
{

    public const ROUTES = [
        GP::API_ROUTE_PREFIX.'/password-reset'   => [
        //'/password-reset'   => [
            Method::HTTP_GET_HEAD_OPT       => [self::class, 'main'],
            Method::HTTP_POST               => [self::class, 'save'],
        ],
    ];

    private User $User;

    public function _init(){
        $Context = Coroutine::getContext();

        $user_id = $Context->current_user_id;

        if (0 === $user_id) {
            $Response = self::get_structured_unauthorized_response();
            return $Response;
        }

        try {
            $this->User = new User($user_id);
        } catch (RecordNotFoundException $exception) {
            $Response = self::get_structured_notfound_response();
            return $Response;
        }
    }

    public function main() : ResponseInterface
    {
        $struct = [];

        $struct['new_password_1_ph'] = t::_('New password');
        $struct['new_password_2_ph'] = t::_('New password confirmation');
        $struct['old_password_ph'] = t::_('Old password');
        $struct['save_button'] = t::_('Change Password');

        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

    public function save(string $new_password_1, string $new_password_2, string $old_password) : ResponseInterface
    {
        $struct = [];

        try {
            $this->User->change_password($new_password_1, $new_password_2, $old_password);

            $struct['message'] = t::_('Your password was changed!');
        } catch (InstanceValidationFailedFailedException $exception) {
            $struct['message'] = t::_($exception->getMessage());
        }

        $Response = parent::get_structured_ok_response($struct);

        return $Response;
    }
}
