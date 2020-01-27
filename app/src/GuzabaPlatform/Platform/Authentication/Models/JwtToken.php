<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;

use GuzabaPlatform\Platform\Authentication\Interfaces\TokenInterface;
use Guzaba2\Translator\Translator as t;
use Guzaba2\Orm\Exceptions\RecordNotFoundException;
use Guzaba2\Orm\ActiveRecord;
use Guzaba2\Base\Exceptions\LogicException;
use Guzaba2\Base\Base;
use Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface;

/**
 * Class Token
 * Contains the match between user and an authentication token.
 * There is no session data stored.
 * The primary key is token_string.
 * @package GuzabaPlatform\Platform\Authentication\Models
 */
class JwtToken extends Base implements TokenInterface
{
    protected const CONFIG_DEFAULTS = [
        'secret'      		=> 'secret',
        'allowed_algs'      => 'HS256',
        'expiration_time'	=>  1 * 24 * 60 * 60, // 1 day
        'renew_time'		=>  1 * 60 * 60, // 1 hour
        'services'          => [
            'CurrentUser'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public $user_id;
    public $token_string;
    public $token_expiration_time;

    /**
     * @param array token['token_string'], to be compatible with GuzabaPlatform\Platform\Authentication\Models\Token
     * which extends ActiveRecord class
     * 
     * @throws RecordNotFoundException
     */
    public function __construct(array $token = [])
    {
    	if (is_int($token)) {
    		 throw new LogicException(sprintf(t::_('JwtToken must be instanced with array with index token_string.')));
    	}

    	if (is_array($token) && isset($token['token_string'])) {
    		$this->token_string = $token['token_string'];

    		try {
    			$decoded = JWT::decode($this->token_string, self::CONFIG_RUNTIME['secret'], [self::CONFIG_RUNTIME['allowed_algs']]);
	    		$this->user_id = $decoded->uid;
	    		$this->token_expiration_time = $decoded->exp;
    		} catch (\Exception $e) {
    			throw new RecordNotFoundException(sprintf(t::_('No metadata for class %s, object_id %s was found.'), get_class($this), 0));
    		}
    	}
    }

    /**
     * generates new token on user login
     * @return Token
     *
     * throws InvalidArgumentException
     */
    public function generate_new_token(): TokenInterface
    {
        if (!$this->user_id > 0) {
            throw new InvalidArgumentException(sprintf(t::_('Cannot generate token without user_id.')));
        }

    	$this->token_expiration_time = time() + self::CONFIG_RUNTIME['expiration_time'];       

		$token = array(
			"uid" => $this->user_id,
		    "exp" => $this->token_expiration_time
		);

		$this->token_string = JWT::encode($token, self::CONFIG_RUNTIME['secret']);

        return $this;
    }

    /**
     * updates token_expiration_time on every request
     * @return Token
     */
    public function update_token() : TokenInterface
    {
    	if ($this->token_expiration_time < time() + self::CONFIG_RUNTIME['renew_time']) {
    		return $this->generate_new_token();
    	} else {
    		return $this;
    	}
    }

    /**
     * @param RequestInterface $Request
     */
    public static function get_user_id_from_request(RequestInterface $Request) /* mixed */
    {
        $ret = self::get_service('CurrentUser')->get_default_user_uuid();
        $headers = $Request->getHeaders();
        if (isset($headers['token'])) {
            try {
                $Token = new JwtToken(['token_string' => $headers['token'][0]]);
                if ($Token->token_expiration_time > time()) {
                    $ret = $Token->user_id;
                }
            } catch (RecordNotFoundException $Exception) {

            }
        }
        return $ret;
    }
}
