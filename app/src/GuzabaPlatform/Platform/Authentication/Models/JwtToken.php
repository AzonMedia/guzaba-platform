<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Authentication\Models;

use Guzaba2\Base\Exceptions\InvalidArgumentException;
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
 *
 * @property int token_id
 * @property string token_uuid
 * @property string token_string
 * @property int user_id
 * @property int token_expiration_time
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

    public string $user_uuid;
    public string $token_string;
    public int $token_expiration_time;//unitime

    /**
     * @param array token['token_string'], to be compatible with GuzabaPlatform\Platform\Authentication\Models\Token
     * which extends ActiveRecord class
     *
     * @throws LogicException
     * @throws RecordNotFoundException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \ReflectionException
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
	    		$this->user_uuid = $decoded->user_uuid;
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
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     */
    public function generate_new_token(): TokenInterface
    {
        if (!$this->user_uuid) {
            throw new InvalidArgumentException(sprintf(t::_('Cannot generate token without user_id.')));
        }

    	$this->token_expiration_time = time() + self::CONFIG_RUNTIME['expiration_time'];       

		$token = array(
			"user_uuid" => $this->user_uuid,
		    "exp" => $this->token_expiration_time
		);

		$this->token_string = JWT::encode($token, self::CONFIG_RUNTIME['secret']);

        return $this;
    }

    /**
     * updates token_expiration_time on every request
     * @return Token
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
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
     * @return string Returns the UUID
     * @throws LogicException
     * @throws \Azonmedia\Exceptions\InvalidArgumentException
     * @throws \Guzaba2\Base\Exceptions\RunTimeException
     * @throws \ReflectionException
     */
    public static function get_user_uuid_from_request(RequestInterface $Request) /* mixed */
    {
        /** @var string $user_uuid */
        $user_uuid = self::get_service('CurrentUser')->get_default_user_uuid();
        $headers = $Request->getHeaders();
        if (isset($headers['token'])) {
            try {
                $Token = new JwtToken(['token_string' => $headers['token'][0]]);
                if ($Token->token_expiration_time > time()) {
                    $user_uuid = $Token->user_uuid;
                }
            } catch (RecordNotFoundException $Exception) {

            }
        }
        return $user_uuid;
    }
}
