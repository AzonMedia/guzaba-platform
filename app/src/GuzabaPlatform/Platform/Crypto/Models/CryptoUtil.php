<?php

declare(strict_types=1);

namespace GuzabaPlatform\Platform\Crypto\Models;

use Guzaba2\Base\Interfaces\BaseInterface;
use Guzaba2\Base\Traits\BaseTrait;

class CryptoUtil extends \Azonmedia\Utilities\CryptoUtil implements BaseInterface
{

    use BaseTrait;

    protected const CONFIG_DEFAULTS = [
        'openssl_encryption_key'            => 'asdl548<.gf.n<SJ3fnaasd',
        'openssl_encryption_method'         => \Azonmedia\Utilities\CryptoUtil::OPENSSL_ENCRYPTION_METHOD,
    ];

    protected const CONFIG_RUNTIME = [];

    public static function openssl_encrypt(string $plain_string, string $key = self::CONFIG_RUNTIME['openssl_encryption_key'], string $encryption_method = self::CONFIG_RUNTIME['openssl_encryption_method']): string
    {
        return parent::openssl_encrypt($plain_string, $key, $encryption_method);
    }

    public static function openssl_decrypt(string $encrypted_string, string $key = self::CONFIG_RUNTIME['openssl_encryption_key'], string $encryption_method = self::CONFIG_RUNTIME['openssl_encryption_method']): ?string
    {
        return parent::openssl_decrypt($encrypted_string, $key, $encryption_method);
    }
}