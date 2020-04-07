<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;

use Azonmedia\Exceptions\InvalidArgumentException;
use Guzaba2\Base\Exceptions\RunTimeException;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Orm\Exceptions\InstanceValidationFailedFailedException;
use Guzaba2\Orm\Exceptions\MultipleValidationFailedException;
use Guzaba2\Orm\Exceptions\ValidationFailedException;
use Guzaba2\Orm\Interfaces\ValidationFailedExceptionInterface;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Authentication\Models\Token as Token;
// use GuzabaPlatform\Platform\Authentication\Models\JwtToken as Token;

/**
 * Class User
 * @package GuzabaPlatform\Platform\Authentication\Models
 *
 * @property int user_id
 * @property string user_name
 * @property string user_email
 * @property string user_password
 * @property int role_id
 */
class User extends \Guzaba2\Authorization\User
{
    protected const CONFIG_DEFAULTS = [
        //'main_table'            => 'users',//inherit
        'cost'                  => 12,
        'alg'                   => PASSWORD_BCRYPT,
        'pepper'                => 'fks48>SKtglsd.vc34',//if this is changed it will invalidate all user passwords
        //'route'                 => '/user',//inherit
        'password_settings'     => [
            'min_length'            => 8,
            'max_length'            => 50,
            'enforce_strong'        => TRUE,
        ],
	];

    protected const CONFIG_RUNTIME = [];

    /**
     * @param string $new_password
     * @param string $new_password_confirmation
     * @param string $old_password
     *
     * @throws InvalidArgumentException
     * @throws MultipleValidationFailedException
     * @throws RunTimeException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function change_password(string $new_password, string $new_password_confirmation, string $old_password) : void
    {
        ///** @var ValidationFailedExceptionInterface[] $validation_exceptions */
        //$validation_exceptions = $this->validate_plain_password($new_password);

//        if ($new_password != $new_password_confirmation) {
//            $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password and the password confirmation do not match.')));
//        }

        $this->set_password($new_password, $new_password_confirmation);

        if (!$this->verify_password($old_password)) {
            $validation_exceptions[] = new ValidationFailedException($this, 'old_password', sprintf(t::_('The old password is not correct.')));
        }

        if (!empty($validation_exceptions)) {
            throw new MultipleValidationFailedException($validation_exceptions);
        }

        $this->user_password = $new_password;//this will trigger self::_before_set_user_password()
        $this->write();
    }

    /**
     * @param string $new_password
     * @param string $new_password_confirmation
     * @throws InvalidArgumentException
     * @throws MultipleValidationFailedException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function set_password(string $new_password, string $new_password_confirmation) : void
    {
        /** @var ValidationFailedExceptionInterface[] $validation_exceptions */
        $validation_exceptions = $this->validate_plain_password($new_password);

        if ($new_password != $new_password_confirmation) {
            $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password and the password confirmation do not match.')));
        }

        if (!empty($validation_exceptions)) {
            throw new MultipleValidationFailedException($validation_exceptions);
        }

        $this->user_password = $new_password;//this will trigger self::_before_set_user_password()
    }

    /**
     * Validates a plain text password
     * This method is not a hook!
     * @param string $password
     * @return ValidationFailedExceptionInterface[]
     * @throws InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
     */
    public function validate_plain_password(string $password): array
    {
        $validation_exceptions = [];
        if (!$password) {
            $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('Please enter password.') ));
        }

        if (strlen($password) < self::CONFIG_RUNTIME['password_settings']['min_length']) {
            $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password must be at least %1s characters.'), self::CONFIG_RUNTIME['password_settings']['min_length']));
        }

        if (strlen($password) > self::CONFIG_RUNTIME['password_settings']['max_length']) {
            $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password can be at most %1s characters.'), self::CONFIG_RUNTIME['password_settings']['max_length']));
        }

        if (self::CONFIG_RUNTIME['password_settings']['enforce_strong']) {
            //$common_message = sprintf(t::_('The password must contain at least one digit, one small letter, one CAPITAL letter and one symbol (like *#@%%!$+_^<>.,).'));
            if (!preg_match("#[0-9]+#", $password)) {
                $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password contains no digit.')));
            }
            if (!preg_match("#[a-z]+#", $password)) {
                $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password contains no small letter.')));
            }
            if (!preg_match("#[A-Z]+#", $password)) {
                $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password contains no capital letter.')));
            }
            if (!preg_match("#\W+|_#", $password)) {
                $validation_exceptions[] = new ValidationFailedException($this, 'password', sprintf(t::_('The password contains no symbol.')));
            }
        }
        return $validation_exceptions;
    }

    //protected function validate_plain_password

    /**
     * Adds pepper and hashes the password.
     * @param string $password
     * @return string
     */
    protected function _before_set_user_password(string $password) : string
    {
        //$ret = password_hash($password, self::CONFIG_RUNTIME['alg'], ['cost' => self::CONFIG_RUNTIME['cost']]);
        //@see https://www.php.net/manual/en/function.password-hash.php#124138
        $peppered_password = hash_hmac("sha256", $password, self::CONFIG_RUNTIME['pepper']);
        $ret = password_hash($peppered_password, self::CONFIG_RUNTIME['alg'], ['cost' => self::CONFIG_RUNTIME['cost']]);
        return $ret;
    }

    /**
     * Verifies the provided plain text $password to be the actual user password.
     * To be used for user authentication.
     * @param string $password
     * @return bool
     */
    public function verify_password(string $password) : bool
    {
        $peppered_password = hash_hmac("sha256", $password, self::CONFIG_RUNTIME['pepper']);
        return password_verify($peppered_password, $this->user_password);
    }
}
