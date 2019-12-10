<?php
declare(strict_types=1);


namespace GuzabaPlatform\Platform\Application;


use Azonmedia\UrlRewriting\Interfaces\RewriterInterface;
use Guzaba2\Base\Base;
use Psr\Http\Message\RequestInterface;

/**
 * Class UrlRewritingRules
 * Rewrites ALL URLs that do not begin with $prefix to /
 * This is to be used to redirect all requests that are not served by the static handler and are not API requests (begin with /api/) to /.
 * @package GuzabaPlatform\Platform\Application
 */
class UrlRewritingRules extends Base
    implements RewriterInterface
{

    protected string $prefix = '';

    public function __construct(string $prefix)
    {
        parent::__construct();
        $this->prefix = $prefix;
    }

    public function rewrite_uri(string $uri): string
    {
        // TODO: Implement rewrite_uri() method.
    }

    /**
     * @param RequestInterface $Request
     * @return RequestInterface
     */
    public function rewrite_request(RequestInterface $Request): RequestInterface
    {
        $path = $Request->getUri()->getPath();
        if (strpos($path, $this->prefix)!==0) { //does not start with...
            $path = '/';
            $Uri = $Request->getUri();
            $Uri = $Uri->withPath($path);
            $Request = $Request->withUri($Uri);
        }
        return $Request;
    }
}