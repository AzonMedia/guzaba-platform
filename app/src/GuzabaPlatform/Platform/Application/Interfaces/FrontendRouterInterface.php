<?php
declare(strict_types=1);

namespace GuzabaPlatform\Platform\Application\Interfaces;

interface FrontendRouterInterface
{

    /**
     * @param string $path URL path like "/something"
     * @param string $component Path to the frontend component
     * @param string $name Optional name for the route
     */
    public function add_route(string $path, string $component, string $name='') : void ;

    /**
     * Returns all routes
     * @return array
     */
    public function get_routes() : array ;

    public function get_route(string $route) : array;

    /**
     * Returns the file to which the configuration is to be dumped
     * @return string
     */
    public function get_router_config_file() : string ;

    /**
     * Dumps the accumulated routes so far in the $router_config_file file.
     * This method is provided as a callback on the server::_before_start event
     */
    public function dump_routes() : void ;
}