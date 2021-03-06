<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Home\Controllers;

use Guzaba2\Http\Interfaces\ServerInterface;
use Guzaba2\Http\Method;
use Guzaba2\Mvc\ActiveRecordController;
use Guzaba2\Translator\Translator as t;
use GuzabaPlatform\Platform\Application\BaseController;
use GuzabaPlatform\Platform\Application\GuzabaPlatform as GP;
use Psr\Http\Message\ResponseInterface;

class Home extends BaseController
{

//    public const ROUTES = [
//        '/'   => [ //does not use the API prefix as this is not an API route but serves the static content (the main Vue template)
//            Method::HTTP_ALL       => [self::class, 'main'],
//        ],
//    ];

    protected const CONFIG_DEFAULTS = [
        'routes'    => [
            '/'   => [ //does not use the API prefix as this is not an API route but serves the static content (the main Vue template)
                Method::HTTP_ALL       => [self::class, 'main'],
            ],
        ],
        'services'  => [
            'Server'
        ],
    ];

    protected const CONFIG_RUNTIME = [];

    public function main() : ResponseInterface
    {

//        $struct = [];
//        $struct['company'] = t::_('Test company name');
//        $struct['content1'] = 'test content 1';
//        $struct['content2'] = 'test content 2';
//        $Response = parent::get_structured_ok_response($struct);
        //
        //print $this->get_request()->get_server()->get_document_root();
        //$index_file_path = $this->get_request()->get_server()->get_document_root().'/index.html';
        /** @var ServerInterface $Server */
        $Server = self::get_service('Server');
        $index_file_path = $Server->get_document_root().'/index.html';
        if (file_exists($index_file_path)) {
            $contents = \Swoole\Coroutine\System::readFile($index_file_path);
        } else {
            $contents = sprintf('The Vue front end is not compiled. Please see ./public_src/build.');
        }


        $Response = parent::get_string_ok_response($contents);
        return $Response;
    }

}