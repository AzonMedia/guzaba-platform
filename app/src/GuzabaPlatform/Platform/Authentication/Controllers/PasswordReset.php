<?php

namespace GuzabaPlatform\Platform\Authentication\Controllers;

use Psr\Http\Message\ResponseInterface;
use Guzaba2\Mvc\Controller;
use Guzaba2\Coroutine\Coroutine;
use Guzaba2\Translator\Translator as t;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Database\Exceptions\DuplicateKeyException;
use Guzaba2\Database\Exceptions\ForeignKeyConstraintException;
use GuzabaPlatform\Platform\Authentication\Models\User;

class PasswordReset extends Controller
{

    private $User = NULL;

    /**
     * @param int $user_id
     */
    public function _init(){
        $Context = Coroutine::getContext();

        // TODO use $Context->current_user_id instead of 1
        $user_id = $Context->current_user_id;
        // $user_id = 1;

        if (0 === $user_id) {
            // TODO redirect to login
            die('Please log in!');
        }

        try {
            $this->User = new User($user_id);
        } catch (RecordNotFoundException $exception) {
            // TODO redirect to login
            die('User not found!');
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
        } catch (ValidationFailedException $exception) {
            $struct['message'] = t::_($exception->getMessage());
        }

        $Response = parent::get_structured_ok_response($struct);

        return $Response;
    }
}
