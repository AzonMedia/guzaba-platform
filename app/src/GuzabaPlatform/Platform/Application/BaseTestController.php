<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application;


use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseTestController
 * @package GuzabaPlatform\Platform\Application
 *
 * Base controller for API tests controllers
 */
abstract class BaseTestController extends BaseController
{
    protected static function get_test_response(iterable $struct): ResponseInterface
    {
        if (array_keys($struct['events']) === range(1, $struct['total_events'])) {
            $struct['response'] = 'ok';
            $Response = self::get_structured_ok_response( $struct );
        } else {
            $struct['response'] = 'fail';
            $Response = self::get_structured_servererror_response( $struct );
        }
        return $Response;
    }
}