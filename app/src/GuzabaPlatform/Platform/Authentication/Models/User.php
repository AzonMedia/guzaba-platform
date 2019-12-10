<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;

use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Orm\Exceptions\InstanceValidationFailedFailedException;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
// use GuzabaPlatform\Platform\Authentication\Models\JwtToken as Token;

class User extends \Guzaba2\Authorization\User
{
    protected const CONFIG_DEFAULTS = [
        'main_table'            => 'users',
        'cost'                  => 12,
        'alg'                   => PASSWORD_BCRYPT,
        'route'                 => '/user',
	];

    protected const CONFIG_RUNTIME = [];

    /**
     * @param string $new_password_1
     * @param string $new_password_2
     * @param string $old_password
     *
     * @throws InstanceValidationFailedFailedException
     */
    public function change_password(string $new_password_1, string $new_password_2, string $old_password)
    {
        $messages = [];

        if (!$new_password_1) {
            $messages[] = ['new_password_1', 1, sprintf(t::_('Please enter password.'))];
            // $this->add_validation_error('new_password_1', self::V_MISSING, sprintf(t::_('Please enter password.')));
        }

        if (!$new_password_2) {
            $messages[] = ['new_password_2', 1, sprintf(t::_('Please enter password confirmation.'))];
            // $this->add_validation_error('new_password_2', self::V_MISSING, sprintf(t::_('Please enter password confirmation.')));
        }

        if ($new_password_1 != $new_password_2) {
            $messages[] = ['new_password_1', 1, sprintf(t::_('The password and the password confirmation do not match.'))];
            // $this->add_validation_error('new_password_1', self::V_NOTVALID, sprintf(t::_('The password and the password confirmation do not match.')));
        }

        if (strlen($new_password_1) < 8) {
            $messages[] = ['new_password_1', 1, sprintf(t::_('The password must be at least 8 characters.'))];
            // $this->add_validation_error('new_password_1', self::V_NOTVALID, sprintf(t::_('The password must be at least 8 characters.')));
        }

        if (strlen($new_password_1) > 20) {
            $messages[] = ['new_password_1', 1, sprintf(t::_('The password can be at most 20 characters.'))];
            // $this->add_validation_error('new_password_1', self::V_NOTVALID, sprintf(t::_('The password can be at most 20 characters.')));
        }

        if (!password_verify($old_password, $this->user_password)) {
            $messages[] = ['old_password', 1, sprintf(t::_('The old password do not match.'))];
            // $this->add_validation_error('old_password', self::V_NOTVALID, sprintf(t::_('The old password do not match.')));
        }

        if (!empty($messages)) {
            throw new InstanceValidationFailedFailedException($messages);
        }

        //$this->user_password = password_hash($new_password_1, self::CONFIG_RUNTIME['alg'], ['cost' => self::CONFIG_RUNTIME['cost']]);
        $this->user_password = $new_password_1;
        $this->save();
    }

    protected function _before_set_user_password(string $value) : string
    {
        $res = password_hash($value, self::CONFIG_RUNTIME['alg'], ['cost' => self::CONFIG_RUNTIME['cost']]);
        return $res;
    }
}
