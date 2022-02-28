<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;

use GuzabaPlatform\Platform\Authentication\Interfaces\TokenInterface;
use Guzaba2\Orm\ActiveRecord;

/**
 * Class Token
 * Contains the match between user and an authentication token.
 * There is no session data stored.
 * The primary key is token_id.
 * @package GuzabaPlatform\Platform\Authentication\Models
 */
class Token extends ActiveRecord implements TokenInterface
{
    protected const CONFIG_DEFAULTS = [
        'main_table'      	=> 'tokens',
        'expiration_time'	=>  4 * 60 * 60 // 4 hours
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * generates new token on user login
     * @return Token
     */
    public function generate_new_token(): TokenInterface
    {
        if (!$this->user_id > 0) {
            throw new InvalidArgumentException(sprintf(t::_('Cannot generate token without user_id.')));
        }

        $this->token_expiration_time = time() + self::CONFIG_RUNTIME['expiration_time'];
        $this->token_uuid = '0';
        $this->token_string = bin2hex(openssl_random_pseudo_bytes(16)) . str_replace("." , "", microtime(true));
        $this->save();

        return $this;
    }

    /**
     * updates token_expiration_time on every request
     * @return Token
     */
    public function update_token() : TokenInterface
    {
        // if ($this->token_expiration_time < time()) {
        // 	return $this->generate_new_token(FALSE);
        // } else {
            // just update token_expiration time => _before_save will do that
            $this->token_expiration_time = time() + self::CONFIG_RUNTIME['expiration_time'];
            $this->save();
            return $this;
        // }
    }
}
