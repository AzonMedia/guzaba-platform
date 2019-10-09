<?php

namespace GuzabaPlatform\Platform\Home\Controllers;

use Guzaba2\Mvc\Controller;
use Guzaba2\Translator\Translator as t;
use Psr\Http\Message\ResponseInterface;

class Home extends Controller
{

    public function main() : ResponseInterface
    {

        $struct = [];
        $struct['company'] = t::_('Test company name');
        $struct['content1'] = 'test content 1';
        $struct['content2'] = 'test content 2';
        $Response = parent::get_structured_ok_response($struct);
        return $Response;
    }

}