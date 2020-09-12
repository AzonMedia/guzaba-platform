<?php

declare(strict_types=1);

namespace GuzabaPlatform\Platform\Redirect\Controllers;

use Guzaba2\Http\Method;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Crypto\Models\Crypto;
use Guzaba2\Translator\Translator as t;
use Psr\Http\Message\ResponseInterface;

class Redirect extends BaseController
{
    protected const CONFIG_DEFAULTS = [
        'routes'    => [
            '/redirect/{to}' => [
                Method::HTTP_GET    => [self::class, 'main'],
            ],
        ]
    ];

    protected const CONFIG_RUNTIME = [];

    /**
     * @param string $to
     * @return ResponseInterface
     */
    public function main(string $to): ResponseInterface
    {
        $decrypted_to = Crypto::openssl_decrypt($to);
        $struct = [];
        if ($decrypted_to === null) {
            $struct['message'] = sprintf(t::_('Invalid redirect string.'));
        } else {
            $struct['redirect'] = $decrypted_to;
        }

        return self::get_structured_ok_response($struct);
    }
}